<?php
    namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vendor')->insert([
            [
                'id_vendor'   => 1,
                'nama_vendor' => 'PT Sumber Makmur Jaya',
                'badan_hukum' => 'Y', // Y = Ya (Berbadan Hukum PT/CV)
                'STATUS'      => 'A', // A = Aktif
            ],
            [
                'id_vendor'   => 2,
                'nama_vendor' => 'CV Distribusi Utama',
                'badan_hukum' => 'Y',
                'STATUS'      => 'A',
            ],
            [
                'id_vendor'   => 3,
                'nama_vendor' => 'Toko Grosir Sejahtera',
                'badan_hukum' => 'N', // N = Tidak
                'STATUS'      => 'A',
            ],
        ]);
    }
}
