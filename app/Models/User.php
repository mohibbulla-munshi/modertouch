<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar',
        'is_banned', 'ban_reason', 'banned_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at'         => 'datetime',
            'password'          => 'hashed',
            'is_banned'         => 'boolean',
        ];
    }

    // ── Role helpers ──────────────────────────────────
    // NOTE: hasRole() is provided by Spatie's HasRoles trait — do NOT redefine it here.
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool      { return in_array($this->role, ['super_admin', 'admin']); }
    public function isManager(): bool    { return in_array($this->role, ['super_admin', 'admin', 'manager']); }
    public function isCustomer(): bool   { return $this->role === 'customer'; }

    // ── Relationships ─────────────────────────────────
    public function orders()      { return $this->hasMany(Order::class); }
    public function addresses()   { return $this->hasMany(Address::class); }
    public function reviews()     { return $this->hasMany(Review::class); }
    public function wishlist()    { return $this->hasMany(Wishlist::class); }
    public function cart()        { return $this->hasOne(Cart::class); }
    public function activityLogs(){ return $this->hasMany(ActivityLog::class); }
}
