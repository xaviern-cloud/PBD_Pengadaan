<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Tanpa memasukkan id_barang agar Trigger trg_before_insert_barang otomatis menghasilkan BB-001, BSJ-001, dst.
        DB::table('barang')->insert([
            ['jenis_barang' => 'BB',  'nama_barang' => 'Tepung Terigu 25kg',        'status' => 1, 'harga' => 210000, 'id_satuan' => 3],
            ['jenis_barang' => 'BB',  'nama_barang' => 'Gula Pasir Industri 50kg',  'status' => 1, 'harga' => 750000, 'id_satuan' => 3],
            ['jenis_barang' => 'BSJ', 'nama_barang' => 'Adonan Roti Beku',          'status' => 1, 'harga' => 15000,  'id_satuan' => 3],
            ['jenis_barang' => 'BJ',  'nama_barang' => 'Roti Tawar Premium',        'status' => 1, 'harga' => 18000,  'id_satuan' => 1],
            ['jenis_barang' => 'BK',  'nama_barang' => 'Minyak Goreng 1L',          'status' => 1, 'harga' => 18500,  'id_satuan' => 4],
            ['jenis_barang' => 'BK',  'nama_barang' => 'Beras Super 5Kg',           'status' => 1, 'harga' => 68000,  'id_satuan' => 3],
            ['jenis_barang' => 'BK',  'nama_barang' => 'Susu UHT 1L',               'status' => 1, 'harga' => 20000,  'id_satuan' => 4],
            ['jenis_barang' => 'BO',  'nama_barang' => 'Kertas A4 80gr',            'status' => 1, 'harga' => 45000,  'id_satuan' => 2],
            ['jenis_barang' => 'BO',  'nama_barang' => 'Pulpen Gel Hitam',          'status' => 1, 'harga' => 3500,   'id_satuan' => 1],
            ['jenis_barang' => 'BO',  'nama_barang' => 'Kertas Struk Thermal 57x47','status' => 0, 'harga' => 65000,  'id_satuan' => 2],
        ]);

        // Isi saldo stok awal (50 unit) di kartu_stok untuk barang yang Aktif agar POS Kasir langsung siap demo
        $barangs = DB::table('barang')->where('status', 1)->get();
        foreach ($barangs as $b) {
            DB::table('kartu_stok')->insert([
                'jenis_transaksi' => 'M',
                'masuk'           => 50,
                'keluar'          => 0,
                'stock'           => 50,
                'update_terakhir' => now(),
                'id_transaksi'    => 0,
                'id_barang'       => $b->id_barang,
            ]);
        }
    }
}
