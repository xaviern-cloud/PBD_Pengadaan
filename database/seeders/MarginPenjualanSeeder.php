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
                'created_at'          => now(),
                'persen'              => 10.0, // Margin 10%
                'STATUS'              => 'A',
                'updated_at'          => now(),
                'id_user'             => 1, // Created by admin
            ],
            [
                'id_margin_penjualan' => 2,
                'created_at'          => now(),
                'persen'              => 15.0, // Margin 15%
                'STATUS'              => 'A',
                'updated_at'          => now(),
                'id_user'             => 1,
            ],
        ]);
    }
}
