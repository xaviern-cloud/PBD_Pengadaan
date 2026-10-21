<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan sementara pengecekan Foreign Key agar bisa di-truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ROLE')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('ROLE')->insert([
            ['id_role' => 1, 'nama_role' => 'Administrator'],
            ['id_role' => 2, 'nama_role' => 'Manager'],
            ['id_role' => 3, 'nama_role' => 'Kasir'],
        ]);
    }
}
