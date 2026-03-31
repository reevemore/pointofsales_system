<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role
        $admin  = Role::firstOrCreate(['name' => 'admin']);
        $kasir  = Role::firstOrCreate(['name' => 'kasir']);

        // User rudy (yang sudah ada) jadi admin
        $user = User::where('email', 'rudy@example.com')->first();
        if ($user) {
            $user->assignRole('admin');
        }

        // Buat akun kasir contoh
        $kasirUser = User::firstOrCreate(
            ['email' => 'kasir@example.com'],
            [
                'name'     => 'Kasir 1',
                'password' => Hash::make('password'),
            ]
        );
        $kasirUser->assignRole('kasir');
    }
}