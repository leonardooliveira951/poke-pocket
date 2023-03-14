<?php

namespace App\Services\Clients;

use App\Exceptions\MissingEnvValueException;
use App\Exceptions\PokeApiClienItemNotFoundException;
use Exception;
use Illuminate\Support\Facades\Http;


class PokeApiClient
{
    private string $baseUri;

    public function __construct()
    {
        $this->baseUri = env('POKE_API_CLIENT_URL');

        if (empty($this->baseUri)) {
            throw MissingEnvValueException::for('POKE_API_CLIENT_URL');
        }
    }

    public function getPokemonById(int|string $id)
    {
        $pokemon = json_decode(
                Http::get($this->baseUri . "/pokemon/{$id}/"
            )->getBody()->getContents());

        if (is_null($pokemon)) {
            throw new PokeApiClienItemNotFoundException($id);
        }

        return $pokemon;
    }

    public function getAbilityById(int|string $id)
    {
        $ability = json_decode(
            Http::get(
                $this->baseUri . "/ability/{$id}/"
            )->getBody()->getContents()
        );
        
        if (is_null($ability)) {
            throw new PokeApiClienItemNotFoundException($id);
        }
        return $ability;
    }

    public function getTypeById(int|string $id)
    {
        $ability = json_decode(
            Http::get(
                $this->baseUri . "/type/{$id}/"
            )->getBody()->getContents()
        );

        if (is_null($ability)) {
            throw new PokeApiClienItemNotFoundException($id);
        }
        return $ability;
    }

    public function getMoveDmgClassById(int|string $id)
    {
        $moveDmgClass = json_decode(
            Http::get(
                $this->baseUri . "/move-damage-class/{$id}/"
            )->getBody()->getContents()
        );

        if (is_null($moveDmgClass)) {
            throw new PokeApiClienItemNotFoundException($id);
        }
        return $moveDmgClass;
    }
}
