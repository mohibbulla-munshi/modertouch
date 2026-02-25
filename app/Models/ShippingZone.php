<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = ['name', 'regions', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function rates() { return $this->hasMany(ShippingRate::class, 'zone_id'); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
