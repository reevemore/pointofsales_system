<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'toko_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Relasi ke toko
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'toko_id');
    }

    // Helper: apakah user ini superadmin?
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    // Helper: apakah user ini admin toko?
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    // Helper: apakah user ini kasir?
    public function isKasir(): bool
    {
        return $this->hasRole('kasir');
    }
}