<?php

namespace App\Exceptions;

use Exception;

class MissingEnvValueException extends Exception
{
    public static function for(string $var)
    {
        return new self("Missing $var .env value");
    }
}
