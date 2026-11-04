<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS counter_barang (
            jenis_barang VARCHAR(10) PRIMARY KEY,
            counter_terakhir INT NOT NULL default 0
            );

            INSERT IGNORE INTO counter_barang (jenis_barang, counter_terakhir) VALUES
            ('BB', 0), ('BSJ', 0), ('BJ', 0), ('BO', 0), ('BK', 0);
        ");

        DB::unprepared("
            CREATE TABLE IF NOT EXISTS log_barang (
            id_log BIGINT AUTO_INCREMENT PRIMARY KEY,
            id_barang VARCHAR(20) NOT NULL,
            aksi VARCHAR(50) NOT NULL,
            keterangan TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_before_insert_barang;
            CREATE TRIGGER trg_before_insert_barang
            BEFORE INSERT ON barang
            FOR EACH ROW
            BEGIN
                DECLARE v_next_counter INT DEFAULT 0;

                -- Pakai BINARY untuk mengabaikan bentrok collation MySQL
                SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(id_barang, '-', -1) AS UNSIGNED)), 0) + 1
                INTO v_next_counter
                FROM barang
                WHERE BINARY jenis_barang = BINARY NEW.jenis_barang;

                -- Update / Sync nilai ke tabel counter_barang
                INSERT INTO counter_barang (jenis_barang, counter_terakhir)
                VALUES (NEW.jenis_barang, v_next_counter)
                ON DUPLICATE KEY UPDATE counter_terakhir = v_next_counter;

                -- Format ID Barang (cth: BB-001)
                SET NEW.id_barang = CONCAT(NEW.jenis_barang, '-', LPAD(v_next_counter, 3, '0'));
            END;
        ");

        // 5. STORED PROCEDURE: Simpan Barang Baru via Procedure
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_tambah_barang;
            CREATE PROCEDURE sp_tambah_barang(
                IN p_jenis_barang VARCHAR(10),
                IN p_nama_barang VARCHAR(100),
                IN p_id_satuan INT,
                IN p_harga INT,
                OUT p_id_barang_baru VARCHAR(20)
            )
            BEGIN
                -- Trigger BEFORE INSERT akan otomatis aktif saat INSERT ini berjalan
                INSERT INTO barang (jenis_barang, nama_barang, id_satuan, harga, status)
                VALUES (p_jenis_barang, p_nama_barang, p_id_satuan, p_harga, 1);

                -- Ambil ID barang terbaru dengan pembandingan BINARY
                SELECT id_barang INTO p_id_barang_baru
                FROM barang
                WHERE BINARY jenis_barang = BINARY p_jenis_barang
                ORDER BY id_barang DESC
                LIMIT 1;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_tambah_barang;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_barang;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_before_insert_barang;");
        DB::unprepared("DROP TABLE IF EXISTS log_barang;");
        DB::unprepared("DROP TABLE IF EXISTS counter_barang;");
    }
};
