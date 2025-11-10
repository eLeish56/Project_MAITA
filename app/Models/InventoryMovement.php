<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $fillable = [
        'goods_receipt_item_id',
        'type', // in/out
        'qty',
        'ref_type', // GR, Sales, etc
        'ref_id',
        'note'
    ];

    protected $casts = [
        'qty' => 'decimal:2'
    ];

    public function goodsReceiptItem(): BelongsTo
    {
        return $this->belongsTo(GoodsReceiptItem::class);
    }
}