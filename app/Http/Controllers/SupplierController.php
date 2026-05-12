<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SupplierController extends Controller
{
   
    public function index(): View
    {
        return view('inventory.supplier.index', [
            'suppliers' => Supplier::with('products')->orderBy('name')->get(),
            'type' => 'show'
        ]);
    }

    public function create(): View
    {
        return view('inventory.supplier.form', [    
            'type' => 'create'
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'address' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
        ]);

        // Simpan produk manual jika tersedia
            if ($request->has('products')) {
        foreach ($request->products as $product_name) {
            if (!empty($product_name)) {
                SupplierProduct::create([
                    'supplier_id' => $supplier->id,
                    'product_name' => $product_name
                ]);
            }
        }
    }

        return redirect()->route('supplier.index')->with('status', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier): View
    {
        return view('inventory.supplier.form', [
            'supplier' => $supplier->load('products'),
            'type' => 'edit'
        ]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'address' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'nullable|string|max:255',
        ]);

        $supplier->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
        ]);

        // Update products only if they are provided in the request
        if ($request->has('products')) {
            // Delete old products
            SupplierProduct::where('supplier_id', $supplier->id)->delete();
            
            // Add new products
            foreach ($request->products as $product_name) {
                if (!empty($product_name)) {
                    SupplierProduct::create([
                        'supplier_id' => $supplier->id,
                        'product_name' => $product_name
                    ]);
                }
            }
        }


        return redirect()->route('supplier.index')->with('status', 'Supplier berhasil diubah');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->products()->delete();
        $supplier->delete();

        return redirect()->route('supplier.index')->with('status', 'Supplier berhasil dihapus');
    }

    /**
     * Export suppliers to Excel
     */
    public function export(Request $request)
    {
        try {
            $type = $request->query('type', 'xlsx');
            
            // Get all suppliers
            $suppliers = Supplier::with('products')->orderBy('name')->get();

            // Create Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Daftar Supplier');

            // Document properties
            $spreadsheet->getProperties()
                ->setCreator('Teaching Factory')
                ->setLastModifiedBy('Teaching Factory')
                ->setTitle('Daftar Supplier Teaching Factory')
                ->setSubject('Daftar Supplier');

            // Header
            $sheet->setCellValue('A1', 'DAFTAR SUPPLIER TEACHING FACTORY');
            $sheet->mergeCells('A1:F1');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'name' => 'Calibri',
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);
            $sheet->getRowDimension(1)->setRowHeight(25);

            // Column Headers
            $headers = ['No', 'Nama Supplier', 'Alamat', 'Telepon', 'Email', 'Produk yang Disediakan'];
            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            
            foreach ($columns as $index => $column) {
                $sheet->setCellValue($column . '3', $headers[$index]);
            }

            // Style headers
            $sheet->getStyle('A3:F3')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '366092']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ]);

            // Data rows
            $row = 4;
            foreach ($suppliers as $index => $supplier) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $supplier->name);
                $sheet->setCellValue('C' . $row, $supplier->address);
                $sheet->setCellValue('D' . $row, $supplier->phone);
                $sheet->setCellValue('E' . $row, $supplier->email ?? '-');
                
                // Get product names for this supplier - display as bullet points on new lines
                $productNames = $supplier->products->pluck('product_name')->filter()->toArray();
                if (!empty($productNames)) {
                    $productsText = '• ' . implode("\n• ", $productNames);
                } else {
                    $productsText = '-';
                }
                $sheet->setCellValue('F' . $row, $productsText);
                
                // Set row height based on number of products (25 base + 15 per product)
                $productCount = count($productNames);
                $rowHeight = $productCount > 0 ? 25 + ($productCount * 15) : 25;
                $sheet->getRowDimension($row)->setRowHeight($rowHeight);

                $row++;
            }

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(40);

            // Format data rows
            $sheet->getStyle('A3:F' . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ]
            ]);

            // Output Excel file
            $writer = new Xlsx($spreadsheet);
            $filename = 'Daftar_Supplier_' . date('d_m_Y_His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating export: ' . $e->getMessage());
        }
    }
}

