<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Controller for handling supplier invoices.
 *
 * Users can upload and review invoices for a purchase order. Once
 * verified, invoices can be marked as paid. Uploads are stored in
 * the public disk.
 */
class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('purchaseOrder')->latest()->get();
        return view('invoice.index', compact('invoices'));
    }

    /**
     * Show the form for uploading an invoice for a purchase order.
     */
    public function create($purchaseOrderId)
    {
        $po = PurchaseOrder::findOrFail($purchaseOrderId);
        return view('invoice.form', compact('po'));
    }

    /**
     * Store a new invoice.
     */
    public function store(Request $request, $purchaseOrderId)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date'   => 'required|date',
            'due_date'       => 'nullable|date',
            'amount'         => 'required|numeric|min:0',
            'invoice_file'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payment_proof'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $po = PurchaseOrder::findOrFail($purchaseOrderId);
        DB::beginTransaction();
        try {
            // Store invoice file
            $invoiceFilePath = null;
            if ($request->hasFile('invoice_file')) {
                $invoiceFilePath = $request->file('invoice_file')->store('invoices', 'public');
            }

            // Store payment proof file
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            $invoice = Invoice::create([
                'purchase_order_id' => $po->id,
                'invoice_number'    => $request->invoice_number,
                'invoice_date'      => $request->invoice_date,
                'due_date'          => $request->due_date,
                'amount'            => $request->amount,
                'status'            => 'completed', // Langsung set status completed
                'invoice_file'      => $invoiceFilePath,
                'payment_proof'     => $paymentProofPath,
            ]);

            // Update PO status to completed
            $po->update(['status' => 'completed']);
            
            // Set flash message
            session()->flash('success', 'Invoice berhasil dibuat dan proses PO telah selesai');

            DB::commit();
            // Redirect back to new purchase orders after adding an invoice
            return redirect()->route('new-purchase-orders.index')->with('success', 'Invoice berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan invoice: ' . $e->getMessage()]);
        }
    }

    /**
     * Mark an invoice as paid.
     */
    public function markPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'paid']);
        // Optionally update PO status to closed/paid
        $invoice->purchaseOrder->update(['status' => 'paid']);
        return redirect()->route('procurement.index')->with('success', 'Invoice ditandai sebagai dibayar');
    }
}