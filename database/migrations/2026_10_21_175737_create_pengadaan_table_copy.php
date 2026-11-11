<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Role
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS role(
            id_role INT AUTO_INCREMENT PRIMARY KEY,
            nama_role varchar(16) NOT NULL
            );
        ");

        // 2. User
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS user(
            id_user INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(45),
            password VARCHAR(100),
            id_role INT,

            constraint fk_user_role FOREIGN KEY (id_role) REFERENCES role(id_role)
        );");

        // 3. Satuan
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS satuan (
            id_satuan INT AUTO_INCREMENT PRIMARY KEY,
            nama_satuan VARCHAR(45),
            status TINYINT);
        ");

        // 4. Barang (Primary Key String Custom ID: BB-001)
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS barang (
            id_barang VARCHAR(20) PRIMARY KEY,
            jenis_barang char(3),
            nama_barang varchar(45),
            id_satuan INT,
            status TINYINT,
            harga decimal(10,2),

            CONSTRAINT fk_barang_satuan FOREIGN KEY (id_satuan) REFERENCES satuan(id_satuan)
        );");
        // 5. VENDOR
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS vendor(
            id_vendor INT AUTO_INCREMENT PRIMARY KEY,
            nama_vendor varchar(100),
            badan_hukum char(2),
            status char(1)
            );
        ");

        // 6. Margin penjualan
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS margin_penjualan (
            id_margin_penjualan INT AUTO_INCREMENT PRIMARY KEY,
            persen double,
            status TINYINT,
            id_user INT,
            dibuat_pada timestamp,

            constraint fk_margin_user FOREIGN KEY (id_user) REFERENCES user(id_user));

        ");

        // 7. Pengadaan
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS pengadaan(
            id_pengadaan BIGINT AUTO_INCREMENT PRIMARY KEY,
            dibuat_pada timestamp,
            status char(1),
            id_user INT,
            id_vendor INT,
            subtotal decimal(10,2),
            ppn int,
            total_nilai decimal(10,2),

            CONSTRAINT fk_pengadaan_user FOREIGN KEY (id_user) REFERENCES user(id_user),
            CONSTRAINT fk_pengadaan_vendor FOREIGN KEY (id_vendor) REFERENCES vendor(id_vendor));
        ");

        // 8. DETAIL PENGADAAN
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS detail_pengadaan(
            id_detail_pengadaan BIGINT AUTO_INCREMENT PRIMARY KEY,
            harga_satuan decimal(10,2),
            jumlah int,
            sub_total decimal(10,2),
            id_barang varchar(20),
            id_pengadaan BIGINT,

            constraint fk_dpengadaan_barang FOREIGN KEY (id_barang) REFERENCES barang(id_barang),
            constraint fk_dpengadaan_pengadaan FOREIGN KEY (id_pengadaan) REFERENCES pengadaan(id_pengadaan));
        ");

        // 9. PENERIMAAN
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS penerimaan(
            id_penerimaan BIGINT AUTO_INCREMENT PRIMARY KEY,
            diterima_pada timestamp,
            status char(1),
            id_pengadaan BIGINT,
            id_user int,

            constraint fk_penerimaan_pengadaan FOREIGN KEY (id_pengadaan) REFERENCES pengadaan(id_pengadaan),
            constraint fk_penerimaan_user FOREIGN KEY (id_user) REFERENCES user(id_user));
        ");

        // 10. DETAIL PENERIMAAN
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS detail_penerimaan(
            id_detail_penerimaan BIGINT AUTO_INCREMENT PRIMARY KEY,
            jumlah_terima INT,
            harga_satuan_terima decimal(10,2),
            sub_total_terima decimal(10,2),
            id_penerimaan BIGINT,
            id_barang VARCHAR(20),

            constraint id_dpenerimaan_penerimaan FOREIGN KEY (id_penerimaan) REFERENCES penerimaan(id_penerimaan),
            constraint id_dpenerimaan_barang FOREIGN KEY (id_barang) REFERENCES barang(id_barang));
        ");

        // 11. RETUR
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS retur (
            id_retur BIGINT AUTO_INCREMENT PRIMARY KEY,
            dikembalikan_pada timestamp,
            id_penerimaan BIGINT,
            id_user INT,

            constraint fk_retur_penerimaan FOREIGN KEY (id_penerimaan) REFERENCES penerimaan(id_penerimaan),
            constraint fk_retur_user FOREIGN KEY (id_user) REFERENCES user(id_user));
        ");

        // 12. DETAIL RETUR
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS detail_retur (
            id_detail_retur INT AUTO_INCREMENT PRIMARY KEY,
            jumlah INT,
            alasan VARCHAR(200),
            id_retur BIGINT,
            id_detail_penerimaan BIGINT,

            constraint fk_dretur_retur FOREIGN KEY (id_retur) REFERENCES retur(id_retur),
            constraint fk_dretur_dpenerimaan FOREIGN KEY (id_detail_penerimaan) REFERENCES detail_penerimaan(id_detail_penerimaan));
        ");
        // 13. PENJUALAN
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS penjualan(
            id_penjualan INT AUTO_INCREMENT PRIMARY KEY,
            dijual_pada timestamp,
            subtotal_nilai decimal(10,2),
            ppn INT,
            total_nilai decimal(10,2),
            id_user INT,
            id_margin_penjualan INT,

            CONSTRAINT id_penjualan_user FOREIGN KEY (id_user) REFERENCES user(id_user),
            CONSTRAINT id_penjualan_margin FOREIGN KEY (id_margin_penjualan) REFERENCES margin_penjualan(id_margin_penjualan));
        ");

        // 14. DETAIL PENJUALAN
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS detail_penjualan (
            id_detail_penjualan BIGINT AUTO_INCREMENT PRIMARY KEY,
            harga_satuan decimal(10,2),
            jumlah INT,
            subtotal decimal(10,2),
            id_penjualan INT,
            id_barang VARCHAR(20),

            CONSTRAINT id_dpenjualan_penjualan FOREIGN KEY (id_penjualan) REFERENCES penjualan(id_penjualan),
            CONSTRAINT id_dpenjualan_barang FOREIGN KEY (id_barang) REFERENCES barang(id_barang));
        ");

        //15. KARTU STOK
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS kartu_stok(
            id_kartu_stok BIGINT AUTO_INCREMENT PRIMARY KEY,
            masuk int,
            keluar int,
            stock int,
            update_terakhir timestamp,
            jenis_transaksi char(1),
            id_transaksi int,
            id_barang varchar(20),

            constraint fk_kstok_barang FOREIGN KEY (id_barang) REFERENCES barang(id_barang));
        ");

    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_stok');
        Schema::dropIfExists('detail_penjualan');
        Schema::dropIfExists('penjualan');
        Schema::dropIfExists('detail_retur');
        Schema::dropIfExists('retur');
        Schema::dropIfExists('detail_penerimaan');
        Schema::dropIfExists('penerimaan');
        Schema::dropIfExists('detail_pengadaan');
        Schema::dropIfExists('pengadaan');
        Schema::dropIfExists('margin_penjualan');
        Schema::dropIfExists('vendor');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('satuan');
        Schema::dropIfExists('user');
        Schema::dropIfExists('role');
    }
};
