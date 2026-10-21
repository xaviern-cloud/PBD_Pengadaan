<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Role
        Schema::create('role', function (Blueprint $table) {
            $table->increments('id_role');
            $table->string('nama_role', 100);
        });

        // 2. User
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('username', 45);
            $table->string('password', 100);
            $table->unsignedInteger('id_role')->nullable();

            $table->foreign('id_role')->references('id_role')->on('role')->onDelete('set null');
        });

        // 3. Satuan
        Schema::create('satuan', function (Blueprint $table) {
            $table->increments('id_satuan');
            $table->string('nama_satuan', 45);
            $table->tinyInteger('status')->default(1);
        });

        // 4. Barang
        Schema::create('barang', function (Blueprint $table) {
            $table->increments('id_barang');
            $table->char('jenis_barang', 1)->nullable();
            $table->string('nama_barang', 45);
            $table->unsignedInteger('id_satuan')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('harga')->nullable();

            $table->foreign('id_satuan')->references('id_satuan')->on('satuan')->onDelete('set null');
        });

        // 5. Vendor
        Schema::create('vendor', function (Blueprint $table) {
            $table->increments('id_vendor');
            $table->string('nama_vendor', 100);
            $table->char('badan_hukum', 1)->nullable();
            $table->char('status', 1)->default('1');
        });

        // 6. Margin Penjualan
        Schema::create('margin_penjualan', function (Blueprint $table) {
            $table->increments('id_margin_penjualan');
            $table->double('persen');
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('id_user')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('set null');
        });

        // 7. Pengadaan
        Schema::create('pengadaan', function (Blueprint $table) {
            $table->bigIncrements('id_pengadaan');
            $table->timestamp('timestamp')->useCurrent();
            $table->unsignedInteger('user_id_user')->nullable();
            $table->char('status', 1)->nullable();
            $table->unsignedInteger('vendor_idvendor')->nullable();
            $table->integer('subtotal_nilai')->nullable();
            $table->integer('ppn')->nullable();
            $table->integer('total_nilai')->nullable();

            $table->foreign('user_id_user')->references('id_user')->on('user')->onDelete('set null');
            $table->foreign('vendor_idvendor')->references('id_vendor')->on('vendor')->onDelete('set null');
        });

        // 8. Detail Pengadaan
        Schema::create('detail_pengadaan', function (Blueprint $table) {
            $table->bigIncrements('id_detail_pengadaan');
            $table->integer('harga_satuan')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('sub_total')->nullable();
            $table->unsignedInteger('id_barang');
            $table->unsignedBigInteger('id_pengadaan');

            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreign('id_pengadaan')->references('id_pengadaan')->on('pengadaan')->onDelete('cascade');
        });

        // 9. Penerimaan
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->bigIncrements('id_penerimaan');
            $table->timestamp('created_at')->useCurrent();
            $table->char('status', 1)->nullable();
            $table->unsignedBigInteger('id_pengadaan');
            $table->unsignedInteger('id_user')->nullable();

            $table->foreign('id_pengadaan')->references('id_pengadaan')->on('pengadaan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('set null');
        });

        // 10. Detail Penerimaan
        Schema::create('detail_penerimaan', function (Blueprint $table) {
            $table->bigIncrements('id_detail_penerimaan');
            $table->unsignedBigInteger('id_penerimaan');
            $table->unsignedInteger('barang_id_barang');
            $table->integer('jumlah_terima')->nullable();
            $table->integer('harga_satuan_terima')->nullable();
            $table->integer('sub_total_terima')->nullable();

            $table->foreign('id_penerimaan')->references('id_penerimaan')->on('penerimaan')->onDelete('cascade');
            $table->foreign('barang_id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });

        // 11. Retur
        Schema::create('retur', function (Blueprint $table) {
            $table->bigIncrements('id_retur');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('id_penerimaan');
            $table->unsignedInteger('id_user')->nullable();

            $table->foreign('id_penerimaan')->references('id_penerimaan')->on('penerimaan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('set null');
        });

        // 12. Detail Retur
        Schema::create('detail_retur', function (Blueprint $table) {
            $table->increments('id_detail_retur');
            $table->integer('jumlah')->nullable();
            $table->string('alasan', 200)->nullable();
            $table->unsignedBigInteger('id_retur');
            $table->unsignedBigInteger('id_detail_penerimaan');

            $table->foreign('id_retur')->references('id_retur')->on('retur')->onDelete('cascade');
            $table->foreign('id_detail_penerimaan')->references('id_detail_penerimaan')->on('detail_penerimaan')->onDelete('cascade');
        });

        // 13. Penjualan
        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('id_penjualan');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('subtotal_nilai')->nullable();
            $table->integer('ppn')->nullable();
            $table->integer('total_nilai')->nullable();
            $table->unsignedInteger('id_user')->nullable();
            $table->unsignedInteger('id_margin_penjualan')->nullable();

            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('set null');
            $table->foreign('id_margin_penjualan')->references('id_margin_penjualan')->on('margin_penjualan')->onDelete('set null');
        });

        // 14. Detail Penjualan
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->bigIncrements('id_detail_penjualan');
            $table->integer('harga_satuan')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('subtotal')->nullable();
            $table->unsignedInteger('penjualan_id_penjualan');
            $table->unsignedInteger('id_barang');

            $table->foreign('penjualan_id_penjualan')->references('id_penjualan')->on('penjualan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });

        // 15. Kartu Stok
        Schema::create('kartu_stok', function (Blueprint $table) {
            $table->bigIncrements('id_kartu_stok');
            $table->char('jenis_transaksi', 1)->nullable();
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);
            $table->integer('stock')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('id_transaksi')->nullable();
            $table->unsignedInteger('id_barang');

            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });
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
