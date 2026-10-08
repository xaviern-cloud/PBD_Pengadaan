<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'id_user'  => 1,
                'username' => 'admin',
                'PASSWORD' => Hash::make('password123'),
                'id_role'  => 1, // Administrator
            ],
            [
                'id_user'  => 2,
                'username' => 'purchasing_user',
                'PASSWORD' => Hash::make('password123'),
                'id_role'  => 2, // Manager / Purchasing
            ],
            [
                'id_user'  => 3,
                'username' => 'kasir1',
                'PASSWORD' => Hash::make('password123'),
                'id_role'  => 3, // Kasir
            ],
        ]);
    }
}
