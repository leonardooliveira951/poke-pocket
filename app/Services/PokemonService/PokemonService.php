<?php

namespace App\Services\PokemonService;

use App\Models\Pokemon;
use App\Services\Clients\PokeApiClient;

class PokemonService
{
    public function __construct(
        private PokeApiClient $pokeApiClient,
    ) {
    }

    public function externalFetchAndSave()
    {
        for ($id = 1; $id<=150; $id++) {
            $pokemon = $this->pokeApiClient->getPokemonById($id);
            Pokemon::firstOrCreate(
                ['id' => $id],
                [
                    'name' => $pokemon->name,
                    'height' => $pokemon->height,
                    'weight' => $pokemon->weight,
                    'sprites' => json_encode($pokemon->sprites)                
                ]
            );
        }
        return $pokemon;
    }

    public function fetchById(int $id)
    {
        return Pokemon::findOrFail($id);
    }
}