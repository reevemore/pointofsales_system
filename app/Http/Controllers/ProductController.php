<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return Inertia::render('Product/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Product/Create', [
            'categories' => Category::where('aktif', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama'        => 'required|string|max:255',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
            'aktif'       => 'boolean',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Product/Edit', [
            'product'    => $product->load('category'),
            'categories' => Category::where('aktif', true)->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama'        => 'required|string|max:255',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
            'aktif'       => 'boolean',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $this->uploadFoto($request->file('foto'));
        }

        // Hapus foto jika user klik "Hapus Foto"
        if ($request->input('hapus_foto') && $product->foto) {
            Storage::disk('public')->delete($product->foto);
            $data['foto'] = null;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Upload dan resize foto produk menggunakan Intervention Image.
     * Hasil: 600x600 jpg, disimpan di storage/app/public/products/
     */
    private function uploadFoto($file): string
    {
        $filename = 'products/' . uniqid('prd_') . '.jpg';

        $image = Image::read($file)
            ->cover(600, 600)          // crop & resize ke 600x600 (persegi)
            ->toJpeg(quality: 80);     // compress jadi JPEG 80%

        Storage::disk('public')->put($filename, $image);

        return $filename;
    }
}