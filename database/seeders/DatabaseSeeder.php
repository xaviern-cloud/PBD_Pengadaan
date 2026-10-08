<?php
    namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SatuanSeeder::class,
            VendorSeeder::class,
            MarginPenjualanSeeder::class,
            BarangSeeder::class,
        ]);
    }
}
