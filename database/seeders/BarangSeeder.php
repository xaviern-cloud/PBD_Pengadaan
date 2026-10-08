<?php
    namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'id_barang'    => 1,
                'jenis_barang' => 'M', // M = Makanan / Minuman
                'nama_barang'  => 'Minyak Goreng 1L',
                'STATUS'       => 'A',
                'harga'        => 18500.00,
                'id_satuan'    => 4, // Liter
            ],
            [
                'id_barang'    => 2,
                'jenis_barang' => 'M',
                'nama_barang'  => 'Beras Super 5Kg',
                'STATUS'       => 'A',
                'harga'        => 68000.00,
                'id_satuan'    => 3, // Kg
            ],
            [
                'id_barang'    => 3,
                'jenis_barang' => 'B', // B = Barang Umum / ATK
                'nama_barang'  => 'Kertas A4 80gr',
                'STATUS'       => 'A',
                'harga'        => 45000.00,
                'id_satuan'    => 2, // Box
            ],
            [
                'id_barang'    => 4,
                'jenis_barang' => 'B',
                'nama_barang'  => 'Pulpen Gel Hitam',
                'STATUS'       => 'A',
                'harga'        => 3500.00,
                'id_satuan'    => 1, // Pcs
            ],
        ]);
    }
}
