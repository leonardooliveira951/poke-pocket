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
        print("Database seeding. It might take a while \n");
        
        $this->call(AbilitySeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(MoveDamageClassSeeder::class);
        $this->call(MoveSeeder::class);
        $this->call(StatSeeder::class);
        $this->call(PokemonSeeder::class);
    }
}
