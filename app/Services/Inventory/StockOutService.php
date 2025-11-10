<?php

namespace App\Services\Inventory;

use App\Models\Item;
use App\Models\InventoryMovement;
use Exception;

class StockOutService
{
    public function takeStock(Item $item, float $quantity, string $refType, int $refId, string $note)
    {
        $remainingQty = $quantity;
        $movements = [];

        // Ambil batch berdasarkan FEFO
        foreach ($item->batches as $batch) {
            if ($remainingQty <= 0) {
                break;
            }

            $qtyToTake = min($remainingQty, $batch->remaining_quantity);
            
            // Buat movement record
            $movement = InventoryMovement::create([
                'goods_receipt_item_id' => $batch->id,
                'type' => 'out',
                'qty' => $qtyToTake,
                'ref_type' => $refType,
                'ref_id' => $refId,
                'note' => $note
            ]);

            $movements[] = $movement;

            // Update remaining quantity pada batch
            $batch->remaining_quantity -= $qtyToTake;
            $batch->save();

            $remainingQty -= $qtyToTake;
        }

        if ($remainingQty > 0) {
            // Rollback semua movements jika stok tidak cukup
            foreach ($movements as $movement) {
                $batch = $movement->goodsReceiptItem;
                $batch->remaining_quantity += $movement->qty;
                $batch->save();
                $movement->delete();
            }

            throw new Exception("Stok tidak cukup untuk item {$item->name}");
        }

        // Update total stok item
        $item->decrement('stock', $quantity);

        return $movements;
    }
}