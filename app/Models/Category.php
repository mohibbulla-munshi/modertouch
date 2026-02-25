<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'description',
        'image', 'meta_title', 'meta_description', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($m) => $m->slug = $m->slug ?: Str::slug($m->name));
        static::updating(fn ($m) => $m->slug = $m->slug ?: Str::slug($m->name));
    }

    public function parent(): BelongsTo  { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children(): HasMany  { return $this->hasMany(Category::class, 'parent_id'); }
    public function products(): HasMany  { return $this->hasMany(Product::class); }

    public function getActiveProductsCountAttribute(): int
    {
        return $this->products()->where('is_active', true)->count();
    }
}
