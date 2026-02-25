<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'description', 'type', 'value', 'minimum_order',
        'maximum_discount', 'usage_limit', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
        'value'      => 'integer',
        'minimum_order' => 'integer',
        'maximum_discount' => 'integer',
    ];

    protected function value(): Attribute {
        return Attribute::make(get: fn ($v) => $v !== null ? $v / 100 : null, set: fn ($v) => $v !== null ? (int) round($v * 100) : null);
    }
    protected function minimumOrder(): Attribute {
        return Attribute::make(get: fn ($v) => $v !== null ? $v / 100 : null, set: fn ($v) => $v !== null ? (int) round($v * 100) : null);
    }
    protected function maximumDiscount(): Attribute {
        return Attribute::make(get: fn ($v) => $v !== null ? $v / 100 : null, set: fn ($v) => $v !== null ? (int) round($v * 100) : null);
    }

    public function isValid(float $orderTotal = 0): bool
    {
        if (! $this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($orderTotal < $this->minimum_order) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        $discount = $this->type === 'percent'
            ? $subtotal * ($this->value / 100)
            : $this->value;

        if ($this->maximum_discount) {
            $discount = min($discount, $this->maximum_discount);
        }

        return min($discount, $subtotal);
    }
}
