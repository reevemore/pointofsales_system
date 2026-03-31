<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index()
    {
        return Inertia::render('Stock/Index', [
            'products' => Product::where('aktif', true)
    ->orderBy('nama')
    ->get(['id', 'nama', 'stok']),
'lowStock' => Product::where('aktif', true)
    ->where('stok', '<=', 5)
    ->get(['id', 'nama', 'stok']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'qty'         => 'required|integer|min:1',
            'type'        => 'required|in:in,out',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->type === 'out' && $product->stok < $request->qty) {
            return back()->withErrors(['qty' => 'Stok tidak cukup untuk dikurangi!']);
        }

        $stokBefore = $product->stok;
        $stokAfter  = $request->type === 'in'
            ? $stokBefore + $request->qty
            : $stokBefore - $request->qty;

        $product->update(['stok' => $stokAfter]);

        StockMutation::create([
            'product_id'  => $product->id,
            'user_id'     => Auth::id(),
            'type'        => $request->type,
            'qty'         => $request->qty,
            'stok_before' => $stokBefore,
            'stok_after'  => $stokAfter,
            'keterangan'  => $request->keterangan,
        ]);

        return back()->with('success', 'Stok berhasil diupdate!');
    }

    public function history(Product $product)
    {
        return Inertia::render('Stock/History', [
            'product'   => $product,
            'mutations' => StockMutation::with('user')
                ->where('product_id', $product->id)
                ->latest()
                ->paginate(20),
        ]);
    }
}