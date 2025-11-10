<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PurchaseReportController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with([
            'items.item', 
            'supplier', 
            'goodsReceipts.inventoryRecords',
            'goodsReceipts.items'
        ])
        ->whereHas('goodsReceipts.items', function($q) {
            $q->whereNotNull('batch_number');
        })
        ->where('status', 'completed');

        // Filter by date range if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Filter by supplier if provided
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchaseOrders = $query->orderBy('created_at', 'desc')->get();

        // Calculate totals
        $totalPurchases = $purchaseOrders->sum('total_amount');
        $totalItems = $purchaseOrders->sum(function($po) {
            return $po->items->sum('quantity');
        });

        // Get summary by supplier
        $supplierSummary = $purchaseOrders->groupBy('supplier_id')
            ->map(function($pos) {
                return [
                    'supplier_name' => $pos->first()->supplier->name,
                    'total_orders' => $pos->count(),
                    'total_amount' => $pos->sum('total_amount'),
                    'total_items' => $pos->sum(function($po) {
                        return $po->items->sum('quantity');
                    })
                ];
            });

        return view('reports.purchases.index', [
            'purchaseOrders' => $purchaseOrders,
            'suppliers' => Supplier::orderBy('name')->get(),
            'totalPurchases' => $totalPurchases,
            'totalItems' => $totalItems,
            'supplierSummary' => $supplierSummary,
            'filters' => $request->only(['start_date', 'end_date', 'supplier_id'])
        ]);
    }

    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'items.item',
            'supplier',
            'goodsReceipts.items',
            'goodsReceipts.receiver',
            'goodsReceipts.inventoryRecords'
        ])->findOrFail($id);

        // Get receipt details
        $receiptDetails = $purchaseOrder->goodsReceipts->map(function($gr) {
            return [
                'gr_number' => $gr->gr_number,
                'receipt_date' => $gr->receipt_date,
                'received_by' => $gr->receiver->name,
                'items' => $gr->items->map(function($item) {
                    return [
                        'product_name' => $item->product_name,
                        'quantity_received' => $item->quantity_received,
                        'expiry_date' => $item->expiry_date,
                        'batch_number' => $item->batch_number,
                        'lot_code' => $item->lot_code,
                        'remaining_quantity' => $item->remaining_quantity,
                        'expiry_status' => $item->expiry_status
                    ];
                })
            ];
        });

        return view('reports.purchases.show', [
            'purchaseOrder' => $purchaseOrder,
            'receiptDetails' => $receiptDetails
        ]);
    }

    public function export(Request $request)
    {
        try {
            // Get data with goods receipts for batch information
            $query = PurchaseOrder::with([
                'items', 
                'supplier', 
                'goodsReceipts.items'
            ])
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc');

            if ($request->filled(['start_date', 'end_date'])) {
                $query->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            $purchases = $query->get();

            if ($purchases->isEmpty()) {
                return back()->with('error', 'Tidak ada data untuk diekspor.');
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Style header simple hitam putih
            $sheet->setCellValue('A1', 'LAPORAN PEMBELIAN TEACHING FACTORY');
            $sheet->mergeCells('A1:I1');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 16
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_MEDIUM
                    ]
                ]
            ]);
            
            // Set tinggi baris judul
            $sheet->getRowDimension(1)->setRowHeight(35);

            // Set period
            // Informasi periode style hitam putih
            if ($request->filled(['start_date', 'end_date'])) {
                $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($request->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($request->end_date)->format('d/m/Y'));
                $sheet->mergeCells('A2:I2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                $sheet->getRowDimension(2)->setRowHeight(25);
            }
            
            // Tambahkan spasi kosong sebelum header kolom
            $sheet->getRowDimension(3)->setRowHeight(10);

            // Set column headers
            $headers = ['No', 'Nomor PO', 'Tanggal', 'Supplier', 'Nama Barang', 'Batch', 'Qty', 'Harga', 'Total'];
            foreach (range('A', 'I') as $key => $column) {
                $sheet->setCellValue($column . '4', $headers[$key]);
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Style header row hitam putih
            $sheet->getStyle('A4:I4')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_MEDIUM
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            
            // Set tinggi baris header
            $sheet->getRowDimension(4)->setRowHeight(30);

            // Fill data
            $row = 5;
            $no = 1;
            $totalPurchase = 0;

            foreach ($purchases as $po) {
                foreach ($po->items as $item) {
                    $total = $item->quantity * $item->unit_price;
                    $totalPurchase += $total;

                    // Get item name from PO item
                    $itemName = $item->product_name ?? '-';
                    $batchNumber = '-';
                    
                    // Get batch number from goods receipt
                    foreach ($po->goodsReceipts as $gr) {
                        foreach ($gr->items as $grItem) {
                            if ($grItem->product_name == $itemName) {
                                $batchNumber = $grItem->batch_number ?? '-';
                                break 2;
                            }
                        }
                    }

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $po->po_number ?? '-');
                    $sheet->setCellValue('C' . $row, Carbon::parse($po->created_at)->format('d/m/Y'));
                    $sheet->setCellValue('D' . $row, $po->supplier->name ?? '-');
                    $sheet->setCellValue('E' . $row, $item->product_name);  // Langsung dari PO item
                    $sheet->setCellValue('F' . $row, $batchNumber);
                    $sheet->setCellValue('G' . $row, $item->quantity);
                    $sheet->setCellValue('H' . $row, number_format($item->unit_price));
                    $sheet->setCellValue('I' . $row, number_format($total));

                    // Style data row hitam putih
                    $rowStyle = [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN
                            ]
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER
                        ]
                    ];
                    
                    $sheet->getStyle('A'.$row.':I'.$row)->applyFromArray($rowStyle);
                    
                    // Set tinggi baris data
                    $sheet->getRowDimension($row)->setRowHeight(25);

                    // Set column alignments
                    $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('B'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('G'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('H'.$row.':I'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    // Zebra striping
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A'.$row.':H'.$row)->getFill()
                            ->setFillType(Fill::FILL_SOLID);
                            // ->setStartColor(['rgb' => 'F8F9FA']);
                    }

                    $row++;
                    $no++;
                }
            }

            // Add total row hitam putih
            $sheet->setCellValue('A' . $row, 'TOTAL PEMBELIAN');
            $sheet->mergeCells('A' . $row . ':H' . $row);
            $sheet->setCellValue('I' . $row, number_format($totalPurchase));
            
            $sheet->getStyle('A'.$row.':I'.$row)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_MEDIUM
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_DOUBLE
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            
            // Set tinggi baris total
            $sheet->getRowDimension($row)->setRowHeight(30);
            
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('H'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Add report date at bottom right
            $row += 2;
            $sheet->setCellValue('G'.$row, 'Tanggal Laporan:');
            $sheet->setCellValue('H'.$row, Carbon::now()->format('d/m/Y'));
            $sheet->mergeCells('H'.$row.':I'.$row);
            $sheet->getStyle('G'.$row.':I'.$row)->applyFromArray([
                'font' => [
                    'size' => 11
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(25);

            // Save file
            $writer = new Xlsx($spreadsheet);
            $filename = 'Laporan_Pembelian_' . date('Y-m-d_His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal export data. Silakan coba lagi.');
        }
    }
}