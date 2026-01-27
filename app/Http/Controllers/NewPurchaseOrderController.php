<?php

namespace App\Http\Controllers;

use App\Models\{
    PurchaseOrder,
    PurchaseOrderItem,
    PurchaseRequest,
    Supplier,
    GoodsReceipt,
    Invoice
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Log as LogFacade;

class NewPurchaseOrderController extends Controller
{
    /**
     * Display a list of approved PRs ready for PO creation
     */
    public function index()
    {
        // Ambil PR yang sudah disetujui dan belum memiliki PO
        $approvedPRs = PurchaseRequest::with(['items', 'supplier', 'purchaseOrders'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil PO yang masih dalam proses (draft, sent, confirmed, received - belum ada invoice)
        $ongoingPOs = PurchaseOrder::with(['supplier', 'purchaseRequest', 'invoices'])
            ->whereIn('status', ['draft', 'sent', 'confirmed', 'received', 'validated'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil PO yang sudah selesai (completed dan memiliki invoice)
        $completedPOs = PurchaseOrder::with(['supplier', 'purchaseRequest', 'goodsReceipts', 'invoices'])
            ->where('status', 'completed')
            ->whereHas('invoices')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('new-purchase-order.index', compact('approvedPRs', 'ongoingPOs', 'completedPOs'));
    }

    /**
     * Get supplier's items as JSON for API
     */
    public function getItemsBySupplier(Supplier $supplier)
    {
        $items = $supplier->supplierProducts()
            ->with('item')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->item->id,
                    'name' => $product->item->name,
                    'code' => $product->item->code,
                    'unit' => $product->item->unit,
                    'price' => $product->price,
                    'min_order' => $product->min_order,
                    'lead_time' => $product->lead_time
                ];
            });

        return response()->json($items);
    }

    /**
     * Show form to create PO from approved PR
     */
    public function create($prId)
    {
        $pr = PurchaseRequest::with(['items', 'requester', 'supplier'])->findOrFail($prId);
        
        if (!$pr->canConvertToPO()) {
            return back()->withErrors(['error' => 'PR ini tidak dapat dikonversi ke PO']);
        }

        DB::beginTransaction();
        try {
            // Create and approve PO directly
            $po = PurchaseOrder::create([
                'purchase_request_id' => $pr->id,
                'supplier_id' => $pr->supplier_id,
                'po_number' => $this->generatePONumber(),
                'po_date' => now(),
                'contact_person' => $pr->supplier->contact_person ?? $pr->supplier->name,
                'contact_phone' => $pr->supplier->phone,
                'estimated_delivery_date' => now()->addDays(7),
                'status' => 'approved', // Set as approved immediately
                'created_by' => Auth::id(),
                'total_amount' => 0, // Will be updated after items are added
                'supplier_confirmed' => false,
                'invoice_image_path' => null // Will store PDF here
            ]);

            $totalAmount = 0;
            // Create PO items
            foreach ($pr->items as $item) {
                $amount = $item->quantity * ($item->unit_price ?? 0);
                $totalAmount += $amount;
                
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price ?? 0,
                    'unit' => $item->unit,
                    'notes' => $item->notes
                ]);
            }

            // Update total amount
            $po->update([
                'total_amount' => $totalAmount
            ]);

            // Update PR status to completed
            $pr->update([
                'status' => 'completed',
                'updated_at' => now()
            ]);

            // Generate PDF
            $pdf = PDF::loadView('new-purchase-order.pdf', compact('po'));
            
            // Store the PDF using the existing invoice_image_path field
            $fileName = 'PO-' . str_replace('/', '-', $po->po_number) . '.pdf';
            $path = 'purchase_orders/' . $fileName;
            Storage::put($path, $pdf->output());
            
            // Update PO with the PDF path
            $po->update([
                'invoice_image_path' => $path
            ]);

            DB::commit();

            // Redirect to PO list with success message
            return redirect()->route('new-purchase-order.index')
                ->with('success', 'Purchase Order telah dibuat dan disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat PO: ' . $e->getMessage()]);
        }
    }

    /**
     * Create PO from approved PR
     */
    public function store(Request $request)
    {
        $request->validate([
            'pr_id' => 'required|exists:purchase_requests,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'contact_person' => 'required|string',
            'contact_phone' => 'required|string',
            'estimated_delivery_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:purchase_request_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $pr = PurchaseRequest::with('items')->findOrFail($request->pr_id);
            
            if (!$pr->canConvertToPO()) {
                throw new \Exception('PR ini tidak dapat dikonversi ke PO');
            }

            // Create PO
            $po = PurchaseOrder::create([
                'purchase_request_id' => $pr->id,
                'supplier_id' => $request->supplier_id,
                'po_number' => $this->generatePONumber(),
                'po_date' => now(),
                'contact_person' => $request->contact_person,
                'contact_phone' => $request->contact_phone,
                'estimated_delivery_date' => $request->estimated_delivery_date,
                'notes' => $request->notes,
                'status' => 'draft',
                'created_by' => Auth::id(),
                'total_amount' => collect($request->items)->sum(fn($item) => $item['quantity'] * $item['unit_price'])
            ]);

            // Create PO items
            foreach ($request->items as $item) {
                $prItem = $pr->items()->findOrFail($item['id']);
                
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_name' => $item->product_name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'unit' => $prItem->unit,
                    'notes' => $item['notes'] ?? null,
                    'is_estimated_price' => $item['is_estimated_price'] ?? false,
                    'price_notes' => $item['price_notes'] ?? null,
                    'final_unit_price' => null
                ]);
            }

            // Update PR status
            $pr->update(['status' => 'po_created']);

            DB::commit();
            return redirect()->route('new-purchase-orders.show', $po->id)
                ->with('success', 'PO berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat PO: ' . $e->getMessage()]);
        }
    }

    /**
     * Display PO details and generate PDF
     */
    public function show($id)
    {
        $po = PurchaseOrder::with([
            'items', 
            'supplier', 
            'purchaseRequest',
            'creator',
            'goodsReceipts',
            'invoices'
        ])->findOrFail($id);
        return view('new-purchase-order.show', compact('po'));
    }

    /**
     * Generate and download PO PDF
     */
    public function generatePDF($id)
    {
        $po = PurchaseOrder::with([
            'items', 
            'supplier', 
            'purchaseRequest',
            'creator'
        ])->findOrFail($id);
        
        $pdf = PDF::loadView('new-purchase-order.pdf', compact('po'));
        return $pdf->download('PO-' . str_replace('/', '-', $po->po_number) . '.pdf');
    }

    /**
     * Mark PO as sent to supplier
     */
    public function markAsSent($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        // Pastikan PO dalam status draft dan harga sudah dikonfirmasi
        if ($po->status !== 'draft') {
            return back()->with('error', 'Hanya PO dalam status draft yang dapat dikirim ke supplier');
        }

        if (!$po->prices_confirmed) {
            return back()->with('error', 'Semua harga harus dikonfirmasi sebelum mengirim PO ke supplier');
        }

        try {
            $po->update([
                'status' => 'sent'
            ]);
            
            return back()->with('success', 'PO berhasil dikirim ke supplier');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim PO: ' . $e->getMessage());
        }
    }

    /**
     * Confirm PO with supplier
     */
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'confirmation_date' => 'required|date',
            'confirmed_delivery_date' => 'required|date|after:confirmation_date',
            'supplier_notes' => 'nullable|string'
        ]);

        $po = PurchaseOrder::findOrFail($id);
        $po->update([
            'status' => 'confirmed',
            'confirmation_date' => $request->confirmation_date,
            'confirmed_delivery_date' => $request->confirmed_delivery_date,
            'supplier_notes' => $request->supplier_notes
        ]);

        return back()->with('success', 'PO telah dikonfirmasi dengan supplier');
    }

    /**
     * Create Goods Receipt for confirmed PO
     */
    public function createGR(Request $request, $id)
    {
        $request->validate([
            'receipt_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.quantity_received' => 'required|integer|min:0',
            'items.*.notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::with('items')->findOrFail($id);
            
            if ($po->status !== 'confirmed') {
                throw new \Exception('PO harus dikonfirmasi terlebih dahulu');
            }

            // Create GR
            $gr = GoodsReceipt::create([
                'purchase_order_id' => $po->id,
                'gr_number' => 'GR-' . date('Ymd') . '-' . str_pad($po->id, 4, '0', STR_PAD_LEFT),
                'receipt_date' => $request->receipt_date,
                'received_by' => Auth::id() ?? 1,
                'notes' => $request->notes
            ]);

            // Create GR items and update inventory
            foreach ($request->items as $itemId => $itemData) {
                $poItem = $po->items()->findOrFail($itemId);
                
                $gr->items()->create([
                    'product_name' => $poItem->product_name,
                    'quantity_received' => $itemData['quantity_received'],
                    'unit' => $poItem->unit,
                    'notes' => $itemData['notes'] ?? null
                ]);

                // Update inventory if needed
                // ... add your inventory update logic here
            }

            // Update PO status
            $po->update(['status' => 'received']);

            DB::commit();
            return back()->with('success', 'Goods Receipt berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat GR: ' . $e->getMessage()]);
        }
    }

    /**
     * Create PO directly from approved PR and generate document
     */
    public function createDirectPO(PurchaseRequest $purchaseRequest)
    {
        // 1. Basic validation
        if ($purchaseRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'PR harus dalam status approved');
        }

        if ($purchaseRequest->purchaseOrders()->exists()) {
            return redirect()->back()->with('error', 'PR ini sudah memiliki PO');
        }

        // 2. Load necessary relations
        $pr = $purchaseRequest->load(['items', 'supplier']);
        
        if (!$pr->supplier) {
            return redirect()->back()->with('error', 'PR harus memiliki supplier');
        }

        DB::beginTransaction();
        try {
            // 3. Create PO
            $po = PurchaseOrder::create([
                'purchase_request_id' => $pr->id,
                'supplier_id' => $pr->supplier_id,
                'po_number' => $this->generatePONumber(),
                'po_date' => now(),
                'status' => 'draft', // Set as draft initially
                'created_by' => Auth::id()
            ]);
            
            // 4. Create PO items with estimated prices
            $totalAmount = 0;
            foreach ($pr->items as $item) {
                $amount = $item->quantity * ($item->unit_price ?? 0);
                $totalAmount += $amount;
                
                $po->items()->create([
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price ?? 0,
                    'unit' => $item->unit,
                    'is_estimated_price' => true, // Mark price as estimated
                    'price_notes' => 'Harga awal dari PR'
                ]);
            }

            // 5. Update PO total and PR status
            $po->total_amount = $totalAmount;
            $po->prices_confirmed = false;
            $po->save();
            
            // Update both status and approval_status
            $pr->update([
                'status' => 'po_created',
                'approval_status' => 'approved'  // Ensure approval status matches actual state
            ]);

            DB::commit();
            
            return redirect()->route('new-purchase-orders.show', $po->id)
                ->with('success', 'Purchase Order berhasil dibuat. Silakan update harga jika diperlukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat PO: ' . $e->getMessage());
        }
    }

    /**
     * Create Invoice for completed PO
     */
    public function createInvoice(Request $request, $id)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'amount' => 'required|numeric|min:0',
            'invoice_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'payment_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::findOrFail($id);
            
            if ($po->status !== 'received') {
                throw new \Exception('Barang harus diterima terlebih dahulu');
            }

            // Store invoice file
            $invoicePath = $request->file('invoice_file')->store('invoices', 'public');

            // Store payment proof
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            // Create invoice
            Invoice::create([
                'purchase_order_id' => $po->id,
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'amount' => $request->amount,
                'invoice_file' => $invoicePath,
                'payment_proof' => $paymentProofPath,
                // set to 'paid' to match views that expect 'paid' / 'pending'
                'status' => 'paid'
            ]);

            // Update PO status to completed karena sudah ada bukti pembayaran
            $po->update(['status' => 'completed']);

            DB::commit();
            return back()->with('success', 'Invoice berhasil dibuat dan PO telah selesai');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging and return a user-visible session error
            LogFacade::error('Failed to create invoice for PO ' . $id . ': ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    private function generatePONumber(): string
    {
        $latestPO = PurchaseOrder::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->orderBy('id', 'desc')
            ->first();

        $count = $latestPO ? intval(substr($latestPO->po_number, -4)) + 1 : 1;
            
        return sprintf("PO/%s/%s/%04d", 
            now()->format('Y'),
            now()->format('m'),
            $count
        );
    }

    /**
     * Show form to update PO prices
     */
    public function editPrices($id)
    {
        $po = PurchaseOrder::with(['items', 'supplier'])->findOrFail($id);
        
        if ($po->status !== 'draft') {
            return redirect()->route('new-purchase-orders.show', $po->id)
                           ->with('error', 'Hanya PO draft yang dapat diupdate harganya');
        }

        return view('new-purchase-order.update-prices', compact('po'));
    }

    /**
     * Update PO prices
     */
    public function updatePrices(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:purchase_order_items,id',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::with('items')->findOrFail($id);
            
            if ($po->status !== 'draft') {
                return redirect()->route('new-purchase-orders.show', $po->id)
                               ->with('error', 'Hanya PO draft yang dapat diupdate harganya');
            }

            $totalAmount = 0;
            $action = $request->input('action', 'update');
            $confirmAll = ($action === 'confirm');
            
            // Update semua item
            foreach ($request->items as $item) {
                $poItem = $po->items->find($item['id']);
                if ($poItem) {
                    // Hanya update jika harga diisi dan valid
                    if (isset($item['unit_price'])) {
                        // Set harga ke 0 jika negatif atau null
                        $price = ($item['unit_price'] > 0) ? $item['unit_price'] : 0;
                        
                        $poItem->update([
                            'unit_price' => $price,
                            'is_estimated_price' => false,
                            'price_notes' => null,
                            'final_unit_price' => $price > 0 ? $price : null
                        ]);

                        if ($price > 0) {
                            $totalAmount += $poItem->quantity * $price;
                        }
                    }
                }
            }

            // Cek apakah semua item memiliki harga valid
            $allPricesValid = true;
            foreach ($po->items as $item) {
                if (!$item->unit_price || $item->unit_price <= 0) {
                    $allPricesValid = false;
                    break;
                }
            }

            // Hanya set prices_confirmed jika konfirmasi diminta dan semua harga valid
            $po->update([
                'total_amount' => $totalAmount,
                'prices_confirmed' => $confirmAll && $allPricesValid
            ]);

            if ($confirmAll && !$allPricesValid) {
                return back()->withErrors(['error' => 'Tidak dapat mengkonfirmasi. Pastikan semua item memiliki harga yang valid.']);
            }

            DB::commit();

            $message = $confirmAll || $allPricesSet ? 
                'Harga berhasil dikonfirmasi' : 
                'Harga berhasil diupdate';

            return redirect()
                ->route('new-purchase-orders.show', $po->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengupdate harga: ' . $e->getMessage()]);
        }
    }

    /**
     * Confirm final prices for PO
     */
    public function confirmPrices(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:purchase_order_items,id',
            'items.*.final_unit_price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::with('items')->findOrFail($id);
            
            if ($po->status !== 'draft') {
                return redirect()->route('new-purchase-orders.show', $po->id)
                               ->with('error', 'Hanya PO draft yang dapat dikonfirmasi harganya');
            }

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $poItem = $po->items->find($item['id']);
                if ($poItem) {
                    $poItem->update([
                        'final_unit_price' => $item['final_unit_price'],
                        'is_estimated_price' => false,
                        'price_notes' => null
                    ]);

                    $totalAmount += $poItem->quantity * $item['final_unit_price'];
                }
            }

            $po->update([
                'total_amount' => $totalAmount,
                'prices_confirmed' => true
            ]);

            DB::commit();
            return redirect()->route('new-purchase-orders.show', $po->id)
                           ->with('success', 'Harga PO berhasil dikonfirmasi');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengkonfirmasi harga: ' . $e->getMessage()]);
        }
    }

    /**
     * Download invoice file
     */
    public function downloadInvoiceFile($invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);
            
            if (!$invoice->invoice_file || !Storage::disk('public')->exists($invoice->invoice_file)) {
                return back()->with('error', 'File invoice tidak ditemukan');
            }

            $filePath = Storage::disk('public')->path($invoice->invoice_file);
            return response()->download($filePath);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal download file: ' . $e->getMessage());
        }
    }

    /**
     * Download payment proof file
     */
    public function downloadPaymentProof($invoiceId)
    {
        try {
            $invoice = Invoice::findOrFail($invoiceId);
            
            if (!$invoice->payment_proof || !Storage::disk('public')->exists($invoice->payment_proof)) {
                return back()->with('error', 'File bukti pembayaran tidak ditemukan');
            }

            $filePath = Storage::disk('public')->path($invoice->payment_proof);
            return response()->download($filePath);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal download file: ' . $e->getMessage());
        }
    }
}