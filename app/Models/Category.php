<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'deskripsi', 'aktif'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->nama) . '-' . time();
        });
        static::updating(function ($model) {
            $model->slug = Str::slug($model->nama) . '-' . time();
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}