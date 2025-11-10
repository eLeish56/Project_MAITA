<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceiptItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BatchController extends Controller
{
    public function index()
    {
        $batches = GoodsReceiptItem::with('goodsReceipt')
            ->where('remaining_quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();

        return view('inventory.batches', compact('batches'));
    }

    public function itemBatches(Item $item)
    {
        $batches = $item->batches;
        return response()->json($batches);
    }

    public function updateExpiryStatus()
    {
        $batches = GoodsReceiptItem::whereNotNull('expiry_date')->get();
        $today = Carbon::today();

        foreach ($batches as $batch) {
            $daysUntilExpiry = $today->diffInDays($batch->expiry_date, false);
            
            if ($daysUntilExpiry < 0) {
                $batch->expiry_status = 'expired';
            } elseif ($daysUntilExpiry <= 30) {
                $batch->expiry_status = 'warning';
            } else {
                $batch->expiry_status = 'safe';
            }
            
            $batch->save();
        }

        return response()->json(['message' => 'Status kadaluarsa batch berhasil diperbarui']);
    }
}