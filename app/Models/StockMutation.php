<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    protected $fillable = [
        'toko_id',
        'product_id',
        'user_id',
        'type',
        'qty',
        'stok_before', // <-- Tambahkan ini jika belum ada
        'stok_after',  // <-- Tambahkan ini jika belum ada
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'toko_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}