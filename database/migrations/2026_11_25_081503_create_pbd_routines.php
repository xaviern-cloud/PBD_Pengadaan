<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /* =====================================================================
         * 1. TABEL PENDUKUNG MASTER BARANG (COUNTER & AUDIT LOG)
         * ===================================================================== */
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS counter_barang (
                jenis_barang VARCHAR(10) PRIMARY KEY,
                counter_terakhir INT NOT NULL DEFAULT 0
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

            INSERT IGNORE INTO counter_barang (jenis_barang, counter_terakhir) VALUES
            ('BB', 0), ('BSJ', 0), ('BJ', 0), ('BO', 0), ('BK', 0);

            CREATE TABLE IF NOT EXISTS log_barang (
                id_log BIGINT AUTO_INCREMENT PRIMARY KEY,
                id_barang VARCHAR(20) NOT NULL,
                aksi VARCHAR(50) NOT NULL,
                keterangan TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        ");

        /* =====================================================================
         * 2. STORED FUNCTIONS (4 FUNCTION: KALKULASI & VALIDATOR TANPA SQLSTATE)
         * ===================================================================== */

        // Function 1: Hitung PPN 11%
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_calculate_ppn;
            CREATE FUNCTION fn_calculate_ppn(p_subtotal INT)
            RETURNS INT
            DETERMINISTIC
            BEGIN
                RETURN ROUND(COALESCE(p_subtotal, 0) * 0.11);
            END;
        ");

        // Function 2: Hitung Harga Jual Otomatis (Harga Modal + Margin %)
        DB::unprepared("
            DROP FUNCTION IF EXISTS get_harga_jual;
            CREATE FUNCTION get_harga_jual(
                p_id_barang VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                p_id_margin INT
            ) RETURNS INT
            DETERMINISTIC
            BEGIN
                DECLARE v_harga_modal INT DEFAULT 0;
                DECLARE v_margin DOUBLE DEFAULT 0;

                SELECT harga INTO v_harga_modal
                FROM barang
                WHERE id_barang = p_id_barang
                LIMIT 1;

                SELECT persen INTO v_margin
                FROM margin_penjualan
                WHERE id_margin_penjualan = p_id_margin AND status = 1
                LIMIT 1;

                RETURN ROUND(COALESCE(v_harga_modal, 0) + (COALESCE(v_harga_modal, 0) * COALESCE(v_margin, 0) / 100));
            END;
        ");

        // Function 3: Cek Sisa Stok Terakhir dari Kartu Stok
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_cek_stok;
            CREATE FUNCTION fn_cek_stok(
                p_id_barang VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            ) RETURNS INT
            DETERMINISTIC
            BEGIN
                DECLARE v_stok INT DEFAULT 0;

                SELECT stock INTO v_stok
                FROM kartu_stok
                WHERE id_barang = p_id_barang
                ORDER BY update_terakhir DESC, id_kartu_stok DESC
                LIMIT 1;

                RETURN COALESCE(v_stok, 0);
            END;
        ");

        // Function 4: Cek Kelengkapan Penerimaan PO (1 = Selesai, 0 = Belum)
        DB::unprepared("
            DROP FUNCTION IF EXISTS fn_pengadaan_selesai;
            CREATE FUNCTION fn_pengadaan_selesai(p_id_pengadaan BIGINT)
            RETURNS TINYINT
            DETERMINISTIC
            BEGIN
                DECLARE v_remaining INT DEFAULT 0;

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

        /* =====================================================================
         * 3. TRIGGERS (5 TRIGGER: CUSTOM ID, AUDIT LOG, & KARTU STOK 'M','K','R')
         * ===================================================================== */

        // Trigger 1: Auto-Generate Custom ID Barang (BB-001, BSJ-001, dst.)
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
                WHERE jenis_barang = NEW.jenis_barang;

                INSERT INTO counter_barang (jenis_barang, counter_terakhir)
                VALUES (NEW.jenis_barang, v_next_counter)
                ON DUPLICATE KEY UPDATE counter_terakhir = v_next_counter;

                SET NEW.id_barang = CONCAT(NEW.jenis_barang, '-', LPAD(v_next_counter, 3, '0'));
            END;
        ");

        // Trigger 2: Audit Log Otomatis Saat Tambah Barang
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

        // Trigger 3: Stok Masuk ('M') saat Detail Penerimaan Diinsert
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_insert_detail_penerimaan;
            CREATE TRIGGER after_insert_detail_penerimaan
            AFTER INSERT ON detail_penerimaan
            FOR EACH ROW
            BEGIN
                DECLARE last_stock INT DEFAULT 0;
                SET last_stock = fn_cek_stok(NEW.id_barang);

                INSERT INTO kartu_stok (
                    jenis_transaksi, masuk, keluar, stock, update_terakhir, id_transaksi, id_barang
                ) VALUES (
                    'M', NEW.jumlah_terima, 0, last_stock + NEW.jumlah_terima, NOW(), NEW.id_penerimaan, NEW.id_barang
                );
            END;
        ");

        // Trigger 4: Stok Keluar ('K') saat Detail Penjualan Diinsert
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_insert_detail_penjualan;
            CREATE TRIGGER after_insert_detail_penjualan
            AFTER INSERT ON detail_penjualan
            FOR EACH ROW
            BEGIN
                DECLARE last_stock INT DEFAULT 0;
                SET last_stock = fn_cek_stok(NEW.id_barang);

                INSERT INTO kartu_stok (
                    jenis_transaksi, masuk, keluar, stock, update_terakhir, id_transaksi, id_barang
                ) VALUES (
                    'K', 0, NEW.jumlah, last_stock - NEW.jumlah, NOW(), NEW.id_penjualan, NEW.id_barang
                );
            END;
        ");

        // Trigger 5: Stok Keluar ('R') saat Detail Retur Diinsert
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_insert_detail_retur;
            CREATE TRIGGER after_insert_detail_retur
            AFTER INSERT ON detail_retur
            FOR EACH ROW
            BEGIN
                DECLARE last_stock INT DEFAULT 0;
                DECLARE v_id_barang VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

                SELECT id_barang INTO v_id_barang
                FROM detail_penerimaan
                WHERE id_detail_penerimaan = NEW.id_detail_penerimaan
                LIMIT 1;

                SET last_stock = fn_cek_stok(v_id_barang);

                INSERT INTO kartu_stok (
                    jenis_transaksi, masuk, keluar, stock, update_terakhir, id_transaksi, id_barang
                ) VALUES (
                    'R', 0, NEW.jumlah, last_stock - NEW.jumlah, NOW(), NEW.id_retur, v_id_barang
                );
            END;
        ");

        /* =====================================================================
         * 4. STORED PROCEDURES (6 SP: POLA INOUT HANA + HELPER RIFDA)
         * ===================================================================== */

        // SP 1: Tambah Master Barang + Return Custom ID
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_tambah_barang;
            CREATE PROCEDURE sp_tambah_barang(
                IN p_jenis_barang VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                IN p_nama_barang VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                IN p_id_satuan INT,
                IN p_harga INT,
                OUT p_id_barang_baru VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            )
            BEGIN
                INSERT INTO barang (jenis_barang, nama_barang, id_satuan, harga, status)
                VALUES (p_jenis_barang, p_nama_barang, p_id_satuan, p_harga, 1);

                SELECT id_barang INTO p_id_barang_baru
                FROM barang
                WHERE jenis_barang = p_jenis_barang
                ORDER BY id_barang DESC
                LIMIT 1;
            END;
        ");

        // SP 2: Simpan Pengadaan Per-Item (Pola INOUT)
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_simpan_pengadaan;
            CREATE PROCEDURE sp_simpan_pengadaan(
                IN p_id_user INT,
                IN p_id_vendor INT,
                IN p_id_barang VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                IN p_jumlah INT,
                IN p_harga INT,
                INOUT p_id_pengadaan BIGINT
            )
            BEGIN
                DECLARE v_sub INT;
                DECLARE v_total_sub INT DEFAULT 0;
                DECLARE v_ppn INT DEFAULT 0;

                SET v_sub = p_jumlah * p_harga;

                IF p_id_pengadaan = 0 THEN
                    INSERT INTO pengadaan (id_user, id_vendor, status, subtotal, ppn, total_nilai, timestamp)
                    VALUES (p_id_user, p_id_vendor, 'P', 0, 0, 0, NOW());
                    SET p_id_pengadaan = LAST_INSERT_ID();
                END IF;

                INSERT INTO detail_pengadaan (id_pengadaan, id_barang, harga_satuan, jumlah, sub_total)
                VALUES (p_id_pengadaan, p_id_barang, p_harga, p_jumlah, v_sub);

                SELECT COALESCE(SUM(sub_total), 0) INTO v_total_sub
                FROM detail_pengadaan WHERE id_pengadaan = p_id_pengadaan;

                SET v_ppn = fn_calculate_ppn(v_total_sub);

                UPDATE pengadaan
                SET subtotal = v_total_sub,
                    ppn = v_ppn,
                    total_nilai = v_total_sub + v_ppn
                WHERE id_pengadaan = p_id_pengadaan;
            END;
        ");

        // SP 3: Simpan Penerimaan Per-Item (Pola INOUT + Otomatis Status PO 'S' / 'F')
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_simpan_penerimaan;
            CREATE PROCEDURE sp_simpan_penerimaan(
                IN p_id_pengadaan BIGINT,
                IN p_id_user INT,
                IN p_id_barang VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                IN p_jumlah_terima INT,
                IN p_harga INT,
                INOUT p_id_penerimaan BIGINT
            )
            BEGIN
                IF p_id_penerimaan = 0 THEN
                    INSERT INTO penerimaan (created_at, status, id_pengadaan, id_user)
                    VALUES (NOW(), 'F', p_id_pengadaan, p_id_user);
                    SET p_id_penerimaan = LAST_INSERT_ID();
                END IF;

                INSERT INTO detail_penerimaan (id_penerimaan, id_barang, jumlah_terima, harga_satuan_terima, sub_total_terima)
                VALUES (p_id_penerimaan, p_id_barang, p_jumlah_terima, p_harga, p_jumlah_terima * p_harga);

                IF fn_pengadaan_selesai(p_id_pengadaan) = 1 THEN
                    UPDATE pengadaan SET status = 'F' WHERE id_pengadaan = p_id_pengadaan;
                ELSE
                    UPDATE pengadaan SET status = 'S' WHERE id_pengadaan = p_id_pengadaan;
                END IF;
            END;
        ");

        // SP 4: Simpan Penjualan Per-Item (Pola INOUT)
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_simpan_penjualan;
            CREATE PROCEDURE sp_simpan_penjualan(
                IN p_id_user INT,
                IN p_id_margin INT,
                IN p_id_barang VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                IN p_jumlah INT,
                IN p_harga INT,
                INOUT p_id_penjualan INT
            )
            BEGIN
                DECLARE v_sub INT;
                DECLARE v_total_sub INT DEFAULT 0;
                DECLARE v_ppn INT DEFAULT 0;

                SET v_sub = p_jumlah * p_harga;

                IF p_id_penjualan = 0 THEN
                    INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, id_user, id_margin_penjualan)
                    VALUES (NOW(), 0, 0, 0, p_id_user, p_id_margin);
                    SET p_id_penjualan = LAST_INSERT_ID();
                END IF;

                INSERT INTO detail_penjualan (harga_satuan, jumlah, subtotal, id_penjualan, id_barang)
                VALUES (p_harga, p_jumlah, v_sub, p_id_penjualan, p_id_barang);

                SELECT COALESCE(SUM(subtotal), 0) INTO v_total_sub
                FROM detail_penjualan WHERE id_penjualan = p_id_penjualan;

                SET v_ppn = fn_calculate_ppn(v_total_sub);

                UPDATE penjualan
                SET subtotal_nilai = v_total_sub,
                    ppn = v_ppn,
                    total_nilai = v_total_sub + v_ppn
                WHERE id_penjualan = p_id_penjualan;
            END;
        ");

        // SP 5: Simpan Retur Per-Item (Pola INOUT)
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_simpan_retur;
            CREATE PROCEDURE sp_simpan_retur(
                IN p_id_penerimaan BIGINT,
                IN p_id_user INT,
                IN p_id_detail_penerimaan BIGINT,
                IN p_jumlah INT,
                IN p_alasan VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
                INOUT p_id_retur BIGINT
            )
            BEGIN
                IF p_id_retur = 0 THEN
                    INSERT INTO retur (dikembalikan_pada, id_penerimaan, id_user)
                    VALUES (NOW(), p_id_penerimaan, p_id_user);
                    SET p_id_retur = LAST_INSERT_ID();
                END IF;

                INSERT INTO detail_retur (jumlah, alasan, id_retur, id_detail_penerimaan)
                VALUES (p_jumlah, p_alasan, p_id_retur, p_id_detail_penerimaan);
            END;
        ");

        // SP 6: Helper Ambil Sisa Item PO untuk Form Penerimaan
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_get_detail_pengadaan_remaining;
            CREATE PROCEDURE sp_get_detail_pengadaan_remaining(IN p_id_pengadaan BIGINT)
            BEGIN
                SELECT
                    dp.id_detail_pengadaan,
                    dp.id_pengadaan,
                    dp.id_barang,
                    b.nama_barang,
                    s.nama_satuan,
                    dp.harga_satuan,
                    dp.jumlah AS jumlah_pesan,
                    COALESCE(terima.total_terima, 0) AS sudah_diterima,
                    (dp.jumlah - COALESCE(terima.total_terima, 0)) AS sisa_belum_diterima
                FROM detail_pengadaan dp
                JOIN barang b ON dp.id_barang = b.id_barang
                LEFT JOIN satuan s ON b.id_satuan = s.id_satuan
                LEFT JOIN (
                    SELECT pen.id_pengadaan, dpr.id_barang, SUM(dpr.jumlah_terima) AS total_terima
                    FROM detail_penerimaan dpr
                    JOIN penerimaan pen ON pen.id_penerimaan = dpr.id_penerimaan
                    GROUP BY pen.id_pengadaan, dpr.id_barang
                ) AS terima ON terima.id_pengadaan = dp.id_pengadaan AND terima.id_barang = dp.id_barang
                WHERE dp.id_pengadaan = p_id_pengadaan
                  AND (dp.jumlah - COALESCE(terima.total_terima, 0)) > 0;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_detail_pengadaan_remaining;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_simpan_retur;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_simpan_penjualan;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_simpan_penerimaan;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_simpan_pengadaan;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_tambah_barang;");
        DB::unprepared("DROP TRIGGER IF EXISTS after_insert_detail_retur;");
        DB::unprepared("DROP TRIGGER IF EXISTS after_insert_detail_penjualan;");
        DB::unprepared("DROP TRIGGER IF EXISTS after_insert_detail_penerimaan;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_barang;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_before_insert_barang;");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_pengadaan_selesai;");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_cek_stok;");
        DB::unprepared("DROP FUNCTION IF EXISTS get_harga_jual;");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_calculate_ppn;");
        DB::unprepared("DROP TABLE IF EXISTS log_barang;");
        DB::unprepared("DROP TABLE IF EXISTS counter_barang;");
    }
};
