<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'variant_id',
        'product_name', 'variant_name', 'price', 'quantity', 'subtotal',
    ];

    protected $casts = ['price' => 'integer', 'subtotal' => 'integer'];

    protected function price(): Attribute {
        return Attribute::make(get: fn ($v) => $v / 100, set: fn ($v) => (int) round($v * 100));
    }
    protected function subtotal(): Attribute {
        return Attribute::make(get: fn ($v) => $v / 100, set: fn ($v) => (int) round($v * 100));
    }

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }
}
