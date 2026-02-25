<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'guest_name', 'guest_email', 'guest_phone',
        'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_city',
        'shipping_state', 'shipping_postal', 'shipping_country',
        'subtotal', 'discount', 'shipping_cost', 'total', 'coupon_code',
        'payment_method', 'payment_status', 'status', 'notes',
    ];

    protected $casts = [
        'subtotal'      => 'integer',
        'discount'      => 'integer',
        'shipping_cost' => 'integer',
        'total'         => 'integer',
    ];

    protected function subtotal(): Attribute {
        return Attribute::make(get: fn ($v) => $v / 100, set: fn ($v) => (int) round($v * 100));
    }
    protected function discount(): Attribute {
        return Attribute::make(get: fn ($v) => $v / 100, set: fn ($v) => (int) round($v * 100));
    }
    protected function shippingCost(): Attribute {
        return Attribute::make(get: fn ($v) => $v / 100, set: fn ($v) => (int) round($v * 100));
    }
    protected function total(): Attribute {
        return Attribute::make(get: fn ($v) => $v / 100, set: fn ($v) => (int) round($v * 100));
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = $order->order_number ?? strtoupper('ORD-' . Str::random(8));
        });
    }

    public function user()         { return $this->belongsTo(User::class); }
    public function items()        { return $this->hasMany(OrderItem::class); }
    public function statusHistory(){ return $this->hasMany(OrderStatusHistory::class)->latest(); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'warning',
            'processing' => 'info',
            'shipped'    => 'primary',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    }

    public function scopeByStatus($q, string $status) { return $q->where('status', $status); }
}
