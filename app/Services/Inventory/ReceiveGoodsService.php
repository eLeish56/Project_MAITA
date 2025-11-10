<?php

namespace App\Services\Inventory;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\InventoryMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReceiveGoodsService
{
    /**
     * Proses GR Item baru
     */
    public function processGRItem(GoodsReceiptItem $grItem)
    {
        // Generate batch number dari product name
        $grItem->batch_number = $this->generateBatchNumber($grItem);

        // Set expiry status
        if ($grItem->expiry_date) {
            $daysUntilExpiry = Carbon::today()->diffInDays($grItem->expiry_date, false);
            if ($daysUntilExpiry < 0) {
                $grItem->expiry_status = 'expired';
            } elseif ($daysUntilExpiry <= 30) {
                $grItem->expiry_status = 'warning';
            } else {
                $grItem->expiry_status = 'safe';
            }
        }

        $grItem->save();

        // Buat movement record
        InventoryMovement::create([
            'goods_receipt_item_id' => $grItem->id,
            'type' => 'in',
            'qty' => $grItem->quantity_received,
            'ref_type' => 'GR',
            'ref_id' => $grItem->goods_receipt_id,
            'note' => "Penerimaan barang dari GR #{$grItem->goodsReceipt->gr_number}"
        ]);

        // Update stok master item jika ada
        if ($grItem->item_id) {
            $grItem->item->increment('stock', $grItem->quantity_received);
        }
    }

    private function generateBatchNumber(GoodsReceiptItem $grItem): string
    {
        // Generate prefix from product name
        $prefix = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($grItem->product_name, 0, 3))); 
        $date = Carbon::today()->format('ymd');
        
        $lastBatch = GoodsReceiptItem::where('batch_number', 'LIKE', "{$prefix}{$date}%")
            ->orderBy('batch_number', 'desc')
            ->first();

        if (!$lastBatch) {
            $sequence = '001';
        } else {
            $sequence = substr($lastBatch->batch_number, -3);
            $sequence = str_pad((int)$sequence + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $date . $sequence;
    }
}