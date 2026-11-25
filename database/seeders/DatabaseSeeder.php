<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan seluruh tabel dari child ke parent agar aman saat db:seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_retur')->truncate();
        DB::table('retur')->truncate();
        DB::table('detail_penjualan')->truncate();
        DB::table('penjualan')->truncate();
        DB::table('detail_penerimaan')->truncate();
        DB::table('penerimaan')->truncate();
        DB::table('detail_pengadaan')->truncate();
        DB::table('pengadaan')->truncate();
        DB::table('kartu_stok')->truncate();
        DB::table('log_barang')->truncate();
        DB::table('counter_barang')->truncate();
        DB::table('barang')->truncate();
        DB::table('margin_penjualan')->truncate();
        DB::table('vendor')->truncate();
        DB::table('satuan')->truncate();
        DB::table('user')->truncate();
        DB::table('role')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SatuanSeeder::class,
            VendorSeeder::class,
            MarginPenjualanSeeder::class,
            BarangSeeder::class,
        ]);
    }
}
