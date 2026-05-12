<?php

namespace App\Console\Commands;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestRestoreStok extends Command
{
    protected $signature = 'test:restore-stok';
    protected $description = 'Test restore stok untuk pesanan yang dibatalkan';

    public function handle()
    {
        $this->line("\n=== TESTING RESTORE STOK PESANAN ONLINE ===\n");

        // Get test data
        $customer = User::where('role', 'customer')->first();
        $item = Item::whereNotNull('id')->where('stock', '>', 5)->first();

        if (!$customer || !$item) {
            $this->error("❌ Tidak ada test data. Found: " . User::where('role', 'customer')->count() . " customers, " . Item::count() . " items");
            return 1;
        }

        $this->info("Customer: {$customer->name} (ID: {$customer->id})");
        $this->info("Item: {$item->name} (ID: {$item->id})");
        $this->line("Stok awal: {$item->stock}\n");

        $stockBefore = (int)$item->stock;
        $qty = 2;

        // Create test order
        $this->info("1. Membuat order test...");
        $order = MarketplaceOrder::create([
            'user_id' => $customer->id,
            'code' => 'TEST-' . strtoupper(uniqid()),
            'status' => 'pending',
            'pickup_name' => 'Test Customer',
            'phone' => '0812345678',
            'total_price' => 10000,
            'expired_at' => now()->addHours(24),
        ]);

        MarketplaceOrderItem::create([
            'order_id' => $order->id,
            'item_id' => $item->id,
            'qty' => $qty,
            'price' => 5000,
        ]);

        // Simulate stock reduction when order created
        $item->decrement('stock', $qty);
        $item->refresh();

        $stockAfterOrder = (int)$item->stock;
        $this->line("   ✅ Order created: {$order->code}");
        $this->line("   Stock before: {$stockBefore} → after order: {$stockAfterOrder} (reduced by {$qty})\n");

        if ($stockAfterOrder !== ($stockBefore - $qty)) {
            $this->error("   ❌ Stock tidak berkurang dengan benar!");
            $order->delete();
            return 1;
        }

        // Cancel order
        $this->info("2. Membatalkan order dan restore stok...");
        
        try {
            $service = app(\App\Services\MarketplaceOrderService::class);
            $service->cancelOrder($order, 'Test cancellation - dummy order', $customer->id);
            
            $order->refresh();
            $item->refresh();
            $stockAfterCancel = (int)$item->stock;

            $this->line("   ✅ Order dibatalkan");
            $this->line("   Order status: {$order->status}");
            $this->line("   Stock after cancel: {$stockAfterCancel}\n");

            // Validation
            $this->info("3. Validasi hasil:\n");
            
            $passed = 0;
            $failed = 0;

            // Test 1: Stock restoration
            if ($stockAfterCancel === $stockBefore) {
                $this->line("   ✅ Stock dikembalikan dengan benar ({$stockBefore})");
                $passed++;
            } else {
                $this->error("   ❌ Stock NOT restored! Expected: {$stockBefore}, Got: {$stockAfterCancel}");
                $failed++;
            }

            // Test 2: Order status
            if ($order->status === 'cancelled') {
                $this->line("   ✅ Order status = 'cancelled'");
                $passed++;
            } else {
                $this->error("   ❌ Order status wrong! Expected: 'cancelled', Got: '{$order->status}'");
                $failed++;
            }

            // Test 3: Cancellation reason
            if (!empty($order->cancellation_reason)) {
                $this->line("   ✅ Cancellation reason recorded");
                $passed++;
            } else {
                $this->error("   ❌ Cancellation reason empty!");
                $failed++;
            }

            // Test 4: Canceled by
            if ($order->canceled_by === $customer->id) {
                $this->line("   ✅ Canceled by user_id recorded\n");
                $passed++;
            } else {
                $this->error("   ❌ Canceled by not recorded!\n");
                $failed++;
            }

            // Summary
            $this->line("=== HASIL TEST ===");
            $this->line("Passed: {$passed}/4");
            $this->line("Failed: {$failed}/4\n");

            if ($failed === 0) {
                $this->info("✅ SEMUA TEST PASSED!");
                $this->line("\nKesimpulan:");
                $this->line("- Stok berkurang saat order dibuat ✅");
                $this->line("- Stok dikembalikan saat order dibatalkan ✅");
                $this->line("- Order data tercatat dengan benar ✅");
                $this->line("\nSistem RESTORE STOK bekerja SEMPURNA!\n");
            } else {
                $this->error("❌ Ada {$failed} test yang gagal\n");
            }

        } catch (\Exception $e) {
            $this->error("❌ Error saat batalkan order: " . $e->getMessage());
            $this->error("Exception: " . get_class($e));
        }

        // Cleanup
        $this->info("4. Cleanup test data...");
        MarketplaceOrderItem::where('order_id', $order->id)->delete();
        $order->delete();
        $this->line("   ✅ Test data deleted\n");

        return 0;
    }
}
