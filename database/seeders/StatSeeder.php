<?php

namespace Database\Seeders;

use App\Services\Clients\PokeApiClient;
use App\Services\PokemonService\PokemonService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pokemonService = new PokemonService(new PokeApiClient());
        $pokemonService->statFetchAndSave();
    }
}
