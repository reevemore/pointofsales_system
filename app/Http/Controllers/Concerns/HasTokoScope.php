<?php

namespace App\Http\Controllers\Concerns;

trait HasTokoScope
{
    /**
     * Ambil toko_id dari user yang sedang login.
     */
    protected function tokoId(): int
    {
        return auth()->user()->toko_id;
    }

    /**
     * Scope query hanya untuk toko yang sedang login.
     * Contoh: $this->scopedQuery(Product::query())->get()
     */
    protected function scopedQuery($query)
    {
        return $query->where('toko_id', $this->tokoId());
    }
}