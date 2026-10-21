<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. View Barang
        DB::statement("
            CREATE OR REPLACE VIEW v_barang AS
            SELECT
                b.id_barang,
                b.jenis_barang,
                b.nama_barang,
                s.nama_satuan,
                b.harga,
                b.status
            FROM barang b
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
        ");

        // 2. View Satuan (Tambahan Terpisah)
        DB::statement("
            CREATE OR REPLACE VIEW v_satuan AS
            SELECT
                id_satuan,
                nama_satuan,
                status
            FROM satuan
        ");

        // 3. View Vendor
        DB::statement("
            CREATE OR REPLACE VIEW v_vendor AS
            SELECT
                id_vendor,
                nama_vendor,
                badan_hukum,
                status
            FROM vendor
        ");

        // 4. View User
        DB::statement("
            CREATE OR REPLACE VIEW v_user AS
            SELECT
                u.id_user,
                u.username,
                r.nama_role
            FROM user u
            LEFT JOIN role r ON u.id_role = r.id_role
        ");

        // 5. View Role (Tambahan Terpisah)
        DB::statement("
            CREATE OR REPLACE VIEW v_role AS
            SELECT
                id_role,
                nama_role
            FROM role
        ");

        // 6. View Margin Penjualan
        DB::statement("
            CREATE OR REPLACE VIEW v_margin_penjualan AS
            SELECT
                m.id_margin_penjualan,
                m.persen,
                m.status,
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
