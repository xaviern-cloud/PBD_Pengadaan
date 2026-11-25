<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'id_user'  => 1,
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'id_role'  => 1, // Admin (Full Control)
            ],
            [
                'id_user'  => 2,
                'username' => 'kasir',
                'password' => Hash::make('kasir123'),
                'id_role'  => 2, // Kasir (POS Penjualan)
            ],
            [
                'id_user'  => 3,
                'username' => 'manager',
                'password' => Hash::make('manager123'),
                'id_role'  => 3, // Manager (Pengadaan, Penerimaan, Retur)
            ],
        ]);
    }
}
