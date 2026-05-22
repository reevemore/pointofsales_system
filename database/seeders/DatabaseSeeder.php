<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,      // 1. Buat role dulu
            SuperAdminSeeder::class, // 2. Buat superadmin (tidak butuh toko)
            StoreSeeder::class,      // 3. Buat toko + admin toko
        ]);
    }
}