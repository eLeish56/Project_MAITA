<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\WholesalePrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ItemController extends Controller
{
  /**
   * @param Request $request
   * @param Item $item
   * @return void
   */
  private static function addWholeSalePrice(Request $request, Item $item): void
  {
    if ($request->wholesale_price) {
      $wholesale_price = [];
      foreach ($request->wholesale_price as $key => $price) {
        $wholesale_price[] = [
          'price' => $price,
          'min_qty' => $request->min_qty[$key]
        ];
      }

      foreach ($wholesale_price as $price) {
        WholesalePrice::create([
          'item_id' => $item->id,
          'min_qty' => $price['min_qty'],
          'price' => $price['price']
        ]);
      }
    }
  }

  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('inventory.item.index', [
      'user' => auth()->user(),
      'items' => Item::with('category')->orderBy('name')->get()->load('wholesalePrices'),
      'type' => 'show'
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show(Item $item): string
  {
    return json_encode($item);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    return view('inventory.item.form', [
      'categories' => Category::orderBy('name')->get(),
      'type' => 'create'
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:items,name',
      'code' => 'required|string|max:255|unique:items,code',
      'category' => 'required|exists:categories,id',
      'cost_price' => 'required|numeric|min:1',
      'selling_price' => 'required|numeric|min:1|gte:cost_price',
      'whole_sale_price' => 'array',
      'min_qty' => 'array',
      'stock' => 'required|numeric|min:0',
      'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
    ]);

    if ($request->hasFile('picture')) {
      $file = $request->file('picture');
      $filename = time() . '.' . $file->getClientOriginalExtension();
      
      // Simpan file ke storage/app/public/items
      $path = $file->storeAs('items', $filename, 'public');
      
      // Pastikan hanya nama file yang disimpan ke database
      $picture_name = $filename;
    } else {
      $picture_name = 'default.png';
    }

    $item = Item::create([
      'name' => $request->name,
      'code' => $request->code,
      'category_id' => $request->category,
      'cost_price' => $request->cost_price,
      'selling_price' => $request->selling_price,
      'stock' => $request->stock,
      'picture' => $picture_name
    ]);

    self::addWholeSalePrice($request, $item);

    return redirect()->route('item.index')->with('status', 'Barang  berhasil ditambahkan');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Item $item): View
  {
    return view('inventory.item.form', [
      'item' => $item,
      'categories' => Category::all(),
      'type' => 'edit'
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Item $item): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:items,name,' . $item->id,
      'code' => 'required|string|max:255|unique:items,code,' . $item->id,
      'category' => 'required|exists:categories,id',
      'cost_price' => 'required|numeric|min:1',
      'selling_price' => 'required|numeric|min:1|gte:cost_price',
      'whole_sale_price' => 'array',
      'min_qty' => 'array',
      'stock' => 'required|numeric|min:0',
      'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
    ]);

    if ($request->hasFile('picture')) {
      // Hapus file lama jika bukan default
      if ($item->picture && $item->picture !== 'default.png') {
        Storage::disk('public')->delete('items/' . $item->picture);
      }

      $file = $request->file('picture');
      $filename = time() . '.' . $file->getClientOriginalExtension();
      
      // Simpan file baru
      $path = $file->storeAs('items', $filename, 'public');
      $picture_name = $filename;
    } else {
      $picture_name = $item->picture;
    }

    $item->update([
      'name' => $request->name,
      'code' => $request->code,
      'category_id' => $request->category,
      'cost_price' => $request->cost_price,
      'selling_price' => $request->selling_price,
      'stock' => $request->stock,
      'picture' => $picture_name
    ]);

    $item->wholesalePrices()->delete();

    self::addWholeSalePrice($request, $item);

    return redirect()->route('item.index')->with('status', 'Barang berhasil diubah');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Item $item): RedirectResponse
  {
    // Hapus gambar dari storage jika bukan default
    if ($item->picture && $item->picture !== 'default.png') {
        Storage::disk('public')->delete('items/' . $item->picture);
    }

    // Hapus item dari database
    $item->delete();

    return redirect()->route('item.index')->with('status', 'Barang berhasil dihapus');
  }

  /**
   * Export items to Excel
   */
  public function export(Request $request)
  {
    try {
      $type = $request->query('type', 'xlsx');
      
      // Get all items with category
      $items = Item::with('category')->orderBy('name')->get();

      // Create Excel
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('Daftar Barang');

      // Document properties
      $spreadsheet->getProperties()
        ->setCreator('Teaching Factory')
        ->setLastModifiedBy('Teaching Factory')
        ->setTitle('Daftar Barang Teaching Factory')
        ->setSubject('Daftar Barang');

      // Header
      $sheet->setCellValue('A1', 'DAFTAR BARANG TEACHING FACTORY');
      $sheet->mergeCells('A1:G1');
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
      $headers = ['No', 'Nama Barang', 'Kode', 'Kategori', 'Harga Beli', 'Harga Jual', 'Stok'];
      $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
      
      foreach ($columns as $index => $column) {
        $sheet->setCellValue($column . '3', $headers[$index]);
      }

      // Style headers
      $sheet->getStyle('A3:G3')->applyFromArray([
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
      foreach ($items as $index => $item) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $item->name);
        $sheet->setCellValue('C' . $row, $item->code);
        $sheet->setCellValue('D' . $row, $item->category->name ?? '-');
        $sheet->setCellValue('E' . $row, $item->cost_price);
        $sheet->setCellValue('F' . $row, $item->selling_price);
        $sheet->setCellValue('G' . $row, $item->stock);

        $row++;
      }

      // Set column widths
      $sheet->getColumnDimension('A')->setWidth(5);
      $sheet->getColumnDimension('B')->setWidth(25);
      $sheet->getColumnDimension('C')->setWidth(12);
      $sheet->getColumnDimension('D')->setWidth(15);
      $sheet->getColumnDimension('E')->setWidth(12);
      $sheet->getColumnDimension('F')->setWidth(12);
      $sheet->getColumnDimension('G')->setWidth(10);

      // Format currency columns
      $sheet->getStyle('E4:F' . ($row - 1))->getNumberFormat()->setFormatCode('Rp #,##0.00');

      // Format data rows
      $sheet->getStyle('A3:G' . ($row - 1))->applyFromArray([
        'borders' => [
          'allBorders' => [
            'borderStyle' => 'thin',
          ]
        ],
        'alignment' => [
          'vertical' => Alignment::VERTICAL_CENTER,
        ]
      ]);

      // Output Excel file
      $writer = new Xlsx($spreadsheet);
      $filename = 'Daftar_Barang_' . date('d_m_Y_His') . '.xlsx';
      
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
