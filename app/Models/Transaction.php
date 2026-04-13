<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'no_transaksi', 'user_id', 'member_id',
        'subtotal', 'diskon', 'total',
        'bayar', 'kembali', 'metode_bayar', 'payment_method',
        'midtrans_order_id',
        'payment_status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $last = static::latest('id')->first();
            $number = $last ? ($last->id + 1) : 1;
            $model->no_transaksi = 'TRX' . date('Ymd') . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function user()    { return $this->belongsTo(User::class); }
    public function member()  { return $this->belongsTo(Member::class); }
    public function items()   { return $this->hasMany(TransactionItem::class); }
}