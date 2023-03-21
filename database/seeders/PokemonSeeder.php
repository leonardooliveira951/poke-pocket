<?php

namespace Database\Seeders;

use App\Services\Clients\PokeApiClient;
use App\Services\PokemonService\PokemonService;
use Illuminate\Database\Seeder;

class PokemonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pokemonService = new PokemonService(new PokeApiClient());
        $pokemonService->externalFetchAndSave();
    }
}
