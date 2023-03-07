<?php

namespace App\Http\Controllers;

use App\Services\PokemonService\PokemonService;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function __construct(
        private PokemonService $pokemonService
    ) {
    }

    public function getAllPokemons(Request $request) {        
        return $this->pokemonService->externalFetchAndSave();
    }

    public function getById(Request $request) {
        return $this->pokemonService->fetchById($request->id);
    }
}
