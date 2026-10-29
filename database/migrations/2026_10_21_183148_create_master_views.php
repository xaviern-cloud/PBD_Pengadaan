<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_barang AS
            SELECT
                b.id_barang,
                CASE
                    WHEN b.jenis_barang = 'BB'  THEN 'Bahan Baku'
                    WHEN b.jenis_barang = 'BSJ' THEN 'Barang Setengah Jadi'
                    WHEN b.jenis_barang = 'BJ'  THEN 'Barang Jadi'
                    WHEN b.jenis_barang = 'BO'  THEN 'Barang Operasional'
                    WHEN b.jenis_barang = 'BK'  THEN 'Barang Konsumen'
                    else 'Belum di Kategorikan'
                END as jenis_barang,
                b.nama_barang,
                s.nama_satuan,
                b.harga,
                CASE
                    WHEN b.status ='1' THEN 'Aktif'
                    WHEN b.status ='2' THEN 'Non-Aktif'
                    else 'Status Belum Diberikan'
                END as Status
            FROM barang b
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_satuan AS
            SELECT
                id_satuan,
                nama_satuan,
                CASE
                    WHEN status ='1' THEN 'AKTIF'
                    WHEN status ='2' THEN 'NON-AKTIF'
                    else 'Status Belum Diberikan'
                END as Status
            FROM satuan
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_vendor AS
            SELECT
                id_vendor,
                nama_vendor,
                CASE
                    WHEN badan_hukum ='PT'  THEN 'Perseroan Terbatas (PT)'
                    WHEN badan_hukum ='CV'  THEN 'Commanditaire Vennootschap (CV)'
                    WHEN badan_hukum ='PD'  THEN 'Perusahaan Dagang (PD)'
                    WHEN badan_hukum ='K'   THEN 'Koperasi'
                    WHEN badan_hukum ='Fa'  THEN 'Firma'
                    else 'Perorangan/Non Badan Hukum'
                END as badan_hukum,
                CASE
                    WHEN status ='1' THEN 'AKTIF'
                    WHEN status ='2' THEN 'NON-AKTIF'
                    else 'Status Belum Diberikan'
                END as Status
            FROM vendor
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_user AS
            SELECT
                u.id_user,
                u.username,
                r.nama_role
            FROM user u
            LEFT JOIN role r ON u.id_role = r.id_role
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_role AS
            SELECT
                id_role,
                nama_role
            FROM role
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_margin_penjualan AS
            SELECT
                m.id_margin_penjualan,
                m.persen,
                CASE
                    WHEN status ='1' THEN 'AKTIF'
                    WHEN status ='2' THEN 'NON-Aktif'
                    else 'Status Belum Diberikan'
                END as Status,
                u.username AS dibuat_oleh,
                m.created_at,
                m.updated_at
            FROM margin_penjualan m
            LEFT JOIN user u ON m.id_user = u.id_user
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_barang");
        DB::statement("DROP VIEW IF EXISTS v_satuan");
        DB::statement("DROP VIEW IF EXISTS v_vendor");
        DB::statement("DROP VIEW IF EXISTS v_user");
        DB::statement("DROP VIEW IF EXISTS v_role");
        DB::statement("DROP VIEW IF EXISTS v_margin_penjualan");
    }
};
