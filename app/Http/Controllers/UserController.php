<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasTokoScope;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use HasTokoScope;

    public function index()
    {
        session(['pageTitle' => 'Users', 'pageSubtitle' => 'Manajemen pengguna toko']);

        // Admin hanya bisa lihat user di tokonya sendiri
        // Admin tidak bisa lihat superadmin
        $users = $this->scopedQuery(User::query())
            ->with('roles')
            ->get()
            ->map(fn($user) => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->getRoleNames()->first(),
            ]);

        // Role yang bisa diassign oleh admin toko (tidak termasuk superadmin)
        $roles = Role::whereIn('name', ['admin', 'kasir'])->get(['id', 'name']);

        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        session(['pageTitle' => 'Tambah User', 'pageSubtitle' => '']);

        $roles = Role::whereIn('name', ['admin', 'kasir'])->get(['id', 'name']);

        return Inertia::render('Users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => 'required|in:admin,kasir',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'toko_id'  => $this->tokoId(), // otomatis toko yang sedang login
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // Admin hanya bisa edit user di tokonya
        abort_if($user->toko_id !== $this->tokoId(), 403);

        session(['pageTitle' => 'Edit User', 'pageSubtitle' => $user->name]);

        $roles = Role::whereIn('name', ['admin', 'kasir'])->get(['id', 'name']);

        return Inertia::render('Users/Edit', [
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->getRoleNames()->first(),
            ],
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->toko_id !== $this->tokoId(), 403);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role'     => 'required|in:admin,kasir',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->toko_id !== $this->tokoId(), 403);

        // Jangan bisa hapus diri sendiri
        abort_if($user->id === auth()->id(), 403, 'Tidak bisa menghapus akun sendiri.');

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}