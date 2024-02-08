<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class BukuSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            DB::table('buku')->insert([
                'judul' => $faker->sentence,
                'sinopsis' => $faker->paragraph,
                'view' => rand(0, 100),
                'genre' => $faker->randomElement(['fiksi', 'drama', 'horor']),
                'cover' => '1707365655_2.jpg',
                'penulis_id' => 1,
                '18+' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
