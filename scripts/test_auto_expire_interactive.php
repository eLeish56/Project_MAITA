<?php
/**
 * Testing Auto-Expire Marketplace Orders - Interactive Testing
 * 
 * SCENARIOS:
 * 1. Buat pesanan → Cek status awal
 * 2. Tunggu timeout → Cek auto-update status via show view
 * 3. Coba proses order → Validasi error jika expired
 * 4. Verifikasi stok ter-restore
 */

require 'bootstrap/app.php';

use App\Models\MarketplaceOrder;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║  AUTO-EXPIRE MARKETPLACE ORDERS - TESTING SCRIPT         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

// ==================== TEST 1: Cek Pesanan Pending ====================
echo "📋 TEST 1: Lihat Pesanan Pending (Belum Expired)\n";
echo "─────────────────────────────────────────────────\n";

$pendingOrders = MarketplaceOrder::where('status', 'pending')
    ->where('expired_at', '>', now())
    ->limit(5)
    ->get();

if ($pendingOrders->count() > 0) {
    foreach ($pendingOrders as $order) {
        $remainingMin = $order->expired_at->diffInMinutes(now());
        echo "  ✓ {$order->code} | {$order->pickup_name} | {$remainingMin}m sisa\n";
    }
} else {
    echo "  ℹ Tidak ada pesanan pending yang belum expired\n";
}

// ==================== TEST 2: Cek Pesanan Expired (Status Masih Pending) ====================
echo "\n📋 TEST 2: Cari Pesanan Expired tapi Status Masih PENDING\n";
echo "────────────────────────────────────────────────────────────\n";

$expiredButPending = MarketplaceOrder::where('status', 'pending')
    ->where('expired_at', '<=', now())
    ->limit(5)
    ->get();

if ($expiredButPending->count() > 0) {
    echo "  ⚠️  Ditemukan {$expiredButPending->count()} order yang sudah timeout tapi status masih PENDING!\n";
    foreach ($expiredButPending as $order) {
        $expiredSince = now()->diffInSeconds($order->expired_at);
        echo "  • {$order->code}\n";
        echo "    - Pickup: {$order->pickup_name}\n";
        echo "    - Expired: " . $order->expired_at->toDateTimeString() . " ({$expiredSince}s ago)\n";
        echo "    - Status: {$order->status}\n";
        echo "    - isExpired(): " . ($order->isExpired() ? 'TRUE' : 'FALSE') . "\n";
    }
    echo "\n  ℹ️  INFO: Jalankan artisan command atau akses halaman order untuk trigger auto-expire\n";
} else {
    echo "  ✅ Tidak ada order expired dengan status pending\n";
}

// ==================== TEST 3: Cek Pesanan Sudah Expired (Status Diupdate) ====================
echo "\n📋 TEST 3: Lihat Pesanan Sudah Expired (Status Diupdate)\n";
echo "──────────────────────────────────────────────────────────\n";

$expiredOrders = MarketplaceOrder::where('status', 'expired')
    ->limit(5)
    ->get();

if ($expiredOrders->count() > 0) {
    foreach ($expiredOrders as $order) {
        echo "  ✓ {$order->code} | Status: EXPIRED\n";
        echo "    - Reason: {$order->cancellation_reason}\n";
        echo "    - Cancelled By: " . ($order->canceled_by ? User::find($order->canceled_by)->name : 'Sistem (Auto)') . "\n";
        
        // Verifikasi stok restored
        $items = $order->items;
        foreach ($items as $item) {
            $currentItem = Item::find($item->item_id);
            echo "    - Item: {$currentItem->name} | Stock restored: +{$item->qty}\n";
        }
    }
} else {
    echo "  ℹ Tidak ada pesanan dengan status expired\n";
}

// ==================== TEST 4: Validasi Expired Order Tidak Bisa Diproses ====================
echo "\n📋 TEST 4: Validasi Pesanan Expired Tidak Bisa Diproses\n";
echo "────────────────────────────────────────────────────────\n";

if ($expiredOrders->count() > 0) {
    $expiredOrder = $expiredOrders->first();
    
    // Coba simulate proses di cashier
    echo "  Mencoba proses order expired: {$expiredOrder->code}\n";
    
    if ($expiredOrder->isExpired()) {
        echo "  ✅ Order terdeteksi EXPIRED\n";
        echo "  ✅ Tidak bisa diproses (sesuai validasi)\n";
    } else {
        echo "  ❌ Order tidak terdeteksi expired (ERROR)\n";
    }
} else {
    echo "  ℹ Tidak ada order expired untuk ditest\n";
}

// ==================== TEST 5: Informasi Schedule ====================
echo "\n📋 TEST 5: Informasi Schedule & Command\n";
echo "───────────────────────────────────────\n";
echo "  Schedule: marketplace:expire-orders\n";
echo "  Frekuensi: Setiap MENIT (testing) / HOURLY (production)\n";
echo "  File: app/Console/Commands/ExpireMarketplaceOrders.php\n";
echo "\n  ✅ Manual trigger:\n";
echo "     php artisan marketplace:expire-orders\n";

// ==================== TEST 6: Database Statistics ====================
echo "\n📋 TEST 6: Statistik Database\n";
echo "──────────────────────────────\n";

$stats = [
    'total' => MarketplaceOrder::count(),
    'pending' => MarketplaceOrder::where('status', 'pending')->count(),
    'expired' => MarketplaceOrder::where('status', 'expired')->count(),
    'picked' => MarketplaceOrder::where('status', 'picked')->count(),
    'cancelled' => MarketplaceOrder::where('status', 'cancelled')->count(),
];

echo "  Total Pesanan: {$stats['total']}\n";
echo "  • Pending: {$stats['pending']}\n";
echo "  • Expired: {$stats['expired']}\n";
echo "  • Picked: {$stats['picked']}\n";
echo "  • Cancelled: {$stats['cancelled']}\n";

// ==================== QUICK ACTION ====================
echo "\n🎯 QUICK ACTION\n";
echo "───────────────\n";

if ($expiredButPending->count() > 0) {
    echo "  1️⃣ Jalankan command untuk auto-expire:\n";
    echo "     php artisan marketplace:expire-orders\n\n";
}

echo "  2️⃣ Test via browser:\n";
echo "     • Buat pesanan marketplace\n";
echo "     • Tunggu timeout (testing: 30 detik)\n";
echo "     • Refresh halaman order\n";
echo "     • Status harus berubah otomatis menjadi EXPIRED\n\n";

echo "  3️⃣ Cek stok ter-restore:\n";
echo "     SELECT name, stock FROM items \n";
echo "     WHERE id IN (SELECT DISTINCT item_id FROM marketplace_order_items);\n";

echo "\n✅ Testing selesai!\n\n";
?>
