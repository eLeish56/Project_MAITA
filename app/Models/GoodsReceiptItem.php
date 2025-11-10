<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * GoodsReceiptItem model
 *
 * Represents a single item and quantity within a goods receipt.
 */
class GoodsReceiptItem extends Model
{
    protected $fillable = [
        'goods_receipt_id',
        'item_id',
        'product_name',
        'unit',
        'quantity_received',
        'remaining_quantity',
        'lot_code',
        'batch_number',
        'expiry_date',
        'expiry_status',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity_received' => 'decimal:2',
        'remaining_quantity' => 'decimal:2'
    ];

    /**
     * Parent goods receipt.
     */
    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    /**
     * Related item.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Inventory movements for this GR item.
     */
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Check if the batch is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date <= Carbon::today();
    }

    /**
     * Get days until expiry.
     */
    public function getDaysUntilExpiry(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }
        return Carbon::today()->diffInDays($this->expiry_date, false);
    }
}