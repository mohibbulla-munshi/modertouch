<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ShippingRate extends Model
{
    protected $fillable = ['zone_id', 'name', 'rate', 'free_over'];
    protected $casts    = ['rate' => 'integer', 'free_over' => 'integer'];

    protected function rate(): Attribute {
        return Attribute::make(get: fn ($v) => $v / 100, set: fn ($v) => (int) round($v * 100));
    }
    protected function freeOver(): Attribute {
        return Attribute::make(get: fn ($v) => $v !== null ? $v / 100 : null, set: fn ($v) => $v !== null ? (int) round($v * 100) : null);
    }

    public function zone() { return $this->belongsTo(ShippingZone::class, 'zone_id'); }

    public function calculateRate(float $orderTotal): float
    {
        if ($this->free_over && $orderTotal >= $this->free_over) {
            return 0;
        }
        return (float) $this->rate;
    }
}
