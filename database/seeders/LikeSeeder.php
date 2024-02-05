<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Loop through and insert dummy data
        for ($i = 0; $i < 10; $i++) {
            DB::table('like')->insert([
                'buku_id' => $faker->numberBetween(1, 3), // Assuming there are 5 buku entries in the 'buku' table
                'user_id' => $faker->numberBetween(1, 2), // Assuming there are 10 user entries in the 'users' table
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
