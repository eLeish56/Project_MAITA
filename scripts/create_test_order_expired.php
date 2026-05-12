<?php
/**
 * Script: Create Test Order dengan Expiry 1 Menit (untuk testing)
 * 
 * Jalankan: php scripts/create_test_order_expired.php
 * 
 * Ini membuat order marketplace dengan waktu expired hanya 1 menit,
 * untuk mudah test auto-expire logic.
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  CREATE TEST ORDER (EXPIRED IN 1 MINUTE)\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

try {
    DB::beginTransaction();

    // 1. Ambil user pertama (atau buat test user)
    $user = DB::table('users')->first();
    if (!$user) {
        throw new Exception("No user found. Please create a user first.");
    }
    
    echo "Using user: {$user->name} (ID: {$user->id})\n";

    // 2. Ambil item pertama yang stock > 0
    $item = DB::table('items')->where('stock', '>', 0)->first();
    if (!$item) {
        throw new Exception("No items with stock available.");
    }
    
    echo "Using item: {$item->name} (Stock: {$item->stock})\n";

    // 3. Buat order dengan expiry 30 detik dari sekarang
    $code = 'TEST-' . strtoupper(\Illuminate\Support\Str::random(8));
    $expiredAt = now()->addSeconds(30); // 30 detik untuk testing
    
    $orderId = DB::table('marketplace_orders')->insertGetId([
        'user_id'     => $user->id,
        'code'        => $code,
        'status'      => 'pending',
        'pickup_name' => 'Test Pickup',
        'phone'       => '081234567890',
        'notes'       => 'This is a test order - will expire in 1 minute',
        'total_price' => $item->selling_price * 2,
        'expired_at'  => $expiredAt,
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    echo "✓ Order created: {$code}\n";
    echo "  Order ID: {$orderId}\n";
    echo "  Expired at: {$expiredAt->format('Y-m-d H:i:s')}\n";

    // 4. Insert order items (qty 2)
    DB::table('marketplace_order_items')->insert([
        'order_id'   => $orderId,
        'item_id'    => $item->id,
        'qty'        => 2,
        'price'      => $item->selling_price,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    echo "✓ Order item added: {$item->name} (qty: 2)\n";

    // 5. Reduce stock
    DB::table('items')->where('id', $item->id)->decrement('stock', 2);
    
    $newStock = DB::table('items')->where('id', $item->id)->value('stock');
    echo "✓ Stock reduced: {$item->name} (new stock: {$newStock})\n";

    DB::commit();

    echo "\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "  ✅ TEST ORDER CREATED SUCCESSFULLY\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "\n";
    echo "NEXT STEPS:\n";
    echo "  1. Wait 1 minute for order to expire\n";
    echo "  2. Run: php artisan marketplace:expire-orders\n";
    echo "  3. Check order status at: http://127.0.0.1:8000/marketplace/order/{$code}\n";
    echo "  4. Verify stock was restored: {$item->name} should be {$newStock + 2}\n";
    echo "\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
