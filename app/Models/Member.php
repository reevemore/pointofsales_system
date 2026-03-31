<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_member',
        'nama',
        'telepon',
        'email',
        'alamat',
        'poin',
        'status',
    ];

    // Auto-generate kode member
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $last = static::latest('id')->first();
            $number = $last ? ($last->id + 1) : 1;
            $model->kode_member = 'MBR' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function pointHistories()
    {
    return $this->hasMany(PointHistory::class);
    }
}