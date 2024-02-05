<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(BukuSeeders::class);
        $this->call(KomentarSeeders::class);
        $this->call(IsiSeeders::class);
        $this->call(LikeSeeder::class);
    }
}
