<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KomentarSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            DB::table('komentar')->insert([
                'id_buku' => $i, // Sesuaikan dengan ID buku yang ada
                'id_user' => 1, // Sesuaikan dengan ID user yang membuat komentar
                'komentar' => 'Komentar buku ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
