<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            -- 1. Role
            CREATE TABLE IF NOT EXISTS role (
                id_role INT AUTO_INCREMENT PRIMARY KEY,
                nama_role VARCHAR(100) NOT NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 2. User
            CREATE TABLE IF NOT EXISTS user (
                id_user INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(45) NOT NULL,
                password VARCHAR(100) NOT NULL,
                id_role INT NULL,
                CONSTRAINT fk_user_role FOREIGN KEY (id_role) REFERENCES role(id_role) ON DELETE SET NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 3. Satuan
            CREATE TABLE IF NOT EXISTS satuan (
                id_satuan INT AUTO_INCREMENT PRIMARY KEY,
                nama_satuan VARCHAR(45) NOT NULL,
                status TINYINT NOT NULL DEFAULT 1
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 4. Barang (Custom VARCHAR ID: BB-001, BSJ-001, dst.)
            CREATE TABLE IF NOT EXISTS barang (
                id_barang VARCHAR(20) PRIMARY KEY,
                jenis_barang VARCHAR(10) NOT NULL,
                nama_barang VARCHAR(100) NOT NULL,
                id_satuan INT NULL,
                status TINYINT NOT NULL DEFAULT 1,
                harga INT NOT NULL DEFAULT 0,
                CONSTRAINT fk_barang_satuan FOREIGN KEY (id_satuan) REFERENCES satuan(id_satuan) ON DELETE SET NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 5. Vendor
            CREATE TABLE IF NOT EXISTS vendor (
                id_vendor INT AUTO_INCREMENT PRIMARY KEY,
                nama_vendor VARCHAR(100) NOT NULL,
                badan_hukum VARCHAR(10) NULL,
                status CHAR(1) NOT NULL DEFAULT 'A'
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 6. Margin Penjualan
            CREATE TABLE IF NOT EXISTS margin_penjualan (
                id_margin_penjualan INT AUTO_INCREMENT PRIMARY KEY,
                persen DOUBLE NOT NULL,
                status TINYINT NOT NULL DEFAULT 1,
                id_user INT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_margin_user FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 7. Pengadaan
            CREATE TABLE IF NOT EXISTS pengadaan (
                id_pengadaan BIGINT AUTO_INCREMENT PRIMARY KEY,
                timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                id_user INT NULL,
                status CHAR(1) NOT NULL DEFAULT 'P',
                id_vendor INT NULL,
                subtotal INT NOT NULL DEFAULT 0,
                ppn INT NOT NULL DEFAULT 0,
                total_nilai INT NOT NULL DEFAULT 0,
                CONSTRAINT fk_pengadaan_user FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL,
                CONSTRAINT fk_pengadaan_vendor FOREIGN KEY (id_vendor) REFERENCES vendor(id_vendor) ON DELETE SET NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 8. Detail Pengadaan
            CREATE TABLE IF NOT EXISTS detail_pengadaan (
                id_detail_pengadaan BIGINT AUTO_INCREMENT PRIMARY KEY,
                harga_satuan INT NOT NULL,
                jumlah INT NOT NULL,
                sub_total INT NOT NULL,
                id_barang VARCHAR(20) NOT NULL,
                id_pengadaan BIGINT NOT NULL,
                CONSTRAINT fk_detail_pengadaan_barang FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE,
                CONSTRAINT fk_detail_pengadaan_parent FOREIGN KEY (id_pengadaan) REFERENCES pengadaan(id_pengadaan) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 9. Penerimaan
            CREATE TABLE IF NOT EXISTS penerimaan (
                id_penerimaan BIGINT AUTO_INCREMENT PRIMARY KEY,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                status CHAR(1) NOT NULL DEFAULT 'F',
                id_pengadaan BIGINT NOT NULL,
                id_user INT NULL,
                CONSTRAINT fk_penerimaan_pengadaan FOREIGN KEY (id_pengadaan) REFERENCES pengadaan(id_pengadaan) ON DELETE CASCADE,
                CONSTRAINT fk_penerimaan_user FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 10. Detail Penerimaan
            CREATE TABLE IF NOT EXISTS detail_penerimaan (
                id_detail_penerimaan BIGINT AUTO_INCREMENT PRIMARY KEY,
                id_penerimaan BIGINT NOT NULL,
                id_barang VARCHAR(20) NOT NULL,
                jumlah_terima INT NOT NULL,
                harga_satuan_terima INT NOT NULL,
                sub_total_terima INT NOT NULL,
                CONSTRAINT fk_detail_penerimaan_parent FOREIGN KEY (id_penerimaan) REFERENCES penerimaan(id_penerimaan) ON DELETE CASCADE,
                CONSTRAINT fk_detail_penerimaan_barang FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 11. Retur
            CREATE TABLE IF NOT EXISTS retur (
                id_retur BIGINT AUTO_INCREMENT PRIMARY KEY,
                dikembalikan_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                id_penerimaan BIGINT NOT NULL,
                id_user INT NULL,
                CONSTRAINT fk_retur_penerimaan FOREIGN KEY (id_penerimaan) REFERENCES penerimaan(id_penerimaan) ON DELETE CASCADE,
                CONSTRAINT fk_retur_user FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 12. Detail Retur
            CREATE TABLE IF NOT EXISTS detail_retur (
                id_detail_retur INT AUTO_INCREMENT PRIMARY KEY,
                jumlah INT NOT NULL,
                alasan VARCHAR(200) NULL,
                id_retur BIGINT NOT NULL,
                id_detail_penerimaan BIGINT NOT NULL,
                CONSTRAINT fk_detail_retur_parent FOREIGN KEY (id_retur) REFERENCES retur(id_retur) ON DELETE CASCADE,
                CONSTRAINT fk_detail_retur_penerimaan FOREIGN KEY (id_detail_penerimaan) REFERENCES detail_penerimaan(id_detail_penerimaan) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 13. Penjualan
            CREATE TABLE IF NOT EXISTS penjualan (
                id_penjualan INT AUTO_INCREMENT PRIMARY KEY,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                subtotal_nilai INT NOT NULL DEFAULT 0,
                ppn INT NOT NULL DEFAULT 0,
                total_nilai INT NOT NULL DEFAULT 0,
                id_user INT NULL,
                id_margin_penjualan INT NULL,
                CONSTRAINT fk_penjualan_user FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL,
                CONSTRAINT fk_penjualan_margin FOREIGN KEY (id_margin_penjualan) REFERENCES margin_penjualan(id_margin_penjualan) ON DELETE SET NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 14. Detail Penjualan
            CREATE TABLE IF NOT EXISTS detail_penjualan (
                id_detail_penjualan BIGINT AUTO_INCREMENT PRIMARY KEY,
                harga_satuan INT NOT NULL,
                jumlah INT NOT NULL,
                subtotal INT NOT NULL,
                id_penjualan INT NOT NULL,
                id_barang VARCHAR(20) NOT NULL,
                CONSTRAINT fk_detail_penjualan_parent FOREIGN KEY (id_penjualan) REFERENCES penjualan(id_penjualan) ON DELETE CASCADE,
                CONSTRAINT fk_detail_penjualan_barang FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 15. Kartu Stok
            CREATE TABLE IF NOT EXISTS kartu_stok (
                id_kartu_stok BIGINT AUTO_INCREMENT PRIMARY KEY,
                jenis_transaksi CHAR(1) NOT NULL,
                masuk INT NOT NULL DEFAULT 0,
                keluar INT NOT NULL DEFAULT 0,
                stock INT NOT NULL DEFAULT 0,
                update_terakhir TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                id_transaksi BIGINT NOT NULL,
                id_barang VARCHAR(20) NOT NULL,
                CONSTRAINT fk_kartu_stok_barang FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            -- 16. Tabel Pendukung: Counter Barang
            CREATE TABLE IF NOT EXISTS counter_barang (
                jenis_barang VARCHAR(10) PRIMARY KEY,
                counter_terakhir INT NOT NULL DEFAULT 0
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            INSERT IGNORE INTO counter_barang (jenis_barang, counter_terakhir) VALUES
            ('BB', 0), ('BSJ', 0), ('BJ', 0), ('BO', 0), ('BK', 0);

            -- 17. Tabel Pendukung: Log Audit Barang
            CREATE TABLE IF NOT EXISTS log_barang (
                id_log BIGINT AUTO_INCREMENT PRIMARY KEY,
                id_barang VARCHAR(20) NOT NULL,
                aksi VARCHAR(50) NOT NULL,
                keterangan TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TABLE IF EXISTS log_barang;
            DROP TABLE IF EXISTS counter_barang;
            DROP TABLE IF EXISTS kartu_stok;
            DROP TABLE IF EXISTS detail_penjualan;
            DROP TABLE IF EXISTS penjualan;
            DROP TABLE IF EXISTS detail_retur;
            DROP TABLE IF EXISTS retur;
            DROP TABLE IF EXISTS detail_penerimaan;
            DROP TABLE IF EXISTS penerimaan;
            DROP TABLE IF EXISTS detail_pengadaan;
            DROP TABLE IF EXISTS pengadaan;
            DROP TABLE IF EXISTS margin_penjualan;
            DROP TABLE IF EXISTS vendor;
            DROP TABLE IF EXISTS barang;
            DROP TABLE IF EXISTS satuan;
            DROP TABLE IF EXISTS user;
            DROP TABLE IF EXISTS role;
        ");
    }
};
