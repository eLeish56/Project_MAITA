<?php
/**
 * Script untuk Testing Auto-Cancel Marketplace Orders
 * 
 * WORKFLOW:
 * 1. Buat pesanan marketplace dengan expired_at = sekarang + 30 detik
 * 2. Tunggu ~40 detik
 * 3. Jalankan command: php artisan marketplace:expire-orders
 * 4. Verifikasi status pesanan berubah menjadi "expired" dan stok ter-restore
 */

require 'bootstrap/app.php';

use App\Models\MarketplaceOrder;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "====== TEST MARKETPLACE AUTO-CANCEL ======\n\n";

// Test Case 1: Cek pesanan expired
echo "📋 TEST 1: Cek Pesanan Expired\n";
echo "─────────────────────────────────\n";

$expiredOrders = MarketplaceOrder::where('status', 'pending')
    ->whereNotNull('expired_at')
    ->where('expired_at', '<=', now())
    ->get();

echo "Pesanan pending yang expired: " . $expiredOrders->count() . "\n";

if ($expiredOrders->count() > 0) {
    foreach ($expiredOrders as $order) {
        echo "\n  • Kode: {$order->code}\n";
        echo "    Status: {$order->status}\n";
        echo "    Expired At: {$order->expired_at}\n";
        echo "    Waktu Sekarang: " . now()->toDateTimeString() . "\n";
        
        // Lihat item-item
        $items = $order->items;
        echo "    Items (" . $items->count() . "):\n";
        foreach ($items as $item) {
            echo "      - Item ID {$item->item_id}: {$item->qty} units @ Rp" . number_format($item->price) . "\n";
        }
    }
}

echo "\n\n";
echo "📋 TEST 2: Jalankan Auto-Expire Command\n";
echo "────────────────────────────────────────\n";
echo "Jalankan di terminal: php artisan marketplace:expire-orders\n";

echo "\n\n";
echo "📋 TEST 3: Verifikasi Hasil Setelah Command\n";
echo "────────────────────────────────────────────\n";

$allOrders = MarketplaceOrder::all();
echo "Total pesanan marketplace: " . $allOrders->count() . "\n";
echo "  • Status pending: " . $allOrders->where('status', 'pending')->count() . "\n";
echo "  • Status expired: " . $allOrders->where('status', 'expired')->count() . "\n";
echo "  • Status picked: " . $allOrders->where('status', 'picked')->count() . "\n";
echo "  • Status cancelled: " . $allOrders->where('status', 'cancelled')->count() . "\n";

echo "\n\n";
echo "📋 TEST 4: Cek Stock Restoration\n";
echo "─────────────────────────────────\n";

$expiredOrders = MarketplaceOrder::where('status', 'expired')->get();
if ($expiredOrders->count() > 0) {
    echo "Pesanan expired (stock seharusnya ter-restore):\n";
    foreach ($expiredOrders as $order) {
        echo "\n  Order {$order->code}:\n";
        $items = $order->items;
        foreach ($items as $item) {
            $currentItem = Item::find($item->item_id);
            echo "    • Item {$currentItem->name}:\n";
            echo "      - Qty pesanan: {$item->qty}\n";
            echo "      - Current stock: {$currentItem->stock}\n";
        }
    }
} else {
    echo "Belum ada pesanan yang expired.\n";
}

echo "\n✅ Testing selesai!\n";
?>
