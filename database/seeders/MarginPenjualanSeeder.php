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
                'persen'              => 15.0,
                'status'              => 1,
                'id_user'             => 1,
                'created_at'          => now(),
            ],
            [
                'id_margin_penjualan' => 2,
                'persen'              => 25.0,
                'status'              => 1,
                'id_user'             => 1,
                'created_at'          => now(),
            ],
            [
                'id_margin_penjualan' => 3,
                'persen'              => 10.0,
                'status'              => 0, // Non-Aktif untuk demo filter
                'id_user'             => 1,
                'created_at'          => now(),
            ],
        ]);
    }
}
