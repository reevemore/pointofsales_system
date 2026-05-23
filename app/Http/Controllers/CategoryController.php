<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasTokoScope;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    use HasTokoScope;

    public function index()
    {
        session(['pageTitle' => 'Kategori', 'pageSubtitle' => 'Daftar kategori produk']);

        $categories = $this->scopedQuery(Category::query())
            ->latest()
            ->get();

        return Inertia::render('Category/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        session(['pageTitle' => 'Tambah Kategori', 'pageSubtitle' => '']);

        return Inertia::render('Category/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'aktif'     => 'boolean',
        ]);

        Category::create([
            'toko_id'   => $this->tokoId(),
            'nama'      => $request->nama,
            'slug'      => Str::slug($request->nama) . '-' . uniqid(),
            'deskripsi' => $request->deskripsi,
            'aktif'     => $request->aktif ?? true,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        // Pastikan kategori milik toko ini
        abort_if($category->toko_id !== $this->tokoId(), 403);

        session(['pageTitle' => 'Edit Kategori', 'pageSubtitle' => $category->nama]);

        return Inertia::render('Category/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        abort_if($category->toko_id !== $this->tokoId(), 403);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'aktif'     => 'boolean',
        ]);

        $category->update([
            'nama'      => $request->nama,
            'slug'      => Str::slug($request->nama) . '-' . $category->id,
            'deskripsi' => $request->deskripsi,
            'aktif'     => $request->aktif ?? true,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        abort_if($category->toko_id !== $this->tokoId(), 403);

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}