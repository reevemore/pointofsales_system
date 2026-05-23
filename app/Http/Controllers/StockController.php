<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasTokoScope;
use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StockController extends Controller
{
    use HasTokoScope;

    public function index()
    {
        session(['pageTitle' => 'Manajemen Stok', 'pageSubtitle' => 'Kelola stok produk']);

        $products = $this->scopedQuery(Product::query())
            ->with('category')
            ->latest()
            ->get();

        $lowStock = $products->filter(fn($p) => $p->stok <= 10)->values();

        return Inertia::render('Stock/Index', [
            'products' => $products,
            'lowStock' => $lowStock,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'type'        => 'required|in:in,out',
            'qty'         => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:500',
        ]);

        $product = Product::find($request->product_id);
        abort_if($product->toko_id !== $this->tokoId(), 403);

        DB::transaction(function () use ($request, $product) {
            // Update stok produk
            if ($request->type === 'in') {
                $product->increment('stok', $request->qty);
            } else {
                abort_if($product->stok < $request->qty, 422, 'Stok tidak mencukupi.');
                $product->decrement('stok', $request->qty);
            }

            // Catat mutasi stok
            $stokBefore = $product->stok;
            $stokAfter  = $request->type === 'in'
                ? $stokBefore + $request->qty
                : $stokBefore - $request->qty;

            StockMutation::create([
                'toko_id'     => $this->tokoId(),
                'product_id'  => $product->id,
                'user_id'     => auth()->id(),
                'type'        => $request->type,
                'qty'         => $request->qty,
                'stok_before' => $stokBefore,
                'stok_after'  => $stokAfter,
                'keterangan'  => $request->keterangan,
            ]);
        });

        return redirect()->route('stock.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

    public function history(Product $product)
    {
        abort_if($product->toko_id !== $this->tokoId(), 403);

        session(['pageTitle' => 'Riwayat Stok', 'pageSubtitle' => $product->nama]);

        $mutations = StockMutation::where('product_id', $product->id)
            ->where('toko_id', $this->tokoId())
            ->with('user')
            ->latest()
            ->get();

        return Inertia::render('Stock/History', [
            'product'   => $product,
            'mutations' => $mutations,
        ]);
    }
}