<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /* =============================================================
         * 1. TABEL PENDUKUNG & ROUTINE MASTER BARANG
         * ============================================================= */

        // Tabel Counter Barang
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS counter_barang (
                jenis_barang VARCHAR(10) PRIMARY KEY,
                counter_terakhir INT NOT NULL DEFAULT 0
            );

            INSERT IGNORE INTO counter_barang (jenis_barang, counter_terakhir) VALUES
            ('BB', 0), ('BSJ', 0), ('BJ', 0), ('BO', 0), ('BK', 0);
        ");

        // Tabel Log Barang
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS log_barang (
                id_log BIGINT AUTO_INCREMENT PRIMARY KEY,
                id_barang VARCHAR(20) NOT NULL,
                aksi VARCHAR(50) NOT NULL,
                keterangan TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Trigger Before Insert Barang (Custom Auto-ID)
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_before_insert_barang;
            CREATE TRIGGER trg_before_insert_barang
            BEFORE INSERT ON barang
            FOR EACH ROW
            BEGIN
                DECLARE v_next_counter INT DEFAULT 0;

                SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(id_barang, '-', -1) AS UNSIGNED)), 0) + 1
                INTO v_next_counter
                FROM barang
                WHERE BINARY jenis_barang = BINARY NEW.jenis_barang;

                INSERT INTO counter_barang (jenis_barang, counter_terakhir)
                VALUES (NEW.jenis_barang, v_next_counter)
                ON DUPLICATE KEY UPDATE counter_terakhir = v_next_counter;

                SET NEW.id_barang = CONCAT(NEW.jenis_barang, '-', LPAD(v_next_counter, 3, '0'));
            END;
        ");

        // Trigger After Insert Barang (Audit Log)
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_after_insert_barang;
            CREATE TRIGGER trg_after_insert_barang
            AFTER INSERT ON barang
            FOR EACH ROW
            BEGIN
                INSERT INTO log_barang (id_barang, aksi, keterangan, created_at)
                VALUES (
                    NEW.id_barang,
                    'INSERT',
                    CONCAT('Tambah barang baru: ', NEW.nama_barang, ' (Jenis: ', NEW.jenis_barang, ')'),
                    NOW()
                );
            END;
        ");

        // Procedure Tambah Barang
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
                INSERT INTO barang (jenis_barang, nama_barang, id_satuan, harga, status)
                VALUES (p_jenis_barang, p_nama_barang, p_id_satuan, p_harga, 1);

                SELECT id_barang INTO p_id_barang_baru
                FROM barang
                WHERE BINARY jenis_barang = BINARY p_jenis_barang
                ORDER BY id_barang DESC
                LIMIT 1;
            END;
        ");

        /* =============================================================
         * 2. STORED FUNCTIONS TRANSAKSI
         * ============================================================= */

        // Function: Hitung Harga Jual Berdasarkan Margin Penjualan
        DB::unprepared("
            DROP FUNCTION IF EXISTS get_harga_jual;
            CREATE FUNCTION get_harga_jual(
                p_id_barang VARCHAR(20),
                p_id_margin INT
            ) RETURNS INT
            DETERMINISTIC
            BEGIN
                DECLARE v_harga_modal INT DEFAULT 0;
                DECLARE v_margin DOUBLE DEFAULT 0;
                DECLARE v_harga_jual INT DEFAULT 0;

                SELECT harga INTO v_harga_modal
                FROM barang
                WHERE id_barang = p_id_barang
                LIMIT 1;

                IF v_harga_modal IS NULL THEN
                    RETURN 0;
                END IF;

                SELECT persen INTO v_margin
                FROM margin_penjualan
                WHERE id_margin_penjualan = p_id_margin
                  AND status = 1
                LIMIT 1;

                IF v_margin IS NULL THEN
                    SET v_margin = 0;
                END IF;

                SET v_harga_jual = v_harga_modal + (v_harga_modal * v_margin / 100);
                RETURN v_harga_jual;
            END;
        ");

        // Function: Cek Apakah Seluruh Item Pengadaan Sudah Diterima
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_pengadaan_selesai;
            CREATE FUNCTION fn_pengadaan_selesai(p_id_pengadaan BIGINT)
            RETURNS TINYINT
            DETERMINISTIC
            BEGIN
                DECLARE v_remaining INT;

                SELECT COUNT(*)
                INTO v_remaining
                FROM detail_pengadaan dp
                LEFT JOIN (
                    SELECT pen.id_pengadaan, dpr.id_barang, SUM(dpr.jumlah_terima) AS total_terima
                    FROM detail_penerimaan dpr
                    JOIN penerimaan pen ON pen.id_penerimaan = dpr.id_penerimaan
                    GROUP BY pen.id_pengadaan, dpr.id_barang
                ) AS terima ON terima.id_pengadaan = dp.id_pengadaan AND terima.id_barang = dp.id_barang
                WHERE dp.id_pengadaan = p_id_pengadaan
                  AND COALESCE(terima.total_terima, 0) < dp.jumlah;

                RETURN (v_remaining = 0);
            END;
        ");

        /* =============================================================
         * 3. TRIGGERS TRANSAKSI & KARTU STOK
         * ============================================================= */

        // Trigger: Otomatis Tambah Stok Masuk saat Detail Penerimaan Diinsert
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_insert_detail_penerimaan;
            CREATE TRIGGER after_insert_detail_penerimaan
            AFTER INSERT ON detail_penerimaan
            FOR EACH ROW
            BEGIN
                DECLARE last_stock INT DEFAULT 0;

                SELECT COALESCE(stock, 0) INTO last_stock
                FROM kartu_stok
                WHERE id_barang = NEW.id_barang
                ORDER BY update_terakhir DESC, id_kartu_stok DESC
                LIMIT 1;

                INSERT INTO kartu_stok (
                    jenis_transaksi, masuk, keluar, stock, update_terakhir, id_transaksi, id_barang
                ) VALUES (
                    'M', NEW.jumlah_terima, 0, last_stock + NEW.jumlah_terima, NOW(), NEW.id_penerimaan, NEW.id_barang
                );
            END;
        ");

        // Trigger: Otomatis Tambah Stok Keluar saat Detail Penjualan Diinsert
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_insert_detail_penjualan;
            CREATE TRIGGER after_insert_detail_penjualan
            AFTER INSERT ON detail_penjualan
            FOR EACH ROW
            BEGIN
                DECLARE last_stock INT DEFAULT 0;

                SELECT COALESCE(stock, 0) INTO last_stock
                FROM kartu_stok
                WHERE id_barang = NEW.id_barang
                ORDER BY update_terakhir DESC, id_kartu_stok DESC
                LIMIT 1;

                INSERT INTO kartu_stok (
                    jenis_transaksi, masuk, keluar, stock, update_terakhir, id_transaksi, id_barang
                ) VALUES (
                    'K', 0, NEW.jumlah, last_stock - NEW.jumlah, NOW(), NEW.id_penjualan, NEW.id_barang
                );
            END;
        ");

        // Trigger: Proteksi Pengadaan yang Sudah Final Agar Nilai Tidak Bisa Diubah
        DB::unprepared("
            DROP TRIGGER IF EXISTS before_update_pengadaan;
            CREATE TRIGGER before_update_pengadaan
            BEFORE UPDATE ON pengadaan
            FOR EACH ROW
            BEGIN
                IF OLD.status = 'F' THEN
                    IF (NEW.id_vendor <> OLD.id_vendor)
                        OR (NEW.subtotal <> OLD.subtotal)
                        OR (NEW.total_nilai <> OLD.total_nilai)
                        OR (NEW.ppn <> OLD.ppn) THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Pengadaan sudah final, data barang dan nilai tidak boleh diubah.';
                    END IF;
                END IF;
            END;
        ");

        /* =============================================================
         * 4. STORED PROCEDURES TRANSAKSI
         * ============================================================= */

        // Procedure: Finalisasi Status Pengadaan
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_finalize_pengadaan;
            CREATE PROCEDURE sp_finalize_pengadaan(IN p_id_pengadaan BIGINT)
            BEGIN
                UPDATE pengadaan
                SET status = 'F'
                WHERE id_pengadaan = p_id_pengadaan;
            END;
        ");

        /* =============================================================
         * 5. RETUR & VENDOR MUTATION
         * ============================================================= */

        // Trigger: Otomatis Tambah Kartu Stok Keluar saat Retur ke Vendor
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_insert_detail_retur;
            CREATE TRIGGER after_insert_detail_retur
            AFTER INSERT ON detail_retur
            FOR EACH ROW
            BEGIN
                DECLARE last_stock INT DEFAULT 0;
                DECLARE v_id_barang VARCHAR(20);

                -- Ambil id_barang dari detail_penerimaan terkait
                SELECT id_barang INTO v_id_barang
                FROM detail_penerimaan
                WHERE id_detail_penerimaan = NEW.id_detail_penerimaan
                LIMIT 1;

                -- Ambil stok terakhir
                SELECT COALESCE(stock, 0) INTO last_stock
                FROM kartu_stok
                WHERE id_barang = v_id_barang
                ORDER BY update_terakhir DESC, id_kartu_stok DESC
                LIMIT 1;

                -- Catat mutasi keluar ('R' = Retur ke Vendor)
                INSERT INTO kartu_stok (
                    jenis_transaksi, masuk, keluar, stock, update_terakhir, id_transaksi, id_barang
                ) VALUES (
                    'R', 0, NEW.jumlah, last_stock - NEW.jumlah, NOW(), NEW.id_retur, v_id_barang
                );
            END;
        ");

        // Stored Procedure: Simpan Transaksi Retur Penerimaan
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_insert_retur;
            CREATE PROCEDURE sp_insert_retur(
                IN p_id_penerimaan BIGINT,
                IN p_id_user INT,
                IN p_id_detail_penerimaan BIGINT,
                IN p_jumlah INT,
                IN p_alasan VARCHAR(200)
            )
            BEGIN
                DECLARE v_id_retur BIGINT;

                START TRANSACTION;

                -- 1. Insert Header Retur
                INSERT INTO retur (dikembalikan_pada, id_penerimaan, id_user)
                VALUES (NOW(), p_id_penerimaan, p_id_user);

                SET v_id_retur = LAST_INSERT_ID();

                -- 2. Insert Detail Retur
                INSERT INTO detail_retur (jumlah, alasan, id_retur, id_detail_penerimaan)
                VALUES (p_jumlah, p_alasan, v_id_retur, p_id_detail_penerimaan);

                COMMIT;
            END;
        ");

        // SQL VIEW: View Laporan Kartu Stok
        DB::unprepared("
            CREATE OR REPLACE VIEW vw_kartu_stok AS
            SELECT
                ks.id_kartu_stok,
                ks.update_terakhir AS tanggal,
                b.id_barang,
                b.nama_barang,
                s.nama_satuan,
                CASE
                    WHEN ks.jenis_transaksi = 'M' THEN 'Penerimaan (Masuk)'
                    WHEN ks.jenis_transaksi = 'K' THEN 'Penjualan (Keluar)'
                    WHEN ks.jenis_transaksi = 'R' THEN 'Retur Vendor (Keluar)'
                    ELSE 'Lainnya'
                END AS jenis_transaksi,
                ks.masuk,
                ks.keluar,
                ks.stock AS stok_akhir,
                ks.id_transaksi AS ref_id_transaksi
            FROM kartu_stok ks
            JOIN barang b ON ks.id_barang = b.id_barang
            LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
            ORDER BY ks.update_terakhir DESC, ks.id_kartu_stok DESC;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS vw_kartu_stok;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_insert_retur;");
        DB::unprepared("DROP TRIGGER IF EXISTS after_insert_detail_retur;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_finalize_pengadaan;");
        DB::unprepared("DROP TRIGGER IF EXISTS before_update_pengadaan;");
        DB::unprepared("DROP TRIGGER IF EXISTS after_insert_detail_penjualan;");
        DB::unprepared("DROP TRIGGER IF EXISTS after_insert_detail_penerimaan;");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_pengadaan_selesai;");
        DB::unprepared("DROP FUNCTION IF EXISTS get_harga_jual;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_tambah_barang;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_barang;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_before_insert_barang;");
        DB::unprepared("DROP TABLE IF EXISTS log_barang;");
        DB::unprepared("DROP TABLE IF EXISTS counter_barang;");
    }
};
