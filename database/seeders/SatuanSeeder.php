<?php
    namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('satuan')->insert([
            ['id_satuan' => 1, 'nama_satuan' => 'Pcs',   'status' => 1],
            ['id_satuan' => 2, 'nama_satuan' => 'Box',   'status' => 1],
            ['id_satuan' => 3, 'nama_satuan' => 'Kg',    'status' => 1],
            ['id_satuan' => 4, 'nama_satuan' => 'Liter', 'status' => 1],
            ['id_satuan' => 5, 'nama_satuan' => 'Pack',  'status' => 0], // Non-Aktif
        ]);
    }
}
