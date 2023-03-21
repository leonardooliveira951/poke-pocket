<?php

namespace App\Services\PokemonService;

use App\Exceptions\PokeApiClienItemNotFoundException;
use App\Models\Ability;
use App\Models\Move;
use App\Models\MoveDamageClass;
use App\Models\Pokemon;
use App\Models\Stat;
use App\Models\Type;
use App\Services\Clients\PokeApiClient;
use Illuminate\Support\Str;

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
                    'sprites' => json_encode($pokemon->sprites),
                    'type_id'
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

    public function typeExternalFetchAndSave()
    {
        try {
            for ($id = 1; $id <= 18; $id++) {
                $type = $this->pokeApiClient->getTypeById($id);

                Type::updateOrCreate(
                    ['id' => $id],
                    [
                        'name' => $type->name,
                    ]
                );
            }
        } catch (PokeApiClienItemNotFoundException $e) {
            logger($e->message($id));
            $lastInsert = $id - 1;
            logger("Type - Last inserted ID was $lastInsert");
        }
    }

    public function moveFetchAndSave()
    {
        try {
            
            for ($id = 1; $id <= 400; $id++) {
                $move = $this->pokeApiClient->getMoveById($id);
                $englishDescription = "";

                foreach ($move->effect_entries as $description) {
                    if ($description->language->name == "en") {
                        $englishDescription = $description->short_effect;
                        break;
                    }
                }
                $typeId = Str::between($move->type->url, 'type/', '/');
                $damageClassId = Str::between($move->damage_class->url, 'move-damage-class/', '/');

                Move::updateOrCreate(
                    ['id' => $id],
                    [
                        'name' => $move->name,
                        'description' => $englishDescription,
                        'power' => $move->power,
                        'accuracy' => $move->accuracy,
                        'pp' => $move->pp,
                        'priority' => $move->priority,
                        'type_id' => $typeId,
                        'move_damage_classes_id' => $damageClassId,
                    ]);
            }
        } catch (PokeApiClienItemNotFoundException $e) {
            logger($e->message($id));
            $lastInsert = $id - 1;
            logger("Move - Last inserted ID was $lastInsert");
        }
    }

    public function moveDmgClassFetchAndSave()
    {
        try {
            for ($id = 1; $id <= 10; $id++) {
                $moveDmgClass = $this->pokeApiClient->getMoveDmgClassById($id);
                $englishDescription = "";

                foreach ($moveDmgClass->descriptions as $description)
                {
                    if ($description->language->name == "en") {
                        logger($description->description);
                        $englishDescription = $description->description;
                        break;
                    }
                }
                MoveDamageClass::updateOrCreate(
                    ['id' => $id],
                    [
                        'name' => $moveDmgClass->name,
                        'description' => $englishDescription,
                    ]
                );
            }
        } catch (PokeApiClienItemNotFoundException $e) {
            logger($e->message($id));
            $lastInsert = $id - 1;
            logger("Move Damage Class - Last inserted ID was $lastInsert");
        }
    }

    public function statFetchAndSave()
    {
        try {
            for ($id = 1; $id <= 15; $id++) {
                $stat = $this->pokeApiClient->getStatById($id);

                Stat::updateOrCreate(
                    ['id' => $id],
                    [
                        'name' => $stat->name,
                        'is_battle_only' => $stat->is_battle_only,
                    ]
                );
            }
        } catch (PokeApiClienItemNotFoundException $e) {
            logger($e->message($id));
            $lastInsert = $id - 1;
            logger("Stat - Last inserted ID was $lastInsert");
        }
    }

    public function fetchById(int $id)
    {
        return Pokemon::findOrFail($id);
    }
}