<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PurchaseRequestItem model
 *
 * Represents a single line item within a purchase request.
 */
class PurchaseRequestItem extends Model
{
    protected $fillable = [
        'purchase_request_id',
        'product_name',
        'quantity',
        'unit',
        'current_stock',
        'unit_price',
        'notes',
    ];

    /**
     * Parent purchase request.
     */
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    /**
     * Get the supplier through the purchase request.
     */
    public function getSupplierAttribute()
    {
        return $this->purchaseRequest ? $this->purchaseRequest->supplier : null;
    }

    /**
     * Get the associated item if it exists.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}