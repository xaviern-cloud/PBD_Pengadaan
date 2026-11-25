<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vendor')->insert([
            ['id_vendor' => 1, 'nama_vendor' => 'Indofood Sukses Makmur', 'badan_hukum' => 'PT', 'status' => 'A'],
            ['id_vendor' => 2, 'nama_vendor' => 'Bumi Nusantara Sentosa', 'badan_hukum' => 'PT', 'status' => 'A'],
            ['id_vendor' => 3, 'nama_vendor' => 'Megah Jaya Logistics',   'badan_hukum' => 'PT', 'status' => 'N'],
            ['id_vendor' => 4, 'nama_vendor' => 'Sumber Rejeki Utama',    'badan_hukum' => 'CV', 'status' => 'A'],
            ['id_vendor' => 5, 'nama_vendor' => 'Lancar Jaya Paper',      'badan_hukum' => 'CV', 'status' => 'A'],
            ['id_vendor' => 6, 'nama_vendor' => 'Sembako Jaya',           'badan_hukum' => 'PD', 'status' => 'A'],
        ]);
    }
}
