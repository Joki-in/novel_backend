<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IsiSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            DB::table('isi')->insert([
                'id_buku' => $i, // Sesuaikan dengan ID buku yang ada
                'chapter' => 'Chapter ' . $i,
                'isi' => 'Isi dari chapter ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
