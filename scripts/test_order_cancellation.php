<?php
/**
 * Script untuk test restore stok saat pesanan dibatalkan
 * 
 * Usage: php artisan tinker < scripts/test_order_cancellation.php
 * Atau manual copy-paste commands di artisan tinker
 */

// Import models yang dibutuhkan
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;

echo "\n=== TEST RESTORE STOK PESANAN ONLINE ===\n\n";

// 1. Setup: Ambil data testing
echo "1. Setup data testing...\n";
$customer = User::where('role', 'customer')->first();
$item = Item::whereNotNull('id')->first();

if (!$customer || !$item) {
    echo "❌ Error: Tidak ada customer atau item untuk test\n";
    exit;
}

echo "   Customer: {$customer->name} (ID: {$customer->id})\n";
echo "   Item: {$item->name} (ID: {$item->id})\n";
echo "   Stok awal: {$item->stock}\n\n";

// 2. Buat order test
echo "2. Membuat order test...\n";
$stockBefore = (int)$item->stock;
$qty = 3;

$order = MarketplaceOrder::create([
    'user_id' => $customer->id,
    'code' => 'TEST-' . strtoupper(uniqid()),
    'status' => 'pending',
    'pickup_name' => 'Test Customer',
    'phone' => '081234567890',
    'total_price' => 50000,
    'expired_at' => Carbon::now()->addHours(24),
]);

MarketplaceOrderItem::create([
    'order_id' => $order->id,
    'item_id' => $item->id,
    'qty' => $qty,
    'price' => 10000,
]);

// Simulasi pengurangan stok saat order dibuat
$item->decrement('stock', $qty);
$item->refresh();

$stockAfterOrder = (int)$item->stock;
echo "   ✅ Order dibuat: {$order->code}\n";
echo "   Order Items: 1 item (qty: {$qty})\n";
echo "   Stok sebelum order: {$stockBefore}\n";
echo "   Stok setelah order: {$stockAfterOrder}\n";
echo "   Pengurangan: " . ($stockBefore - $stockAfterOrder) . "\n\n";

if ($stockAfterOrder !== ($stockBefore - $qty)) {
    echo " Error: Stok tidak berkurang dengan benar\n";
    exit;
}

// 3. Batalkan order dan cek restore stok
echo "3. Membatalkan order dan restore stok...\n";

// Ambil service
$orderService = app(\App\Services\MarketplaceOrderService::class);

try {
    $canceled = $orderService->cancelOrder(
        $order,
        'Test cancellation - ini adalah dummy order test',
        $customer->id
    );
    
    $item->refresh();
    $stockAfterCancel = (int)$item->stock;
    
    echo "   ✅ Order dibatalkan\n";
    echo "   Order status: {$order->status}\n";
    echo "   Cancellation reason: {$order->cancellation_reason}\n";
    echo "   Stok setelah cancel: {$stockAfterCancel}\n\n";
    
    // 4. Validasi hasil
    echo "4. Validasi hasil:\n";
    
    $tests = [
        ["Stok restoration", $stockAfterCancel === $stockBefore, "Stok harus kembali ke {$stockBefore}, got {$stockAfterCancel}"],
        ["Order status", $order->status === 'cancelled', "Status harus 'cancelled', got '{$order->status}'"],
        ["Has cancellation reason", !empty($order->cancellation_reason), "Cancellation reason harus ada"],
        ["Canceled by", $order->canceled_by === $customer->id, "Canceled by harus sesuai user_id"],
    ];
    
    $allPassed = true;
    foreach ($tests as $test) {
        $name = $test[0];
        $passed = $test[1];
        $message = $test[2] ?? '';
        
        if ($passed) {
            echo "   ✅ {$name}\n";
        } else {
            echo "   ❌ {$name}\n";
            if ($message) echo "      {$message}\n";
            $allPassed = false;
        }
    }
    
    echo "\n";
    if ($allPassed) {
        echo "=== ✅ SEMUA TEST PASSED ===\n";
        echo "\nHasil:\n";
        echo "- Stok berkurang saat order dibuat ✅\n";
        echo "- Stok dikembalikan saat order dibatalkan ✅\n";
        echo "- Order data tercatat dengan benar ✅\n";
        echo "\nSistem RESTORE STOK sudah bekerja dengan SEMPURNA!\n\n";
    } else {
        echo "=== ❌ BEBERAPA TEST GAGAL ===\n\n";
    }
    
    // 5. Cleanup: Hapus data test
    echo "5. Cleanup (menghapus data test)...\n";
    MarketplaceOrderItem::where('order_id', $order->id)->delete();
    $order->delete();
    echo "   ✅ Data test dihapus\n\n";
    
} catch (\Exception $e) {
    echo "   ❌ Error saat batalkan order: " . $e->getMessage() . "\n";
    echo "   Exception: " . get_class($e) . "\n\n";
}

echo "=== TEST SELESAI ===\n\n";
