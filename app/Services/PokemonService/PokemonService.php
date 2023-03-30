<?php

namespace App\Services\PokemonService;

use App\Enums\PokeApiEntities;
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
        for ($id = 1; $id<=15; $id++) {
            $pokemon = $this->pokeApiClient->abstractGet(PokeApiEntities::POKEMON, $id);
            $pokemonModel = Pokemon::firstOrCreate(
                ['id' => $id],
                [
                    'name' => $pokemon->name,
                    'height' => $pokemon->height,
                    'weight' => $pokemon->weight,
                    'base_experience' => $pokemon->base_experience,
                    'sprites' => json_encode($pokemon->sprites),
                ]
            );
            
            $this->attachPokemonTypes($pokemon->types, $pokemonModel);
            $this->attachPokemonStats($pokemon->stats, $pokemonModel);
            $this->attachPokemonMoves($pokemon->moves, $pokemonModel);
            $this->attachPokemonAbilities($pokemon->abilities, $pokemonModel);
        }
        return $pokemon;
    }

    public function abilityExternalFetchAndSave()
    {
        try {
            for ($id = 1; $id <= 20; $id++) {
                    $ability = $this->pokeApiClient->abstractGet(PokeApiEntities::ABILITY, $id);
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
            logger($e->getMessage());
        }
    }

    public function typeExternalFetchAndSave()
    {
        try {
            for ($id = 1; $id <= 20; $id++) {
                $type = $this->pokeApiClient->abstractGet(PokeApiEntities::TYPE, $id);

                Type::updateOrCreate(
                    ['id' => $id],
                    [
                        'name' => $type->name,
                    ]
                );
            }
        } catch (PokeApiClienItemNotFoundException $e) {
            logger($e->getMessage());
        }
    }

    public function moveFetchAndSave()
    {
        try {
            
            for ($id = 1; $id <= 100; $id++) {
                $move = $this->pokeApiClient->abstractGet(PokeApiEntities::MOVE, $id);
                $englishDescription = "";

                foreach ($move->effect_entries as $description) {
                    if ($description->language->name == "en") {
                        $englishDescription = $description->short_effect;
                        break;
                    }
                }
                $typeId = Str::between($move->type->url, 'type/', '/');
                $damageClassId = Str::between($move->damage_class->url, '{move-damage-class}/', '/');

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
                        'move_damage_class_id' => $damageClassId,
                    ]);
            }
        } catch (PokeApiClienItemNotFoundException $e) {
            logger($e->getMessage());
        }
    }

    public function moveDmgClassFetchAndSave()
    {
        try {
            for ($id = 1; $id <= 10; $id++) {
                $moveDmgClass = $this->pokeApiClient->abstractGet(PokeApiEntities::MOVE_DAMAGE_CLASS, $id);
                $englishDescription = "";

                foreach ($moveDmgClass->descriptions as $description)
                {
                    if ($description->language->name == "en") {
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
            logger($e->getMessage());
        }
    }

    public function statFetchAndSave()
    {
        $damageClassId = null;
        try {
            for ($id = 1; $id <= 15; $id++) {
                $stat = $this->pokeApiClient->abstractGet(PokeApiEntities::STAT, $id);

                if ($stat->move_damage_class) {
                    $damageClassId = Str::between($stat->move_damage_class->url, 'move-damage-class/', '/');
                }

                Stat::updateOrCreate(
                    ['id' => $id],
                    [
                        'name' => $stat->name,
                        'is_battle_only' => $stat->is_battle_only,
                        'move_damage_class_id' => $damageClassId,
                    ]
                );
            }
        } catch (PokeApiClienItemNotFoundException $e) {
            logger($e->getMessage());
        }
    }

    public function fetchById(int $id)
    {
        return Pokemon::findOrFail($id);
    }

    private function attachPokemonTypes($typesArrayFromPokeApi, Pokemon $pokemon)
    {
        foreach ($typesArrayFromPokeApi as $type) {
            $typeId = Str::between($type->type->url, 'type/', '/');
            $existingRecord = $pokemon->types()->wherePivot('type_id', $typeId)
                ->wherePivot('pokemon_id', $pokemon->id)
                ->first();

            if (!$existingRecord) {
                $pokemon->types()->attach($typeId);
            }
        }
    }

    private function attachPokemonStats($statsArrayFromPokeApi, $pokemon)
    {
        foreach ($statsArrayFromPokeApi as $stat) {
            $statId = Str::between($stat->stat->url, 'stat/', '/');
            $existingRecord = $pokemon->stats()->wherePivot('stat_id', $statId)
                ->wherePivot('pokemon_id', $pokemon->id)
                    ->first();
            
            if (!$existingRecord) {
                $pokemon->stats()->attach($statId, [
                    'base_stat' => $stat->base_stat,
                    'effort' => $stat->effort
                ]);
            }
        }
    }

    private function attachPokemonMoves($movesArrayFromPokeApi, $pokemon)
    {
        foreach ($movesArrayFromPokeApi as $move) {
            $moveId = Str::between($move->move->url, 'move/', '/');

            $existingRecord = $pokemon->moves()->wherePivot('move_id', $moveId)
                ->wherePivot('pokemon_id', $pokemon->id)
                ->first();
            if (!$existingRecord) {
                $pokemon->moves()->attach($moveId);
            }
        }
    }

    private function attachPokemonAbilities($abilitiesArrayFromPokeApi, $pokemon)
    {
        foreach ($abilitiesArrayFromPokeApi as $ability) {
            $abilityId = Str::between($ability->ability->url, 'ability/', '/');

            $existingRecord = $pokemon->abilities()->wherePivot('ability_id', $abilityId)
                ->wherePivot('pokemon_id', $pokemon->id)
                ->first();
            if (!$existingRecord) {
                $pokemon->abilities()->attach($abilityId, [
                    'is_hidden' => $ability->is_hidden,
                ]);
            }
        }
    }

}