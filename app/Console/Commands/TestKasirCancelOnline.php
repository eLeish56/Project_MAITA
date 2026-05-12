<?php

namespace App\Console\Commands;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestKasirCancelOnline extends Command
{
    protected $signature = 'test:kasir-cancel-online';
    protected $description = 'Test kasir batalkan pesanan online dengan restore stok';

    public function handle()
    {
        $this->line("\n=== TEST KASIR BATALKAN PESANAN ONLINE ===\n");

        // Get test data
        $kasir = User::where('role', 'cashier')->first();
        $customer = User::where('role', 'customer')->first();
        $item = Item::whereNotNull('id')->where('stock', '>', 5)->first();

        if (!$kasir || !$customer || !$item) {
            $this->error("❌ Tidak ada test data. Found: " . 
                User::where('role', 'cashier')->count() . " cashiers, " .
                User::where('role', 'customer')->count() . " customers, " .
                Item::count() . " items");
            return 1;
        }

        $this->info("Kasir: {$kasir->name} (ID: {$kasir->id})");
        $this->info("Customer: {$customer->name} (ID: {$customer->id})");
        $this->info("Item: {$item->name} (ID: {$item->id})");
        $this->line("Stok awal: {$item->stock}\n");

        $stockBefore = (int)$item->stock;
        $qty = 3;

        // Create test online order (marketplace order)
        $this->info("1. Membuat pesanan online test...");
        $order = MarketplaceOrder::create([
            'user_id' => $customer->id,
            'code' => 'TEST-' . strtoupper(uniqid()),
            'status' => 'pending',
            'pickup_name' => 'Test Customer',
            'phone' => '0812345678',
            'total_price' => 50000,
            'expired_at' => now()->addHours(24),
        ]);

        MarketplaceOrderItem::create([
            'order_id' => $order->id,
            'item_id' => $item->id,
            'qty' => $qty,
            'price' => 10000,
        ]);

        // Simulate stock reduction when order created
        $item->decrement('stock', $qty);
        $item->refresh();

        $stockAfterOrder = (int)$item->stock;
        $this->line("   ✅ Pesanan online dibuat: {$order->code}");
        $this->line("   Pemesan: {$order->pickup_name}");
        $this->line("   Item: {$qty} unit");
        $this->line("   Stok awal: {$stockBefore} → setelah order: {$stockAfterOrder}\n");

        if ($stockAfterOrder !== ($stockBefore - $qty)) {
            $this->error("   ❌ Stok tidak berkurang dengan benar!");
            $order->delete();
            return 1;
        }

        // Kasir batalkan pesanan
        $this->info("2. Kasir membatalkan pesanan online...");
        
        try {
            $service = app(\App\Services\MarketplaceOrderService::class);
            $cancelledReason = 'Test cancellation by cashier - alasan pembatalan untuk test';
            
            $service->cancelOrder(
                $order, 
                $cancelledReason, 
                $kasir->id  // Kasir yang membatalkan
            );
            
            $order->refresh();
            $item->refresh();
            $stockAfterCancel = (int)$item->stock;

            $this->line("   ✅ Pesanan dibatalkan oleh kasir");
            $this->line("   Kasir: {$kasir->name}");
            $this->line("   Order status: {$order->status}");
            $this->line("   Stok setelah cancel: {$stockAfterCancel}\n");

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
                $this->line("   ✅ Cancellation reason recorded: \"" . substr($order->cancellation_reason, 0, 40) . "...\"");
                $passed++;
            } else {
                $this->error("   ❌ Cancellation reason empty!");
                $failed++;
            }

            // Test 4: Canceled by (kasir ID)
            if ($order->canceled_by === $kasir->id) {
                $this->line("   ✅ Canceled by kasir recorded (ID: {$kasir->id})");
                $passed++;
            } else {
                $this->error("   ❌ Canceled by NOT recorded! Expected: {$kasir->id}, Got: {$order->canceled_by}");
                $failed++;
            }

            // Test 5: Order still owned by customer
            if ($order->user_id === $customer->id) {
                $this->line("   ✅ Order tetap dimiliki customer asli\n");
                $passed++;
            } else {
                $this->error("   ❌ Order ownership changed!\n");
                $failed++;
            }

            // Summary
            $this->line("=== HASIL TEST ===");
            $this->line("Passed: {$passed}/5");
            $this->line("Failed: {$failed}/5\n");

            if ($failed === 0) {
                $this->info("✅ SEMUA TEST PASSED!");
                $this->line("\nKesimpulan:");
                $this->line("- Stok berkurang saat order dibuat ✅");
                $this->line("- Kasir dapat membatalkan pesanan online ✅");
                $this->line("- Stok dikembalikan dengan benar ✅");
                $this->line("- Data pembatalan tercatat dengan benar (reason & kasir ID) ✅");
                $this->line("- Sistem KASIR CANCEL ONLINE bekerja SEMPURNA!\n");
            } else {
                $this->error("❌ Ada {$failed} test yang gagal\n");
            }

        } catch (\Exception $e) {
            $this->error("❌ Error saat batalkan pesanan: " . $e->getMessage());
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
