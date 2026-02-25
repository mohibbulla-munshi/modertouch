<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($m) => $m->slug = $m->slug ?: Str::slug($m->name));
    }

    public function products() { return $this->belongsToMany(Product::class); }
}
