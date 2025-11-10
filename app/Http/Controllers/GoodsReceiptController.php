<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\InventoryRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Controller for handling goods receipts (GRNs).
 *
 * Warehouse/admin users use this controller to record the receipt of
 * goods against a purchase order. The GRN can later be reviewed by
 * supervisors before final approval.
 */
class GoodsReceiptController extends Controller
{
    /**
     * List all goods receipts.
     */
    public function index()
    {
        $receipts = GoodsReceipt::with('purchaseOrder')->latest()->get();
        return view('goods-receipt.index', compact('receipts'));
    }

    /**
     * Show the form for creating a new goods receipt for a specific PO.
     */
    public function create($purchaseOrderId)
    {
        $po = PurchaseOrder::with(['items.item'])->findOrFail($purchaseOrderId);
        return view('goods-receipt.form', compact('po'));
    }

    /**
     * Store a new goods receipt.
     */
    public function createGR(Request $request, PurchaseOrder $purchaseOrder)
    {
        Log::info('GR Creation Started', ['request' => $request->except(['_token'])]);
        
        // 1. Validate Request
        $validated = $request->validate([
            'receipt_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.quantity_received' => 'required|integer|min:1',
            'items.*.expiry_date' => 'nullable|date',
            'items.*.batch_number' => 'nullable|string|max:50',
            'items.*.product_name' => 'required|string',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'receipt_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            Log::info('Starting DB transaction');
            
            // 1. Validate PO status
            if ($purchaseOrder->status !== 'sent') {
                throw new \Exception('PO harus dalam status terkirim untuk membuat GR');
            }

            // 2. Handle file upload
            $receiptPath = null;
            if ($request->hasFile('receipt_document')) {
                $receiptPath = $request->file('receipt_document')->store('receipts', 'public');
                Log::info('Document uploaded', ['path' => $receiptPath]);
            }

            // 3. Create GR record
            $grNumber = $this->generateGRNumber();
            Log::info('Creating GR record', [
                'po_id' => $purchaseOrder->id,
                'gr_number' => $grNumber,
                'receipt_date' => $request->receipt_date
            ]);
            
            $gr = new GoodsReceipt();
            $gr->purchase_order_id = $purchaseOrder->id;
            $gr->gr_number = $grNumber;
            $gr->receipt_date = $request->receipt_date;
            $gr->received_by = Auth::id();
            $gr->status = 'completed';
            $gr->notes = $request->notes;
            $gr->receipt_document = $receiptPath;
            $gr->save();

            $receiveService = app(\App\Services\Inventory\ReceiveGoodsService::class);

            // 4. Create GR Items using service
            foreach ($request->items as $index => $item) {
                Log::info('Processing item', ['index' => $index, 'item' => $item]);
                
                try {
                    // Get the item model
                    Log::info('Finding item with ID', ['item_id' => $item['item_id']]);
                    
                    // Create GR Item
                    $grItem = new GoodsReceiptItem();
                    $grItem->goods_receipt_id = $gr->id;
                    
                    // Link to master item if available
                    if (!empty($item['item_id'])) {
                        $itemModel = \App\Models\Item::find($item['item_id']);
                        if ($itemModel) {
                            $grItem->item_id = $itemModel->id;
                            Log::info('Found and linked master item', ['item' => $itemModel->toArray()]);
                        }
                    }
                    
                    $grItem->product_name = $item['product_name'];
                    $grItem->quantity_received = $item['quantity_received'];
                    $grItem->remaining_quantity = $item['quantity_received'];
                    
                    // Handle optional fields
                    $grItem->expiry_date = !empty($item['expiry_date']) ? $item['expiry_date'] : null;
                    $grItem->batch_number = $item['batch_number'] ?? null;
                    $grItem->unit = $item['unit'] ?? null;
                    $grItem->notes = $item['notes'] ?? null;
                    
                    // Use service to save and process the item
                    $receiveService->processGRItem($grItem);
                    
                    Log::info('GR Item created', ['gr_item_id' => $grItem->id]);

                    // Pencatatan inventory sudah ditangani oleh processGRItem
                } catch (\Exception $e) {
                    Log::error('Failed to process item', [
                        'index' => $index,
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }

            Log::info('Updating PO status');
            $purchaseOrder->status = 'received';
            $purchaseOrder->save();
            
            Log::info('All operations completed, committing transaction');
            DB::commit();
            Log::info('Transaction committed successfully');
            
            return redirect()
                ->back()
                ->with('success', 'Penerimaan barang berhasil disimpan dengan nomor GR: ' . $gr->gr_number);
                
        } catch (\Exception $e) {
            Log::error('Failed to create GR, rolling back transaction', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            DB::rollBack();
            Log::info('Transaction rolled back');
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan penerimaan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show a single goods receipt.
     */
    public function show($id)
    {
        $receipt = GoodsReceipt::with('items', 'purchaseOrder')->findOrFail($id);
        return view('goods-receipt.show', compact('receipt'));
    }

    /**
     * Helper: generate sequential GR numbers (format: GR-0001/MM/YYYY).
     */
    private function generateGRNumber(): string
    {
        $count = GoodsReceipt::count() + 1;
        $month = date('m');
        $year  = date('Y');
        return sprintf("GR-%04d/%s/%s", $count, $month, $year);
    }

    /**
     * Download receipt document
     */
    public function downloadDocument(GoodsReceipt $goodsReceipt, $type = 'receipt')
    {
        $field = match($type) {
            'receipt' => 'receipt_document',
            'do' => 'delivery_order_file',
            'bbm' => 'bbm_file',
            default => throw new \InvalidArgumentException('Invalid document type')
        };

        if (!$goodsReceipt->$field || !Storage::disk('public')->exists($goodsReceipt->$field)) {
            return back()->with('error', 'Dokumen tidak ditemukan');
        }

        $path = Storage::disk('public')->path($goodsReceipt->$field);
        return response()->download($path);
    }
}