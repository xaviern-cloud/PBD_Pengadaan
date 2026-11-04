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

        // Kosongkan isi tabel barang
        DB::table('barang')->truncate();

        Schema::enableForeignKeyConstraints();

        // KITA TIDAK MASUKKAN 'id_barang' SAMA SEKALI!
        // Trigger BEFORE INSERT di MySQL yang akan otomatis mengisi BB-001, BB-002, BSJ-001, dll.
        DB::table('barang')->insert([
            // ==========================================
            // BAHAN BAKU (BB) -> Otomatis jadi BB-001, BB-002
            // ==========================================
            [
                'jenis_barang' => 'BB',
                'nama_barang'  => 'Tepung Terigu 25kg',
                'status'       => 1,
                'harga'        => 210000.00,
                'id_satuan'    => 3,
            ],
            [
                'jenis_barang' => 'BB',
                'nama_barang'  => 'Gula Pasir Industri 50kg',
                'status'       => 1,
                'harga'        => 750000.00,
                'id_satuan'    => 3,
            ],

            // ==========================================
            // BARANG SETENGAH JADI (BSJ) -> Otomatis jadi BSJ-001
            // ==========================================
            [
                'jenis_barang' => 'BSJ',
                'nama_barang'  => 'Adonan Roti Beku',
                'status'       => 1,
                'harga'        => 15000.00,
                'id_satuan'    => 3,
            ],

            // ==========================================
            // BARANG JADI (BJ) -> Otomatis jadi BJ-001
            // ==========================================
            [
                'jenis_barang' => 'BJ',
                'nama_barang'  => 'Roti Tawar Premium',
                'status'       => 1,
                'harga'        => 18000.00,
                'id_satuan'    => 1,
            ],

            // ==========================================
            // BARANG KONSUMEN (BK) -> Otomatis jadi BK-001, BK-002, BK-003
            // ==========================================
            [
                'jenis_barang' => 'BK',
                'nama_barang'  => 'Minyak Goreng 1L',
                'status'       => 1,
                'harga'        => 18500.00,
                'id_satuan'    => 4,
            ],
            [
                'jenis_barang' => 'BK',
                'nama_barang'  => 'Beras Super 5Kg',
                'status'       => 1,
                'harga'        => 68000.00,
                'id_satuan'    => 3,
            ],
            [
                'jenis_barang' => 'BK',
                'nama_barang'  => 'Susu UHT 1L',
                'status'       => 1,
                'harga'        => 20000.00,
                'id_satuan'    => 4,
            ],

            // ==========================================
            // BARANG OPERASIONAL (BO) -> Otomatis jadi BO-001, BO-002, BO-003
            // ==========================================
            [
                'jenis_barang' => 'BO',
                'nama_barang'  => 'Kertas A4 80gr',
                'status'       => 1,
                'harga'        => 45000.00,
                'id_satuan'    => 2,
            ],
            [
                'jenis_barang' => 'BO',
                'nama_barang'  => 'Pulpen Gel Hitam',
                'status'       => 1,
                'harga'        => 3500.00,
                'id_satuan'    => 1,
            ],
            [
                'jenis_barang' => 'BO',
                'nama_barang'  => 'Kertas Struk Thermal 57x47',
                'status'       => 1,
                'harga'        => 65000.00,
                'id_satuan'    => 2,
            ],
        ]);
    }
}
