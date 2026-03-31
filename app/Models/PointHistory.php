<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    protected $fillable = [
        'member_id', 'transaction_id', 'type',
        'poin', 'poin_before', 'poin_after', 'keterangan',
    ];

    public function member()      { return $this->belongsTo(Member::class); }
    public function transaction() { return $this->belongsTo(Transaction::class); }
}