<?php

namespace App\Services\PokemonService;

use App\Exceptions\PokeApiClienItemNotFoundException;
use App\Models\Ability;
use App\Models\Pokemon;
use App\Services\Clients\PokeApiClient;

class PokemonService
{
    public function __construct(
        private PokeApiClient $pokeApiClient,
        ) {}

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
                    'base_experience' => $pokemon->weight,
                    'sprites' => json_encode($pokemon->sprites)                
                ]
            );
        }
        return $pokemon;
    }

    public function abilityExternalFetchAndSave()
    {
        try {
            for ($id = 1; $id <= 300; $id++) {
                    $ability = $this->pokeApiClient->getAbilityById($id);
                    $englishDescription = "";

                    foreach ($ability->effect_entries as $description)
                        {
                            if ($description->language->name == "en") {
                                $englishDescription = $description->short_effect;
                                break;
                            }
                        }
                    Ability::updateOrCreate(
                        ['id' => $id],
                        [
                            'name' => $ability->name,
                            'description' => $englishDescription,
                        ]
                    );
            } 
        } catch (PokeApiClienItemNotFoundException $e) {
            logger($e->message($id));
            $lastInsert = $id - 1;
            logger("Ability - Last inserted ID was $lastInsert");
        }
    }

    public function fetchById(int $id)
    {
        return Pokemon::findOrFail($id);
    }
}