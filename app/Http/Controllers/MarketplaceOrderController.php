<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\MarketplaceOrder;
use App\Services\MarketplaceOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MarketplaceOrderController extends Controller
{
    protected string $cartSessionKey = 'marketplace_cart';
    protected MarketplaceOrderService $orderService;

    public function __construct(MarketplaceOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /** Halaman ringkasan + form pickup_name/phone/notes */
    public function create(Request $request)
    {
        $cartItems = DB::table('cart_items')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('marketplace.cart')->with('error', 'Keranjang kosong.');
        }

        $items = Item::whereIn('id', $cartItems->pluck('item_id'))->get()->keyBy('id');

        $rows = [];
        $total = 0;
        foreach ($cartItems as $cartItem) {
            $item = $items->get($cartItem->item_id);
            if (!$item) continue;

            $qty = (int) $cartItem->quantity;
            $price = (int) ($item->selling_price ?? 0);
            $subtotal = $price * $qty;

            $rows[] = [
                'item' => $item,
                'qty' => $qty,
                'price' => $price,
                'subtotal' => $subtotal
            ];
            $total += $subtotal;
        }
        
        if (empty($rows)) {
            return redirect()->route('marketplace.cart')->with('error', 'Keranjang tidak valid.');
        }

        // default isi nama dari user login
        $buyerName = Auth::user()->name ?? '';

        return view('marketplace.checkout', compact('rows','total','buyerName'));
    }

    /** Proses "Buat Pesanan" → simpan ke marketplace_orders/items & kurangi stok */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'pickup_name' => ['required','string','max:100'],
            'phone'       => ['required','string','max:30'],
            'notes'       => ['nullable','string','max:255'],
        ]);

        $cartItems = DB::table('cart_items')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('marketplace.cart')->with('error', 'Keranjang kosong.');
        }

        // lockForUpdate untuk aman stock deduction
        $items = Item::whereIn('id', $cartItems->pluck('item_id'))->lockForUpdate()->get()->keyBy('id');

        $rows = [];
        $total = 0;

        foreach ($cartItems as $cartItem) {
            $item = $items->get($cartItem->item_id);
            if (!$item) return back()->with('error', 'Ada produk yang tidak ditemukan.');

            $qty = (int) $cartItem->quantity;
            if ($qty < 1) return back()->with('error', 'Kuantitas tidak valid.');

            if ($qty > (int)$item->stock) {
                return back()->with('error', "Stok '{$item->name}' tidak mencukupi.");
            }

            $price = (int) ($item->selling_price ?? 0);
            $subtotal = $price * $qty;

            $rows[] = [
                'item_id'  => $item->id,
                'name'     => $item->name,
                'code'     => $item->code,
                'qty'      => $qty,
                'price'    => $price,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        $code = 'PO-' . strtoupper(Str::random(8));
        // Waktu pengambilan order: 24 jam
        $expiredAt = now()->addHours(24); // 24 jam dari sekarang

        DB::transaction(function () use ($user, $data, $rows, $total, $code, $expiredAt) {
            // INSERT marketplace_orders dengan expired_at
            $orderId = DB::table('marketplace_orders')->insertGetId([
                'user_id'     => $user->id,
                'code'        => $code,
                'status'      => 'pending',
                'pickup_name' => $data['pickup_name'],
                'phone'       => $data['phone'],
                'notes'       => $data['notes'] ?? null,
                'total_price' => $total,
                'expired_at'  => $expiredAt, // Set deadline pengambilan 24 jam
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // INSERT marketplace_order_items + kurangi stock
            foreach ($rows as $r) {
                DB::table('marketplace_order_items')->insert([
                    'order_id'   => $orderId,
                    'item_id'    => $r['item_id'],
                    'qty'        => $r['qty'],
                    'price'      => $r['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                // Kurangi stok di items.stock
                DB::table('items')->where('id', $r['item_id'])->decrement('stock', $r['qty']);
            }
        });

        // Hapus items dari keranjang
        DB::table('cart_items')->where('user_id', $user->id)->delete();

        return redirect()->route('marketplace.order.show', $code)
                ->with('success', 'Pesanan dibuat. Bayar tunai saat barang diambil.');
    }

    /** Nota berdasarkan data DB marketplace_orders & marketplace_order_items */
    public function show(string $code)
    {
        // Gunakan Model agar casting bekerja
        $order = MarketplaceOrder::where('code', $code)->first();
        if (!$order) abort(404);

        // AUTO-CHECK: Jika order expired tapi status masih pending → expire sekarang
        if ($order->status === 'pending' && $order->isExpired()) {
            try {
                $this->orderService->cancelOrder(
                    $order,
                    'Otomatis dihapus - Waktu pengambilan 24 jam telah berakhir',
                    null
                );
                // Reload untuk tampilkan status terbaru
                $order->refresh();
            } catch (\Exception $e) {
                Log::error("Failed to auto-expire order {$order->code}: {$e->getMessage()}");
            }
        }

        $items = $order->items()->get();

        // Bentuk rows untuk view (dengan subtotal)
        $rows = $items->map(function ($i) {
            $price = (int)$i->price;
            return [
                'item_id'  => $i->item_id,
                'qty'      => (int)$i->qty,
                'price'    => $price,
                'subtotal' => $price * (int)$i->qty,
            ];
        });

        $total = (int)$order->total_price;

        // Ambil nama item yang terkini
        $map = Item::whereIn('id', $rows->pluck('item_id'))->get()->keyBy('id');
        $rows = $rows->map(function ($r) use ($map) {
            $it = $map->get($r['item_id']);
            return [
                'name'     => $it->name ?? '-',
                'code'     => $it->code ?? '-',
                'qty'      => $r['qty'],
                'price'    => $r['price'],
                'subtotal' => $r['subtotal'],
            ];
        });

        return view('marketplace.order-show', compact('order','rows','total'));
    }

    public function finalizeAtCashier(Request $request)
    {
        $data = $request->validate([
            'order_code'        => ['required','string','exists:marketplace_orders,code'],
            'payment_method_id' => ['nullable','integer','exists:payment_methods,id'], // default: Tunai=1
        ]);

        $cashier = Auth::user();                 // pastikan middleware role kasir/admin/supervisor
        $pmId    = $data['payment_method_id'] ?? 1; // 1 = Tunai

        DB::transaction(function () use ($data, $cashier, $pmId) {
            // 1) Ambil order PENDING dan kunci (hindari race)
            $order = DB::table('marketplace_orders')
                ->where('code', $data['order_code'])
                ->lockForUpdate()
                ->first();

            if (!$order) {
                abort(404, 'Order tidak ditemukan.');
            }
            if ($order->status !== 'pending') {
                abort(400, 'Order tidak dalam status pending.');
            }

            // VALIDASI: Cek apakah order sudah expired
            $orderModel = MarketplaceOrder::find($order->id);
            if ($orderModel->isExpired()) {
                abort(400, 'Pesanan sudah kadaluarsa dan tidak dapat diproses. Stok telah dikembalikan.');
            }

            $items = DB::table('marketplace_order_items')
                ->where('order_id', $order->id)
                ->get();

            // 2) Buat nomor invoice harian (format: ddmmyy + 4 digit running)
            $today = now()->format('dmy');
            $last = DB::table('transactions')
                ->whereDate('created_at', now()->toDateString())
                ->orderByDesc('id')
                ->first();
            $seq = $last ? ((int)$last->invoice_no + 1) : 1;
            $invoice = $today . str_pad($seq, 4, '0', STR_PAD_LEFT); // contoh: 1109250001

            // 3) Insert transaksi POS
            $trxId = DB::table('transactions')->insertGetId([
                // store marketplace customer as transaction user
                'user_id'           => $order->user_id,
                'channel'           => 'online',                 // enum: pos|online
                'payment_status'    => 'paid',
                'pickup_status'     => 'picked_up',
                'pickup_code'       => $order->code,            // jejak ke marketplace_orders
                //'customer_id'       => $order->user_id,         // legacy column not used here
                'invoice'           => $invoice,
                'invoice_no'        => (string)$seq,
                'total'             => (int)$order->total_price, // int (rupiah)
                'discount'          => 0,
                'payment_method_id' => $pmId,                    // 1 = Tunai
                'amount'            => (int)$order->total_price,
                'change'            => 0,
                'status'            => 'paid',
                'note'              => 'Marketplace pickup: '.$order->pickup_name.' ('.$order->phone.')',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // 4) Insert detail transaksi
            foreach ($items as $i) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $trxId,
                    'item_id'        => $i->item_id,
                    'qty'            => (int)$i->qty,
                    'item_price'     => (int)$i->price,         // int (rupiah)
                    'total'          => (int)$i->price * (int)$i->qty,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            // 5) Update status order → picked
            DB::table('marketplace_orders')
                ->where('id', $order->id)
                ->update([
                    'status'       => 'picked',
                    'picked_up_at' => now(),
                    'updated_at'   => now(),
                ]);
        });

        return back()->with('success', 'Transaksi selesai dan dicatat pada modul POS.');
    }
    public function index()
    {
        $user = Auth::user();
        // Ambil semua pesanan user menggunakan Model agar casting bekerja
        $orders = MarketplaceOrder::where('user_id', $user->id)
                   ->orderByDesc('created_at')
                   ->get();

        return view('marketplace.orders', compact('orders'));
    }

    /**
     * Daftar pesanan online yang pending untuk kasir
     */
    public function pendingOrders()
    {
        // Hanya kasir/admin/supervisor yang bisa akses
        $orders = MarketplaceOrder::where('status', 'pending')
                   ->with('user') // Load user relationship untuk tampilkan nama customer
                   ->orderByDesc('created_at')
                   ->get();

        return view('marketplace.pending-orders', compact('orders'));
    }

    /**
     * Batalkan pesanan oleh kasir/admin
     */
    public function cancel(Request $request)
    {
        $data = $request->validate([
            'order_code' => ['required', 'string', 'exists:marketplace_orders,code'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $order = MarketplaceOrder::where('code', $data['order_code'])->first();
        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        try {
            $this->orderService->cancelOrder(
                $order,
                $data['reason'],
                Auth::id() // Track siapa yang membatalkan
            );
            return back()->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal batalkan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan pesanan oleh customer pemilik order
     * Hanya customer pembuat order yang bisa membatalkan
     */
    public function cancelByCustomer(Request $request)
    {
        $data = $request->validate([
            'order_code' => ['required', 'string', 'exists:marketplace_orders,code'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $order = MarketplaceOrder::where('code', $data['order_code'])->first();
        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        // Pastikan order adalah milik user yang login
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak berhak membatalkan pesanan ini.');
        }

        try {
            $this->orderService->cancelOrder(
                $order,
                $data['reason'],
                Auth::id() // Track siapa yang membatalkan
            );
            return back()->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal batalkan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Auto-cancel pesanan yang sudah expired (24 jam)
     * Bisa dijalankan via cronjob atau manual trigger
     */
    public function autoExpire()
    {
        $count = $this->orderService->autoExpireOrders();
        return response()->json(['success' => true, 'message' => "{$count} pesanan dibatalkan otomatis"]);
    }
}
