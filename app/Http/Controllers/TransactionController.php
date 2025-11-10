<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(): View
    {
        $user = User::find(Auth::user()->id);

        // Ambil daftar pesanan online (status debt oleh customer)
        $orders = Transaction::with('user')
            ->where('status', 'debt')
            ->whereHas('user', function($q) {
                $q->where('role', 'customer');
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Get users with role customer
        $customers = User::where('role', 'customer')
                        ->orderBy('name')
                        ->get();

        return view('transaction.index', [
            'user' => $user,
            'customers' => $customers,
            'items' => Item::orderBy('name')->get(),
            'payment_methods' => PaymentMethod::orderBy('name')->get(),
            'carts' => $user->carts,
            'orders' => $orders,
        ]);
    }

    private function move_cart($transaction)
    {
        try {
            $carts = Cart::where('user_id', Auth::user()->id)->get();

            foreach ($carts as $cart) {
                $item = Item::find($cart->item_id);
                if (!$item) {
                    throw new \Exception("Item tidak ditemukan");
                }

                if ($item->stock < $cart->qty) {
                    throw new \Exception("Stok {$item->name} tidak mencukupi");
                }

                $item->stock -= $cart->qty;
                $item->save();

                $transaction_detail = new TransactionDetail();
                $transaction_detail->transaction_id = $transaction->id;
                $transaction_detail->item_id = $cart->item_id;
                $transaction_detail->item_price = calculate_price($cart->item, $cart->qty);
                $transaction_detail->qty = $cart->qty;
                $transaction_detail->total = $cart->subtotal;
                $transaction_detail->save();
            }
        } catch (\Exception $e) {
            // Rollback manual jika terjadi error
            if (isset($carts)) {
                foreach ($carts as $cart) {
                    $item = Item::find($cart->item_id);
                    if ($item) {
                        $item->stock += $cart->qty;
                        $item->save();
                    }
                }
            }
            throw $e;
        }
    }

    private function finalizePaymentCommon(
        Transaction $transaction,
        string $paymentMethodName,
        int $amount,
        int $change,
        ?string $note = null
    ): void {
        $payment = PaymentMethod::where('name', $paymentMethodName)->first();
        if (!$payment) {
            throw new \Exception('Metode pembayaran tidak ditemukan.');
        }

        $total = (int) $transaction->total;
        $isCash = strtolower($paymentMethodName) === 'tunai';

        $transaction->payment_method_id = $payment->id;
        $transaction->status = 'paid';
        $transaction->amount = $isCash ? $amount : $total;
        $transaction->change = $isCash ? max(0, $change) : 0;

        $kasir = optional(Auth::user())->name;
        $stamp = now()->format('d/m/Y H:i');
        $append = trim(($note ?: '') . " (diproses: {$kasir}, {$stamp})");
        $transaction->note = trim(implode(' | ', array_filter([$transaction->note, $append])));

        $transaction->save();
    }

    public function store(Request $request): string
    {
        try {
            return DB::transaction(function() use ($request) {
                $request->validate([
                    'invoice' => 'required|string',
                    'invoice_no' => 'required|numeric',
                    'total' => 'required|numeric|min:0',
                    'discount' => 'nullable|numeric|min:0',
                    'payment_method' => 'required|string',
                    'amount' => 'nullable|numeric|min:0',
                    'change' => 'nullable|numeric|min:0',
                    'note' => 'nullable|string|max:255',
                    'customer_id' => 'nullable|numeric'
                ]);

                $transaction = new Transaction();
                $transaction->user_id = Auth::user()->id;
                // Set customer name in note if provided
            if ($request->customer_name) {
                $note = $request->note ? $request->note . " | " : "";
                $transaction->note = $note . "Pelanggan: " . $request->customer_name;
            }
                $transaction->invoice = $request->invoice;
                $transaction->invoice_no = $request->invoice_no;
                $transaction->total = (int) $request->total;
                $transaction->discount = (int) ($request->discount ?? 0);
                $transaction->note = $request->note ?? null;
                $transaction->save();

                // Validasi pembayaran dulu
                $method = $request->payment_method;
                $total = (int) $transaction->total;
                $isCash = strtolower($method) === 'tunai';
                $amount = (int) ($request->amount ?? 0);
                $change = (int) ($request->change ?? 0);

                if ($isCash && $amount < $total) {
                    throw new \Exception('Jumlah uang tunai kurang dari total!');
                }

                // Proses cart dan kurangi stok
                $this->move_cart($transaction);

                // Finalisasi pembayaran
                $this->finalizePaymentCommon($transaction, $method, $amount, $change, $request->note);

                // Kosongkan cart
                Cart::where('user_id', Auth::user()->id)->delete();

                return json_encode(['status' => 'success', 'message' => 'Transaksi berhasil']);
            });
        } catch (\Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /** Hapus transaksi (dipakai di laporan) */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();
        return redirect()->route('report.transaction.index')
            ->with('status', 'Berhasil menghapus data penjualan');
    }

    /**
     * Simpan transaksi sebagai HUTANG (debt) — ini dipakai juga untuk "pesanan online menunggu diambil"
     * Tidak set payment_method/amount/change di sini.
     */
    public function save_transaction(Request $request): string
    {
        $request->validate([
            'invoice'     => 'required|string',
            'invoice_no'  => 'required|numeric',
            'total'       => 'required|numeric|min:0',
            'customer_id' => 'nullable|numeric'
        ]);

        $transaction = new Transaction();
        // By default set the transaction owner to the current user (POS cashier).
        // If a customer_id is provided (from the customer dropdown which lists users with role='customer'),
        // use that user as the transaction owner (online flow).
        $transaction->user_id = Auth::user()->id; // default: cashier
        if ($request->customer_id && (int)$request->customer_id !== 0) {
            $transaction->user_id = (int) $request->customer_id;
        }
        $transaction->invoice    = $request->invoice;
        $transaction->invoice_no = $request->invoice_no;
        $transaction->total      = (int) $request->total;
        $transaction->status     = 'debt'; // menunggu diambil / belum dibayar
        $transaction->save();

        $this->move_cart($transaction);

        return json_encode(['status' => 'success', 'message' => 'Transaksi berhasil']);
    }

    /** Ambil daftar item (POS) */
    public function get_items(Request $request): string|View
    {
        $items = Item::orderBy('name')->get();

        if ($request->json) {
            return json_encode($items);
        }

        return view('transaction.items', [
            'items' => $items
        ]);
    }

    /** Generate invoice & invoice_no harian */
    public function get_invoice(): string
    {
        if (Transaction::whereDate('created_at', Carbon::today())->exists()) {
            $invoice = intval(Transaction::whereDate('created_at', Carbon::today())->max('invoice_no')) + 1;
        } else {
            $invoice = 1;
        }

        $invoice_no = $invoice;
        $invoice    = env('INVOICE_PREFIX') . date('dmy') . str_pad($invoice, 4, "0", STR_PAD_LEFT);

        return json_encode(['invoice' => $invoice, 'invoice_no' => $invoice_no]);
    }

    /**
     * Halaman daftar pesanan online (status 'debt' & dibuat oleh customer)
     * HANYA daftar, tidak menampilkan item/cart — proses melalui modal kecil.
     */
    public function onlineOrders()
    {
        // Ambil pesanan marketplace yang belum diambil, gabung dengan tabel users untuk nama customer
        $orders = DB::table('marketplace_orders as mo')
            ->join('users as u', 'mo.user_id', '=', 'u.id')
            ->select(
                'mo.id',
                'mo.code',
                'mo.pickup_name',
                'mo.phone',
                'mo.total_price',
                'mo.created_at',
                'mo.status',
                'u.name as customer_name'
            )
            ->where('mo.status', 'pending_pickup')
            ->orderBy('mo.created_at', 'asc')
            ->get();

        $payment_methods = PaymentMethod::orderBy('name')->get();

        return view('transaction.online', [
            'orders'          => $orders,
            'payment_methods' => $payment_methods,
        ]);
    }

    /**
     * Proses pembayaran pesanan marketplace (online) di kasir.
     * Menerima ID marketplace_order sebagai parameter.
     */
    private function ensureCustomerExists($userId)
    {
        // Ambil data user dari marketplace
        $user = DB::table('users')->find($userId);
        if (!$user) {
            return null;
        }

        // We no longer create or read from the legacy `customers` table.
        // Marketplace orders already reference a user in `users` (user_id).
        // Return the marketplace user's id so callers can use the `users` table
        // (role 'customer') as the canonical customer record.
        return $user->id;
    }

    public function processOnline(Request $request, $orderId)
    {
        $order = DB::table('marketplace_orders')->where('id', $orderId)->first();
        if (!$order || $order->status !== 'pending_pickup') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan tidak valid atau sudah diproses.'
            ], 422);
        }

        // Pastikan customer ada di tabel customers
        $customerId = $this->ensureCustomerExists($order->user_id);
        if (!$customerId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data pelanggan tidak valid.'
            ], 422);
        }

        // Gunakan metode pembayaran default (Tunai)
        $payment = PaymentMethod::whereRaw('LOWER(name) = ?', ['tunai'])->first()
                  ?? PaymentMethod::first();

        DB::transaction(function () use ($order, $payment, $customerId) {
            $total = (int) $order->total_price;
            $orderLocked = DB::table('marketplace_orders')
                ->where('id', $order->id)
                ->lockForUpdate()
                ->first();

            if (!$orderLocked || $orderLocked->status !== 'pending_pickup') {
                abort(400, 'Order tidak dalam status pending_pickup.');
            }

            $items = DB::table('marketplace_order_items')
                ->where('order_id', $orderLocked->id)
                ->get();

            $today   = now()->format('dmy');
            $last    = DB::table('transactions')
                ->whereDate('created_at', now()->toDateString())
                ->orderByDesc('id')
                ->first();
            $seq     = $last ? ((int) $last->invoice_no + 1) : 1;
            $invoice = $today . str_pad($seq, 4, '0', STR_PAD_LEFT);

            $trxId = DB::table('transactions')->insertGetId([
                // Store the marketplace customer as the transaction user
                // (we no longer use the legacy `customers` table)
                'user_id'           => $customerId,
                'channel'           => 'online',
                'payment_status'    => 'paid',
                'pickup_status'     => 'picked_up',
                'pickup_code'       => $orderLocked->code,
                //'customer_id'       => $customerId, // legacy column not used here
                'invoice'           => $invoice,
                'invoice_no'        => (string) $seq,
                'total'             => (int) $orderLocked->total_price,
                'discount'          => 0,
                'payment_method_id' => $payment->id,
                'amount'            => $total,
                'change'            => 0,
                'status'            => 'paid',
                'note'              => 'Marketplace pickup: ' . $orderLocked->pickup_name . ' (' . $orderLocked->phone . ')',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            foreach ($items as $i) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $trxId,
                    'item_id'        => $i->item_id,
                    'qty'            => (int) $i->qty,
                    'item_price'     => (int) $i->price,
                    'total'          => (int) $i->price * (int) $i->qty,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            DB::table('marketplace_orders')
                ->where('id', $orderLocked->id)
                ->update([
                    'status'     => 'completed',
                    'updated_at' => now(),
                ]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Transaksi online berhasil diproses.'
        ]);
    }

    public function marketplaceOnlineOrders()
    {
        $orders = DB::table('marketplace_orders as mo')
            ->join('users as u', 'mo.user_id', '=', 'u.id')
            ->select(
                'mo.id',
                'mo.code',
                'mo.pickup_name',
                'mo.phone',
                'mo.total_price',
                'mo.created_at',
                'mo.status',
                'u.name as customer_name'
            )
            ->whereIn('mo.status', ['pending_pickup','processing','completed'])
            ->orderBy('mo.created_at', 'asc')
            ->get();

    $payment_methods = PaymentMethod::whereRaw('LOWER(name) = ?', ['tunai'])
    ->orderBy('name')
    ->get();

    return view('transaction.marketplace-online', [
        'orders'          => $orders,
        'payment_methods' => $payment_methods,
    ]);
    }

    /**
     * Kembalikan detail item untuk pesanan marketplace sebagai tampilan HTML.
     * Rute ini dipanggil melalui AJAX ketika pengguna klik tombol "Detail".
     */
    public function marketplaceOrderItems($orderId)
    {
        $order = DB::table('marketplace_orders')->find($orderId);
        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan');
        }

        $items = DB::table('marketplace_order_items as moi')
            ->join('items as i', 'moi.item_id', '=', 'i.id')
            ->select(
                'i.name',
                'moi.qty',
                'moi.price',
                DB::raw('moi.price * moi.qty as subtotal')
            )
            ->where('moi.order_id', $orderId)
            ->get();

        // menampilkan detail item dalam view blade sederhana
        return view('transaction.marketplace-order-items', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    /**
     * Proses pesanan marketplace: verifikasi metode pembayaran, hitung jumlah uang & kembalian,
     * simpan transaksi, lalu ubah status pesanan menjadi completed.
     */
    // app/Http/Controllers/TransactionController.php

    public function onlineOrderItems($orderId)
{
    $order = DB::table('marketplace_orders')
        ->select(
            'id',
            'code',
            'pickup_name',
            'phone',
            'total_price as total',
            'status',
            'created_at',
            'notes'
        )
        ->where('id', $orderId)
        ->first();
        
    if (!$order) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Pesanan tidak ditemukan.'
        ], 404);
    }

    $items = DB::table('marketplace_order_items as moi')
        ->join('items as i', 'moi.item_id', '=', 'i.id')
        ->select(
            'i.name',
            'moi.qty',
            'moi.price',
            DB::raw('moi.qty * moi.price as subtotal')
        )
        ->where('moi.order_id', $orderId)
        ->get();    return view('transaction.online-order-items', [
        'order' => $order,
        'items' => $items
    ]);
}

