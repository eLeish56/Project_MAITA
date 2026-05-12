<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MarketplaceOrder extends Model
{
    protected $fillable = [
        'user_id','code','status','pickup_name','phone','notes','total_price',
        'expired_at','picked_up_at','cancellation_reason','canceled_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expired_at' => 'datetime',
        'picked_up_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(MarketplaceOrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }

    /**
     * Cek apakah order sudah expired
     */
    public function isExpired(): bool
    {
        if (!$this->expired_at) return false;
        
        $expiredAt = $this->expired_at instanceof \DateTime 
            ? $this->expired_at 
            : Carbon::parse($this->expired_at);
            
        return now()->isAfter($expiredAt);
    }

    /**
     * Cek apakah order masih bisa diambil
     */
    public function canBePicked(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Cek apakah order bisa dibatalkan
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'pending' && !$this->picked_up_at;
    }

    /**
     * Get waktu sisa pengambilan
     */
    public function getTimeRemainingAttribute(): ?string
    {
        if (!$this->expired_at) return null;
        
        // Ensure expired_at is Carbon instance
        $expiredAt = $this->expired_at instanceof \DateTime 
            ? $this->expired_at 
            : \Carbon\Carbon::parse($this->expired_at);
        
        if (now()->isAfter($expiredAt)) {
            return 'Kadaluarsa';
        }

        // Calculate remaining time using diff()
        $diff = $expiredAt->diff(now());
        $hours = $diff->h;
        $minutes = $diff->i;
        $days = $diff->d;
        
        if ($days > 0) {
            return "{$days}d {$hours}h {$minutes}m";
        }
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        return "{$minutes}m sisa";
    }
}
