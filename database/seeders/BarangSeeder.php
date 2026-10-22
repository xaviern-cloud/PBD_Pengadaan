<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Kosongkan isi tabel barang dan reset auto-increment ID
        DB::table('barang')->truncate();

        // Aktifkan kembali proteksi Foreign Key
        Schema::enableForeignKeyConstraints();
        DB::table('barang')->insert([
            // ==========================================
            // BAHAN BAKU (BB) - Pengadaan untuk bahan olahan/produksi
            // ==========================================
            [
                'id_barang'    => 1,
                'jenis_barang' => 'BB',
                'nama_barang'  => 'Tepung Terigu 25kg',
                'STATUS'       => '1',
                'harga'        => 210000.00,
                'id_satuan'    => 3, // Kg / Bag
            ],
            [
                'id_barang'    => 2,
                'jenis_barang' => 'BB',
                'nama_barang'  => 'Gula Pasir Industri 50kg',
                'STATUS'       => '1',
                'harga'        => 750000.00,
                'id_satuan'    => 3, // Kg
            ],

            // ==========================================
            // BARANG SETENGAH JADI (BSJ) - Dalam proses olahan
            // ==========================================
            [
                'id_barang'    => 3,
                'jenis_barang' => 'BSJ',
                'nama_barang'  => 'Adonan Roti Beku',
                'STATUS'       => '1',
                'harga'        => 15000.00,
                'id_satuan'    => 3, // Kg
            ],

            // ==========================================
            // BARANG JADI (BJ) - Hasil produksi internal yang siap dijual
            // ==========================================
            [
                'id_barang'    => 4,
                'jenis_barang' => 'BJ',
                'nama_barang'  => 'Roti Tawar Premium',
                'STATUS'       => '1',
                'harga'        => 18000.00,
                'id_satuan'    => 1, // Pcs
            ],

            // ==========================================
            // BARANG KONSUMEN (BK) - Barang dagangan langsung dari supplier
            // ==========================================
            [
                'id_barang'    => 5,
                'jenis_barang' => 'BK',
                'nama_barang'  => 'Minyak Goreng 1L',
                'STATUS'       => '1',
                'harga'        => 18500.00,
                'id_satuan'    => 4, // Liter
            ],
            [
                'id_barang'    => 6,
                'jenis_barang' => 'BK',
                'nama_barang'  => 'Beras Super 5Kg',
                'STATUS'       => '1',
                'harga'        => 68000.00,
                'id_satuan'    => 3, // Kg
            ],
            [
                'id_barang'    => 7,
                'jenis_barang' => 'BK',
                'nama_barang'  => 'Susu UHT 1L',
                'STATUS'       => '1',
                'harga'        => 20000.00,
                'id_satuan'    => 4, // Liter
            ],

            // ==========================================
            // BARANG OPERASIONAL (BO) - Inventaris & ATK Toko
            // ==========================================
            [
                'id_barang'    => 8,
                'jenis_barang' => 'BO',
                'nama_barang'  => 'Kertas A4 80gr',
                'STATUS'       => '1',
                'harga'        => 45000.00,
                'id_satuan'    => 2, // Box
            ],
            [
                'id_barang'    => 9,
                'jenis_barang' => 'BO',
                'nama_barang'  => 'Pulpen Gel Hitam',
                'STATUS'       => '1',
                'harga'        => 3500.00,
                'id_satuan'    => 1, // Pcs
            ],
            [
                'id_barang'    => 10,
                'jenis_barang' => 'BO',
                'nama_barang'  => 'Kertas Struk Thermal 57x47',
                'STATUS'       => '1',
                'harga'        => 65000.00,
                'id_satuan'    => 2, // Box
            ],
        ]);
    }
}
