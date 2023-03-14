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
        logger("Database seeding. It might take a while");
        
        $this->call(AbilitySeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(MoveDamageClassSeeder::class);
        $this->call(MoveSeeder::class);
    }
}
