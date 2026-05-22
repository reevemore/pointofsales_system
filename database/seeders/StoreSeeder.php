<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // Buat toko pertama
        $toko = Store::firstOrCreate(
            ['nama' => 'Toko Utama'],
            [
                'alamat'  => 'Jl. Contoh No. 1, Surabaya',
                'telepon' => '031-12345678',
                'email'   => 'tokoutama@posowi.com',
                'aktif'   => true,
            ]
        );

        // Buat admin untuk toko pertama
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Admin Toko Utama',
                'password' => Hash::make('admin123'),
                'toko_id'  => $toko->id,
            ]
        );

        // Pastikan toko_id admin sudah benar
        // (jika user sudah ada sebelumnya dari seeder lama)
        if ($admin->toko_id === null) {
            $admin->update(['toko_id' => $toko->id]);
        }

        $admin->syncRoles(['admin']);

        // Buat kasir contoh untuk toko pertama
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@admin.com'],
            [
                'name'     => 'Kasir Toko Utama',
                'password' => Hash::make('kasir123'),
                'toko_id'  => $toko->id,
            ]
        );

        $kasir->syncRoles(['kasir']);
    }
}