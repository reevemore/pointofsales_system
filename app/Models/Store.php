<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    // Relasi ke users (admin & kasir toko ini)
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'toko_id');
    }

    // Relasi ke admin utama toko (role admin)
    public function admin()
    {
        return $this->hasMany(User::class, 'toko_id')
                    ->role('admin')
                    ->first();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'toko_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'toko_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'toko_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'toko_id');
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class, 'toko_id');
    }
}