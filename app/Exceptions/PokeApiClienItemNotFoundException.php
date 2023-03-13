<?php

namespace App\Exceptions;

use Exception;

class PokeApiClienItemNotFoundException extends Exception
{
    public static function message(string $id)
    {
        return new self("The searched ID doesn't exist yet. ID: $id");
    }
}
