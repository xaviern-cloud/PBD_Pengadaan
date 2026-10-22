<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('vendor')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('vendor')->insert([
            // ==========================================
            // PERSEROAN TERBATAS (PT)
            // ==========================================
            [
                'id_vendor'   => 1,
                'nama_vendor' => 'Indofood Sukses Makmur',
                'badan_hukum' => 'PT',
                'status'      => '1', // AKTIF
            ],
            [
                'id_vendor'   => 2,
                'nama_vendor' => 'Bumi Nusantara Sentosa',
                'badan_hukum' => 'PT',
                'status'      => '1', // AKTIF
            ],
            [
                'id_vendor'   => 3,
                'nama_vendor' => 'Megah Jaya Logistics',
                'badan_hukum' => 'PT',
                'status'      => '2', // NON-Aktif
            ],

            // ==========================================
            // COMMANDITAIRE VENNOOTSCHAP (CV)
            // ==========================================
            [
                'id_vendor'   => 4,
                'nama_vendor' => 'Sumber Rejeki Utama',
                'badan_hukum' => 'CV',
                'status'      => '1', // AKTIF
            ],
            [
                'id_vendor'   => 5,
                'nama_vendor' => 'Lancar Jaya Paper',
                'badan_hukum' => 'CV',
                'status'      => '1', // AKTIF
            ],
            [
                'id_vendor'   => 6,
                'nama_vendor' => 'Barokah Abadi Tehnik',
                'badan_hukum' => 'CV',
                'status'      => '2', // NON-Aktif
            ],

            // ==========================================
            // PERUSAHAAN DAGANG (PD)
            // ==========================================
            [
                'id_vendor'   => 7,
                'nama_vendor' => 'Sembako Jaya',
                'badan_hukum' => 'PD',
                'status'      => '1', // AKTIF
            ],
            [
                'id_vendor'   => 8,
                'nama_vendor' => 'Maju Bersama Plastik',
                'badan_hukum' => 'PD',
                'status'      => '2', // NON-Aktif
            ],

            // ==========================================
            // KOPERASI (K)
            // ==========================================
            [
                'id_vendor'   => 9,
                'nama_vendor' => 'Koperasi Serba Usaha Mandiri',
                'badan_hukum' => 'K',
                'status'      => '1', // AKTIF
            ],
            [
                'id_vendor'   => 10,
                'nama_vendor' => 'Koperasi Karyawan Sejahtera',
                'badan_hukum' => 'K',
                'status'      => '2', // NON-Aktif
            ],

            // ==========================================
            // FIRMA (Fa)
            // ==========================================
            [
                'id_vendor'   => 11,
                'nama_vendor' => 'Konsultan Mitra Bersama',
                'badan_hukum' => 'Fa',
                'status'      => '1', // AKTIF
            ],

            // ==========================================
            // PERORANGAN / NON BADAN HUKUM (Else Case)
            // ==========================================
            [
                'id_vendor'   => 12,
                'nama_vendor' => 'Toko Kelontong Pak Slamet',
                'badan_hukum' => 'PR', // Mengisi selain kode di atas
                'status'      => '1', // AKTIF
            ],
            [
                'id_vendor'   => 13,
                'nama_vendor' => 'Petani Sayur Organik Pak Budi',
                'badan_hukum' => 'PR',
                'status'      => '2', // NON-Aktif
            ],
        ]);
    }
}
