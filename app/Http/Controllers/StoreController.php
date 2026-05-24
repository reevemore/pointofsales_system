<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    /**
     * Daftar semua toko
     */
    public function index(): Response
    {
        $stores = Store::withCount('users')
            ->with([
                'users' => fn($q) => $q->role('admin')->select('id', 'name', 'email', 'toko_id')
            ])
            ->latest()
            ->get()
            ->map(function ($store) {
                return [
                    'id'          => $store->id,
                    'nama'        => $store->nama,
                    'alamat'      => $store->alamat,
                    'telepon'     => $store->telepon,
                    'email'       => $store->email,
                    'aktif'       => $store->aktif,
                    'users_count' => $store->users_count,
                    'admin'       => $store->users->first() ? [
                        'id'    => $store->users->first()->id,
                        'name'  => $store->users->first()->name,
                        'email' => $store->users->first()->email,
                    ] : null,
                    'created_at'  => $store->created_at->format('d/m/Y'),
                ];
            });

        session(['pageTitle' => 'Manajemen Toko', 'pageSubtitle' => 'Daftar semua toko']);

        return Inertia::render('Stores/Index', [
            'stores' => $stores,
        ]);
    }

    /**
     * Form tambah toko
     */
    public function create(): Response
    {
        session(['pageTitle' => 'Tambah Toko', 'pageSubtitle' => 'Buat toko baru']);

        return Inertia::render('Stores/Create');
    }

    /**
     * Simpan toko baru + otomatis buat user admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'alamat'          => 'nullable|string|max:500',
            'telepon'         => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            // Data admin toko
            'admin_name'      => 'required|string|max:255',
            'admin_email'     => 'required|email|max:255|unique:users,email',
            'admin_password'  => ['required', 'confirmed', Password::min(8)],
        ], [
            'nama.required'           => 'Nama toko wajib diisi.',
            'admin_name.required'     => 'Nama admin toko wajib diisi.',
            'admin_email.required'    => 'Email admin toko wajib diisi.',
            'admin_email.unique'      => 'Email admin sudah digunakan.',
            'admin_password.required' => 'Password admin wajib diisi.',
            'admin_password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'admin_password.min'      => 'Password minimal 8 karakter.',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat toko
            $store = Store::create([
                'nama'    => $request->nama,
                'alamat'  => $request->alamat,
                'telepon' => $request->telepon,
                'email'   => $request->email,
                'aktif'   => true,
            ]);

            // 2. Buat user admin untuk toko ini
            $admin = User::create([
                'name'     => $request->admin_name,
                'email'    => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'toko_id'  => $store->id,
            ]);

            // 3. Assign role admin
            $admin->assignRole('admin');
        });

        return redirect()->route('stores.index')
            ->with('success', 'Toko berhasil dibuat beserta akun admin.');
    }

    /**
     * Form edit toko
     */
    public function edit(Store $store): Response
    {
        $admin = $store->users()->role('admin')->first();

        session(['pageTitle' => 'Edit Toko', 'pageSubtitle' => $store->nama]);

        return Inertia::render('Stores/Edit', [
            'store' => [
                'id'      => $store->id,
                'nama'    => $store->nama,
                'alamat'  => $store->alamat,
                'telepon' => $store->telepon,
                'email'   => $store->email,
                'aktif'   => $store->aktif,
            ],
            'admin' => $admin ? [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
            ] : null,
        ]);
    }

    /**
     * Update data toko + (opsional) update data admin
     */
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'alamat'  => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            // Update admin opsional
            'admin_name'     => 'nullable|string|max:255',
            'admin_email'    => 'nullable|email|max:255|unique:users,email,' . ($store->users()->role('admin')->first()?->id),
            'admin_password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'nama.required'        => 'Nama toko wajib diisi.',
            'admin_email.unique'   => 'Email admin sudah digunakan.',
            'admin_password.min'   => 'Password minimal 8 karakter.',
            'admin_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($request, $store) {
            // Update data toko
            $store->update([
                'nama'    => $request->nama,
                'alamat'  => $request->alamat,
                'telepon' => $request->telepon,
                'email'   => $request->email,
            ]);

            // Update data admin jika diisi
            $admin = $store->users()->role('admin')->first();
            if ($admin) {
                $adminData = [];

                if ($request->filled('admin_name')) {
                    $adminData['name'] = $request->admin_name;
                }
                if ($request->filled('admin_email')) {
                    $adminData['email'] = $request->admin_email;
                }
                if ($request->filled('admin_password')) {
                    $adminData['password'] = Hash::make($request->admin_password);
                }

                if (!empty($adminData)) {
                    $admin->update($adminData);
                }
            }
        });

        return redirect()->route('stores.index')
            ->with('success', 'Data toko berhasil diperbarui.');
    }

    /**
     * Hapus toko beserta semua user yang terhubung
     */
    public function destroy(Store $store)
    {
        DB::transaction(function () use ($store) {
            // Hapus semua user yang terhubung ke toko ini
            $store->users()->delete();

            // Hapus toko
            $store->delete();
        });

        return redirect()->route('stores.index')
            ->with('success', "Toko {$store->nama} beserta semua user-nya berhasil dihapus.");
    }

    /**
     * Toggle aktif / nonaktif toko
     */
    public function toggle(Store $store)
    {
        $store->update(['aktif' => !$store->aktif]);

        $status = $store->aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('stores.index')
            ->with('success', "Toko {$store->nama} berhasil {$status}.");
    }
}