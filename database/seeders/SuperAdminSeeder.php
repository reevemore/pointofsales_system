<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user superadmin — tidak punya toko_id
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@posowi.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'toko_id'  => null,
            ]
        );

        $superadmin->syncRoles(['superadmin']);
    }
}