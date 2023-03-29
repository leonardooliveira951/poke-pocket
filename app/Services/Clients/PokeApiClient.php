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

    public function abstractGet(string $entityName, int $id)
    {
        $item = json_decode(
            Http::get(
                $this->baseUri . "/{$entityName}/{$id}/"
            )->getBody()->getContents()
        );

        if (is_null($item)) {
            throw new PokeApiClienItemNotFoundException($id);
        }

        return $item;
    }

}
