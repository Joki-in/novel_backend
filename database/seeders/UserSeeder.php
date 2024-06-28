<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'alamat' => 'Jl. Raya Cikarang',
            'tanggal_lahir' => '2000-01-01',
            'role' => 'admin',
            'password' => Hash::make('secret'),
        ]);
        DB::table('users')->insert([
            'name' => 'rizqi',
            'email' => 'semuamana@gmail.com',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. mantap',
            'password' => Hash::make('1'),
        ]);
    }
}
