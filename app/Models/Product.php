<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'short_description', 'description',
        'specifications', 'price', 'sale_price', 'stock', 'low_stock_threshold',
        'weight', 'dimensions', 'featured_image', 'meta_title', 'meta_description',
        'is_active', 'is_featured', 'views',
    ];

    protected $casts = [
        'specifications'  => 'array',
        'price'           => 'integer',
        'sale_price'      => 'integer',
        'is_active'       => 'boolean',
        'is_featured'     => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($m) => $m->slug = $m->slug ?: Str::slug($m->name));
    }

    // ── Computed ──────────────────────────────────────
    // ── Computed ──────────────────────────────────────
    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price && $this->sale_price < $this->price
            ? $this->sale_price / 100
            : $this->price / 100;
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => (int) round($value * 100),
        );
    }

    protected function salePrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value !== null ? $value / 100 : null,
            set: fn ($value) => $value !== null ? (int) round($value * 100) : null,
        );
    }

    public function getIsInStockAttribute(): bool { return $this->stock > 0; }
    public function getIsLowStockAttribute(): bool { return $this->stock > 0 && $this->stock <= $this->low_stock_threshold; }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return null;
    }

    // ── Relationships ─────────────────────────────────
    public function category(): BelongsTo  { return $this->belongsTo(Category::class); }
    public function images(): HasMany      { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function primaryImage()         { return $this->hasOne(ProductImage::class)->where('is_primary', true); }
    public function variants(): HasMany    { return $this->hasMany(ProductVariant::class); }
    public function tabs(): HasMany        { return $this->hasMany(ProductTab::class)->orderBy('sort_order'); }
    public function reviews(): HasMany     { return $this->hasMany(Review::class)->where('is_approved', true); }
    public function allReviews(): HasMany  { return $this->hasMany(Review::class); }
    public function tags(): BelongsToMany { return $this->belongsToMany(Tag::class); }
    public function wishlists(): HasMany  { return $this->hasMany(Wishlist::class); }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // ── Scopes ────────────────────────────────────────
    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }
    public function scopeInStock($q)   { return $q->where('stock', '>', 0); }
    public function scopeLowStock($q)  { return $q->whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0); }
}
