<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $last = static::latest('id')->first();
            $number = $last ? ($last->id + 1) : 1;
            $model->kode_produk = 'PRD' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMutations()
    {
        return $this->hasMany(StockMutation::class);
    }
}