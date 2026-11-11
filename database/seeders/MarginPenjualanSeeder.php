<?php
    namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarginPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('margin_penjualan')->insert([
            [
                'id_margin_penjualan' => 1,
                'dibuat_pada'          => now(),
                'persen'              => 10.0, // Margin 10%
                'STATUS'              => 1,
                'id_user'             => 1, // Created by admin
            ],
            [
                'id_margin_penjualan' => 2,
                'dibuat_pada'          => now(),
                'persen'              => 15.0, // Margin 15%
                'STATUS'              => 1,
                'id_user'             => 1,
            ],
        ]);
    }
}
