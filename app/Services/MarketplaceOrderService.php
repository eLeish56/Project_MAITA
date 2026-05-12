<?php

namespace App\Services;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarketplaceOrderService
{
    /**
     * Batalkan pesanan dan restore stok barang
     */
    public function cancelOrder(
        MarketplaceOrder $order,
        string $reason = 'Dibatalkan',
        ?int $cancelledBy = null
    ): bool {
        // Validasi order bisa dibatalkan
        if (!$order->canBeCancelled()) {
            throw new \Exception("Pesanan tidak bisa dibatalkan. Status: {$order->status}");
        }

        return DB::transaction(function () use ($order, $reason, $cancelledBy) {
            try {
                // 1. Restore stok untuk semua item
                $orderItems = MarketplaceOrderItem::where('order_id', $order->id)->get();

                if ($orderItems->isEmpty()) {
                    Log::warning("Order {$order->code}: No items found to restore stock", ['order_id' => $order->id]);
                    throw new \Exception("Tidak ada item dalam pesanan untuk dikembalikan.");
                }

                $restoredItems = [];
                foreach ($orderItems as $item) {
                    // Restore stock di tabel items
                    $updated = DB::table('items')
                        ->where('id', $item->item_id)
                        ->increment('stock', $item->qty);
                    
                    if ($updated === 0) {
                        Log::error("Failed to restore stock for item {$item->item_id} in order {$order->code}");
                        throw new \Exception("Gagal mengembalikan stok untuk item {$item->item_id}");
                    }

                    $restoredItems[] = [
                        'item_id' => $item->item_id,
                        'qty_restored' => $item->qty
                    ];

                    Log::info("Stock restored", [
                        'order_code' => $order->code,
                        'item_id' => $item->item_id,
                        'qty_restored' => $item->qty
                    ]);
                }

                // 2. Update status order
                $status = $cancelledBy === null ? 'expired' : 'cancelled';

                $order->update([
                    'status' => $status,
                    'cancellation_reason' => $reason,
                    'canceled_by' => $cancelledBy,
                ]);

                Log::info("Order cancelled successfully", [
                    'order_code' => $order->code,
                    'order_id' => $order->id,
                    'status' => $status,
                    'reason' => $reason,
                    'cancelled_by' => $cancelledBy,
                    'items_restored' => count($restoredItems)
                ]);

                return true;
            } catch (\Exception $e) {
                Log::error("Error cancelling order {$order->code}: {$e->getMessage()}", [
                    'order_id' => $order->id,
                    'exception' => $e
                ]);
                throw $e;
            }
        });
    }

    /**
     * Mark order sebagai picked up oleh kasir
     */
    public function markAsPicked(MarketplaceOrder $order): bool
    {
        if (!$order->canBePicked()) {
            throw new \Exception('Pesanan tidak bisa diambil: sudah expired atau status tidak valid');
        }

        return $order->update([
            'status' => 'picked',
            'picked_up_at' => now(),
        ]);
    }

    /**
     * Auto-cancel semua pesanan yang sudah melewati waktu expiry
     */
    public function autoExpireOrders(): int
    {
        $expiredOrders = MarketplaceOrder::where('status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->get();

        $count = 0;
        foreach ($expiredOrders as $order) {
            try {
                // Ensure $order is a MarketplaceOrder instance
                if (!($order instanceof MarketplaceOrder)) {
                    $order = MarketplaceOrder::find($order->id);
                }
                $this->cancelOrder($order, 'Otomatis dihapus - Waktu pengambilan 24 jam telah berakhir', null);
                $count++;
            } catch (\Exception $e) {
                logger()->error("Failed to auto-expire order {$order->code}", ['error' => $e->getMessage()]);
            }
        }

        return $count;
    }

    /**
     * Set expiry time untuk order baru (24 jam dari sekarang)
     */
    public function setExpiry(MarketplaceOrder $order, int $hoursFromNow = 24): bool
    {
        $expiredAt = now()->addHours($hoursFromNow);
        return $order->update(['expired_at' => $expiredAt]);
    }
}
