<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'variant_id', 'quantity', 'price'];
    protected $casts    = ['price' => 'integer'];

    protected function price(): Attribute {
        return Attribute::make(get: fn ($v) => $v / 100, set: fn ($v) => (int) round($v * 100));
    }

    public function cart()    { return $this->belongsTo(Cart::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variant_id'); }

    public function getLineTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