public function processMarketplaceOrder($orderId)
{
    $order = DB::table('marketplace_orders')->where('id', $orderId)->first();
    if (!$order || $order->status !== 'pending_pickup') {
        return response()->json([
            'status'  => 'error',
            'message' => 'Pesanan tidak valid atau sudah diproses.'
        ], 422);
    }

    DB::transaction(function () use ($order) {
        // Gunakan metode pembayaran default, misalnya Tunai
        $payment = PaymentMethod::whereRaw('LOWER(name) = ?', ['tunai'])->first()
                  ?? PaymentMethod::first();

        // Generate invoice baru
        $today   = now()->format('dmy');
        $last    = DB::table('transactions')
            ->whereDate('created_at', now()->toDateString())
            ->orderByDesc('id')
            ->first();
        $seq     = $last ? ((int) $last->invoice_no + 1) : 1;
        $invoice = $today . str_pad($seq, 4, '0', STR_PAD_LEFT);

        // ... kode lain ...

        $trxId = DB::table('transactions')->insertGetId([
            'user_id'           => Auth::id(),
            'channel'           => 'online',
            'payment_status'    => 'paid',
            'pickup_status'     => 'picked_up',
            'pickup_code'       => $order->code,
            'customer_id'       => null, // jangan mengisi id user di sini karena tidak ada di tabel customers
            'invoice'           => $invoice,
            'invoice_no'        => (string) $seq,
            'total'             => (int) $order->total_price,
            'discount'          => 0,
            'payment_method_id' => $payment->id,
            'amount'            => (int) $order->total_price,
            'change'            => 0,
            'status'            => 'paid',
            'note'              => 'Marketplace pickup: ' . $order->pickup_name . ' (' . $order->phone . ')',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);


        // Simpan detail transaksi
        $items = DB::table('marketplace_order_items')
            ->where('order_id', $order->id)
            ->get();

        foreach ($items as $i) {
            DB::table('transaction_details')->insert([
                'transaction_id' => $trxId,
                'item_id'        => $i->item_id,
                'qty'            => (int) $i->qty,
                'item_price'     => (int) $i->price,
                'total'          => (int) $i->price * (int) $i->qty,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // Update status pesanan menjadi completed
        DB::table('marketplace_orders')->where('id', $order->id)->update([
            'status'     => 'completed',
            'updated_at' => now(),
        ]);
    });

    return response()->json([
        'status'  => 'success',
        'message' => 'Pesanan berhasil diselesaikan.'
    ]);
}


}
