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
            $sheet->setTitle('Laporan Pembelian');

            $row = 1;

            // Header Utama - Judul Laporan
            $sheet->setCellValue('A' . $row, 'LAPORAN PEMBELIAN TEACHING FACTORY SMK MUHAMMADIYAH 1 PALEMBANG');
            $sheet->mergeCells('A' . $row . ':I' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(28);
            $row++;

            // Alamat Sekolah
            $sheet->setCellValue('A' . $row, 'Jl. Balayudha, RT.16/RW.4, Ario Kemuning, Kec. Kemuning, Kota Palembang, Sumatera Selatan 30128 | Telepon: (0711) 414662');
            $sheet->mergeCells('A' . $row . ':I' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => [
                    'size' => 10
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;

            // Periode Laporan
            if ($request->filled(['start_date', 'end_date'])) {
                $sheet->setCellValue('A' . $row, 'Periode: ' . Carbon::parse($request->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($request->end_date)->format('d/m/Y'));
            } else {
                $sheet->setCellValue('A' . $row, 'Periode: Semua Data');
            }
            $sheet->mergeCells('A' . $row . ':I' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;

            // Garis pembatas
            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_MEDIUM
                    ]
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(2);
            $row++;

            // Spasi kosong
            $sheet->getRowDimension($row)->setRowHeight(10);
            $row++;

            // Header kolom
            $headers = ['No', 'Nomor PO', 'Tanggal', 'Supplier', 'Nama Barang', 'Batch', 'Qty', 'Harga', 'Total'];
            foreach (range('A', 'I') as $key => $column) {
                $sheet->setCellValue($column . $row, $headers[$key]);
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Style header row
            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '366092'
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);

            $sheet->getRowDimension($row)->setRowHeight(30);
            $row++;

            // Fill data
            $no = 1;
            $totalPurchase = 0;

            foreach ($purchases as $po) {
                foreach ($po->items as $item) {
                    $total = $item->quantity * $item->unit_price;
                    $totalPurchase += $total;

                    // Get batch number from goods receipt
                    $itemName = $item->product_name ?? '-';
                    $batchNumber = '-';
                    
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
                    $sheet->setCellValue('E' . $row, $item->product_name);
                    $sheet->setCellValue('F' . $row, $batchNumber);
                    $sheet->setCellValue('G' . $row, $item->quantity);
                    $sheet->setCellValue('H' . $row, $item->unit_price);
                    $sheet->setCellValue('I' . $row, $total);

                    // Style data row dengan alternating colors
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

                    // Alternating row colors
                    if ($no % 2 == 0) {
                        $rowStyle['fill'] = [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'E7E6E6'
                            ]
                        ];
                    }

                    $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($rowStyle);
                    $sheet->getRowDimension($row)->setRowHeight(22);

                    // Set alignment untuk setiap kolom
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    // Format sebagai currency
                    $sheet->getStyle('H' . $row . ':I' . $row)->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $row++;
                    $no++;
                }
            }

            // Total row
            $sheet->setCellValue('A' . $row, 'TOTAL PEMBELIAN');
            $sheet->mergeCells('A' . $row . ':H' . $row);
            $sheet->setCellValue('I' . $row, $totalPurchase);

            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '366092'
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_MEDIUM
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

            $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getRowDimension($row)->setRowHeight(28);

            // Report date at bottom
            $row += 2;
            $sheet->setCellValue('G' . $row, 'Tanggal Laporan:');
            $sheet->setCellValue('H' . $row, Carbon::now()->format('d/m/Y'));
            $sheet->mergeCells('H' . $row . ':I' . $row);
            $sheet->getStyle('G' . $row . ':I' . $row)->applyFromArray([
                'font' => [
                    'size' => 11
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(22);

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(25);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(10);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(15);

            // Save file
            $writer = new Xlsx($spreadsheet);
            $filename = 'Laporan_Pembelian_' . date('Y-m-d_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal export data. Silakan coba lagi.');
        }
    }
}