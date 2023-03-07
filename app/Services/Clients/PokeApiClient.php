<?php

namespace App\Services\Clients;

use Illuminate\Support\Facades\Http;


class PokeApiClient
{
    public function getPokemonById(int|string $id) {
        return json_decode(
                Http::get("https://pokeapi.co/api/v2/pokemon/{$id}/"
            )->getBody()->getContents());
    }
}