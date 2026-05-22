<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'toko_id',
        'category_id',
        'kode_produk',
        'nama',
        'deskripsi',
        'foto',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif'      => 'boolean',
            'harga_beli' => 'integer',
            'harga_jual' => 'integer',
            'stok'       => 'integer',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'toko_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}