<?php
/**
 * Script Test: Auto-Expire Marketplace Orders
 * 
 * Jalankan: php scripts/test_marketplace_expiry.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  TEST: AUTO-EXPIRE MARKETPLACE ORDERS (24 JAM)\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

// 1. Cek orders yang pending dan belum expired
echo "1. CEK PENDING ORDERS (YANG BELUM EXPIRED):\n";
echo "   ───────────────────────────────────────\n";
$pending = \DB::table('marketplace_orders')
    ->where('status', 'pending')
    ->where(function($q) {
        $q->whereNull('expired_at')
          ->orWhere('expired_at', '>', \DB::raw('NOW()'));
    })
    ->select('code', 'pickup_name', 'total_price', 'expired_at', 'created_at')
    ->get();

if ($pending->count() > 0) {
    echo "   Found {$pending->count()} pending orders:\n";
    foreach ($pending as $order) {
        $remaining = \Carbon\Carbon::parse($order->expired_at)->diffInMinutes(now());
        $hours = intval($remaining / 60);
        $mins = $remaining % 60;
        echo "   ✓ {$order->code} | {$order->pickup_name} | Sisa: {$hours}h {$mins}m\n";
    }
} else {
    echo "   ✓ Tidak ada pending orders\n";
}

echo "\n";

// 2. Cek orders yang sudah expired (menunggu cancel)
echo "2. CEK ORDERS YANG SUDAH EXPIRED (BELUM DIBATALKAN):\n";
echo "   ──────────────────────────────────────────────────\n";
$expired = \DB::table('marketplace_orders')
    ->where('status', 'pending')
    ->whereNotNull('expired_at')
    ->where('expired_at', '<=', \DB::raw('NOW()'))
    ->select('code', 'pickup_name', 'total_price', 'expired_at', 'created_at')
    ->get();

if ($expired->count() > 0) {
    echo "   Found {$expired->count()} EXPIRED orders yang perlu dibatalkan:\n";
    foreach ($expired as $order) {
        $expiredTime = \Carbon\Carbon::parse($order->expired_at)->format('d/m/Y H:i:s');
        echo "   ⚠ {$order->code} | {$order->pickup_name} | Expired: {$expiredTime}\n";
    }
    
    echo "\n   🔄 MENJALANKAN AUTO-EXPIRE COMMAND...\n";
    echo "   ───────────────────────────────────────\n";
    
    $status = $kernel->call('marketplace:expire-orders');
    
    echo "\n";
    if ($status === 0) {
        echo "   ✅ SUCCESS! Orders telah dibatalkan & stok dikembalikan\n";
    } else {
        echo "   ❌ FAILED! Ada error saat auto-expire\n";
    }
} else {
    echo "   ✓ Tidak ada orders yang expired\n";
}

echo "\n";

// 3. Cek hasil (orders yang sudah cancelled/expired)
echo "3. CEK ORDERS YANG SUDAH DIBATALKAN:\n";
echo "   ────────────────────────────────────\n";
$cancelled = \DB::table('marketplace_orders')
    ->whereIn('status', ['expired', 'cancelled'])
    ->select('code', 'pickup_name', 'status', 'cancellation_reason', 'updated_at')
    ->orderBy('updated_at', 'desc')
    ->limit(5)
    ->get();

if ($cancelled->count() > 0) {
    echo "   Recent cancelled orders:\n";
    foreach ($cancelled as $order) {
        echo "   ✓ {$order->code} | Status: {$order->status} | Reason: {$order->cancellation_reason}\n";
    }
} else {
    echo "   ✓ Tidak ada orders yang dibatalkan\n";
}

echo "\n";

// 4. Verifikasi stock restoration
echo "4. VERIFIKASI STOCK RESTORATION:\n";
echo "   ──────────────────────────────\n";
$restoredItems = \DB::table('marketplace_orders as mo')
    ->join('marketplace_order_items as moi', 'mo.id', '=', 'moi.order_id')
    ->whereIn('mo.status', ['expired', 'cancelled'])
    ->select('moi.item_id', \DB::raw('SUM(moi.qty) as total_qty'))
    ->groupBy('moi.item_id')
    ->limit(3)
    ->get();

if ($restoredItems->count() > 0) {
    echo "   Items yang di-restore:\n";
    foreach ($restoredItems as $item) {
        $itemData = \DB::table('items')->where('id', $item->item_id)->first();
        echo "   ✓ {$itemData->name} | Restored qty: {$item->total_qty}\n";
    }
} else {
    echo "   ✓ Belum ada items yang di-restore\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  ✅ TEST SELESAI\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";
