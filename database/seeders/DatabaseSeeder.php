<?php

namespace Database\Seeders;

use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Team::insert([
            ['name' => 'Arsenal'],
            ['name' => 'Liverpool'],
            ['name' => 'Manchester City'],
            ['name' => 'Chelsea'],
        ]);
    }
}
