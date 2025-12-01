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
use Barryvdh\DomPDF\Facade\Pdf;

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
        // PINDAHKAN ITEM DARI CART KE TRANSACTION DETAIL
        // Dan kurangi stok item untuk setiap transaksi (cash only)
        try {
            $carts = Cart::where('user_id', Auth::user()->id)->get();

            foreach ($carts as $cart) {
                $item = Item::find($cart->item_id);
                if (!$item) {
                    throw new \Exception("Item tidak ditemukan");
                }

                // CEK STOK CUKUP
                if ($item->stock < $cart->qty) {
                    throw new \Exception("Stok {$item->name} tidak mencukupi");
                }

                // KURANGI STOK
                $item->stock -= $cart->qty;
                $item->save();

                // BUAT DETAIL TRANSAKSI
                $transaction_detail = new TransactionDetail();
                $transaction_detail->transaction_id = $transaction->id;
                $transaction_detail->item_id = $cart->item_id;
                $transaction_detail->item_price = calculate_price($cart->item, $cart->qty);
                $transaction_detail->qty = $cart->qty;
                $transaction_detail->total = $cart->subtotal;
                $transaction_detail->save();
            }
        } catch (\Exception $e) {
            // ROLLBACK STOK JIKA ADA ERROR
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
        // FINALISASI PEMBAYARAN - HANYA TUNAI
        // Method pembayaran sudah fixed ke 'Tunai', tidak ada metode lain
        $payment = PaymentMethod::where('name', $paymentMethodName)->first();
        if (!$payment) {
            throw new \Exception('Metode pembayaran tidak ditemukan.');
        }

        $total = (int) $transaction->total;
        $isCash = strtolower($paymentMethodName) === 'tunai'; // Selalu true

        $transaction->payment_method_id = $payment->id;
        $transaction->status = 'paid'; // Transaksi tunai langsung PAID
        $transaction->amount = $isCash ? $amount : $total; // Jumlah uang yang diserahkan
        $transaction->change = $isCash ? max(0, $change) : 0; // Kembalian hanya untuk tunai

        // CATATAN: Tambahkan info kasir dan timestamp proses
        $kasir = optional(Auth::user())->name;
        $stamp = now()->format('d/m/Y H:i');
        $append = trim(($note ?: '') . " (diproses: {$kasir}, {$stamp})");
        $transaction->note = trim(implode(' | ', array_filter([$transaction->note, $append])));

        $transaction->save();
    }

    public function store(Request $request): string
    {
        // FUNGSI PROSES TRANSAKSI - HANYA TUNAI
        // Semua transaksi melalui sistem tunai, tidak ada pilihan metode pembayaran lagi
        try {
            return DB::transaction(function() use ($request) {
                // VALIDASI INPUT DARI FORM PEMBAYARAN
                $request->validate([
                    'invoice' => 'required|string',
                    'invoice_no' => 'required|numeric',
                    'total' => 'required|numeric|min:0',
                    'discount' => 'nullable|numeric|min:0',
                    'payment_method' => 'required|string', // FIXED: hanya 'Tunai'
                    'amount' => 'nullable|numeric|min:0', // WAJIB untuk pembayaran tunai
                    'change' => 'nullable|numeric|min:0',
                    'note' => 'nullable|string|max:255',
                    'customer_id' => 'nullable|numeric'
                ]);

                // BUAT TRANSAKSI BARU
                $transaction = new Transaction();
                $transaction->user_id = Auth::user()->id;
                
                // Set nama pelanggan di catatan jika ada
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

                // VALIDASI PEMBAYARAN - HANYA TUNAI
                $method = $request->payment_method; // FIXED: 'Tunai' only
                $total = (int) $transaction->total;
                $isCash = strtolower($method) === 'tunai'; // Selalu true sekarang
                $amount = (int) ($request->amount ?? 0);
                $change = (int) ($request->change ?? 0);

                // VALIDASI UANG TUNAI - SELALU WAJIB
                if ($isCash && $amount < $total) {
                    throw new \Exception('Jumlah uang tunai kurang dari total!');
                }

                // PROSES CART DAN KURANGI STOK
                $this->move_cart($transaction);

                // FINALISASI PEMBAYARAN - SISTEM TUNAI
                $this->finalizePaymentCommon($transaction, $method, $amount, $change, $request->note);

                // KOSONGKAN KERANJANG SETELAH TRANSAKSI
                Cart::where('user_id', Auth::user()->id)->delete();

                // RETURN ID TRANSAKSI UNTUK CETAK NOTA
                return json_encode([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil',
                    'transaction_id' => $transaction->id,
                    'invoice' => $transaction->invoice
                ]);
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
        // PROSES PESANAN MARKETPLACE - SISTEM TUNAI
        // Setiap pesanan online selalu menggunakan metode pembayaran Tunai
        $order = DB::table('marketplace_orders')->where('id', $orderId)->first();
        if (!$order || $order->status !== 'pending_pickup') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan tidak valid atau sudah diproses.'
            ], 422);
        }

        // PASTIKAN CUSTOMER ADA
        $customerId = $this->ensureCustomerExists($order->user_id);
        if (!$customerId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data pelanggan tidak valid.'
            ], 422);
        }

        // GUNAKAN METODE PEMBAYARAN TUNAI
        // Tidak ada pilihan metode pembayaran lagi, semua online order = tunai
        $payment = PaymentMethod::whereRaw('LOWER(name) = ?', ['tunai'])->first()
                  ?? PaymentMethod::first();

        $transactionId = null;
        DB::transaction(function () use ($order, $payment, $customerId, &$transactionId) {
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

            // GENERATE NOMOR INVOICE UNIK UNTUK TRANSAKSI
            $today   = now()->format('dmy');
            $last    = DB::table('transactions')
                ->whereDate('created_at', now()->toDateString())
                ->orderByDesc('id')
                ->first();
            $seq     = $last ? ((int) $last->invoice_no + 1) : 1;
            $invoice = $today . str_pad($seq, 4, '0', STR_PAD_LEFT);

            // BUAT RECORD TRANSAKSI UNTUK MARKETPLACE ORDER
            $transactionId = DB::table('transactions')->insertGetId([
                'user_id'           => $customerId,
                'channel'           => 'online',
                'payment_status'    => 'paid',
                'pickup_status'     => 'picked_up',
                'pickup_code'       => $orderLocked->code,
                'invoice'           => $invoice,
                'invoice_no'        => (string) $seq,
                'total'             => (int) $orderLocked->total_price,
                'discount'          => 0,
                'payment_method_id' => $payment->id, // FIXED: Tunai
                'amount'            => $total, // Jumlah tunai yang diserahkan
                'change'            => 0, // Kembalian (bisa di-override customer)
                'status'            => 'paid', // Langsung PAID untuk online tunai
                'note'              => 'Marketplace pickup: ' . $orderLocked->pickup_name . ' (' . $orderLocked->phone . ')',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // BUAT DETAIL TRANSAKSI UNTUK SETIAP ITEM
            foreach ($items as $i) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'item_id'        => $i->item_id,
                    'qty'            => (int) $i->qty,
                    'item_price'     => (int) $i->price,
                    'total'          => (int) $i->price * (int) $i->qty,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            // UBAH STATUS PESANAN MENJADI SELESAI
            DB::table('marketplace_orders')
                ->where('id', $orderLocked->id)
                ->update([
                    'status'     => 'completed',
                    'updated_at' => now(),
                ]);
        });

        // RETURN ID TRANSAKSI UNTUK CETAK NOTA
        return response()->json([
            'status'  => 'success',
            'message' => 'Transaksi online berhasil diproses.',
            'transaction_id' => $transactionId
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

    $transactionId = null;
    DB::transaction(function () use ($order, &$transactionId) {
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

        $transactionId = DB::table('transactions')->insertGetId([
            'user_id'           => Auth::id(),
            'channel'           => 'online',
            'payment_status'    => 'paid',
            'pickup_status'     => 'picked_up',
            'pickup_code'       => $order->code,
            'customer_id'       => null,
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
                'transaction_id' => $transactionId,
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
        'message' => 'Pesanan berhasil diselesaikan.',
        'transaction_id' => $transactionId
    ]);
}    /**
     * Cetak nota/receipt transaksi
     */
    public function printReceipt(Transaction $transaction)
    {
        try {
            // Load transaksi dengan relasi
            $transaction->load('transactionDetails', 'user', 'paymentMethod');

            // Ambil detail item untuk setiap transaksi detail
            $transactionDetails = $transaction->transactionDetails->map(function($detail) {
                $item = Item::find($detail->item_id);
                return [
                    'item_name' => $item ? $item->name : 'Item tidak ditemukan',
                    'qty' => $detail->qty,
                    'price' => $detail->item_price,
                    'subtotal' => $detail->total,
                ];
            });

            // Data untuk view
            $data = [
                'transaction' => $transaction,
                'details' => $transactionDetails,
                'company_name' => env('APP_NAME', 'POS System'),
                'company_address' => env('COMPANY_ADDRESS', 'Alamat Perusahaan'),
                'company_phone' => env('COMPANY_PHONE', ''),
            ];

            // Generate PDF
            $pdf = Pdf::loadView('transaction.receipt', $data);
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->stream('nota-' . $transaction->invoice . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat nota: ' . $e->getMessage());
        }
    }

    /**
     * API: Dapatkan transaksi berdasarkan invoice
     */
    public function getTransactionByInvoice($invoice)
    {
        $transaction = Transaction::where('invoice', $invoice)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json(['id' => $transaction->id]);
    }

    /**
     * API: Dapatkan transaksi terakhir
     */
    public function getLastTransaction()
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json(['id' => $transaction->id]);
    }


}