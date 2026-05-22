<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'toko_id',
        'no_transaksi',
        'user_id',
        'member_id',
        'subtotal',
        'diskon',
        'total',
        'bayar',
        'kembali',
        'metode_bayar',
        'payment_method',
        'midtrans_order_id',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'diskon'   => 'integer',
            'total'    => 'integer',
            'bayar'    => 'integer',
            'kembali'  => 'integer',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'toko_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function pointHistories(): HasMany
    {
        return $this->hasMany(PointHistory::class);
    }
}