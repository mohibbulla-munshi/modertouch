<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'sku', 'price', 'stock', 'attributes', 'is_active'];
    protected $casts    = ['attributes' => 'array', 'price' => 'integer', 'is_active' => 'boolean'];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value !== null ? $value / 100 : null,
            set: fn ($value) => $value !== null ? (int) round($value * 100) : null,
        );
    }

    public function product() { return $this->belongsTo(Product::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
