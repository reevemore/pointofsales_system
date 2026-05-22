<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasTokoScope;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    use HasTokoScope;

    public function index()
    {
        session(['pageTitle' => 'Produk', 'pageSubtitle' => 'Daftar produk']);

        $products = $this->scopedQuery(Product::query())
            ->with('category')
            ->latest()
            ->get();

        return Inertia::render('Product/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        session(['pageTitle' => 'Tambah Produk', 'pageSubtitle' => '']);

        $categories = $this->scopedQuery(Category::query())
            ->where('aktif', true)
            ->get(['id', 'nama']);

        return Inertia::render('Product/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'kode_produk' => 'required|string|max:50|unique:products,kode_produk',
            'nama'        => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|max:2048',
            'harga_beli'  => 'required|integer|min:0',
            'harga_jual'  => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
            'aktif'       => 'boolean',
        ]);

        // Pastikan category_id milik toko ini
        abort_if(
            !Category::where('id', $request->category_id)
                     ->where('toko_id', $this->tokoId())
                     ->exists(),
            403
        );

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('products', 'public');
        }

        Product::create([
            'toko_id'     => $this->tokoId(),
            'category_id' => $request->category_id,
            'kode_produk' => $request->kode_produk,
            'nama'        => $request->nama,
            'deskripsi'   => $request->deskripsi,
            'foto'        => $fotoPath,
            'harga_beli'  => $request->harga_beli,
            'harga_jual'  => $request->harga_jual,
            'stok'        => $request->stok,
            'satuan'      => $request->satuan,
            'aktif'       => $request->aktif ?? true,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        abort_if($product->toko_id !== $this->tokoId(), 403);

        session(['pageTitle' => 'Edit Produk', 'pageSubtitle' => $product->nama]);

        $categories = $this->scopedQuery(Category::query())
            ->where('aktif', true)
            ->get(['id', 'nama']);

        return Inertia::render('Product/Edit', [
            'product'    => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->toko_id !== $this->tokoId(), 403);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'kode_produk' => 'required|string|max:50|unique:products,kode_produk,' . $product->id,
            'nama'        => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|max:2048',
            'harga_beli'  => 'required|integer|min:0',
            'harga_jual'  => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
            'aktif'       => 'boolean',
        ]);

        abort_if(
            !Category::where('id', $request->category_id)
                     ->where('toko_id', $this->tokoId())
                     ->exists(),
            403
        );

        $fotoPath = $product->foto;
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }
            $fotoPath = $request->file('foto')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'kode_produk' => $request->kode_produk,
            'nama'        => $request->nama,
            'deskripsi'   => $request->deskripsi,
            'foto'        => $fotoPath,
            'harga_beli'  => $request->harga_beli,
            'harga_jual'  => $request->harga_jual,
            'stok'        => $request->stok,
            'satuan'      => $request->satuan,
            'aktif'       => $request->aktif ?? true,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        abort_if($product->toko_id !== $this->tokoId(), 403);

        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}