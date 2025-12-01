<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'address',
        'role',
        'picture',
        'password',
        'position',
        'is_active', // Tambahkan flag untuk status aktif
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function transactions()
    {
        // transactions where this user is the customer (legacy column `customer_id` is not used)
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function marketplaceOrders()
    {
        return $this->hasMany(MarketplaceOrder::class, 'user_id');
    }

    // helper role
    public function hasRole($roles)
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles, true);
    }

    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }

    /**
     * Allow login with username or email
     */
    public function findForPassport($username)
    {
        return $this->where('email', $username)
                    ->orWhere('username', $username)
                    ->first();
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
