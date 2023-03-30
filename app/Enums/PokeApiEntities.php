<?php

namespace App\Enums;

enum PokeApiEntities: string
{
    case ABILITY = 'ability';
    case MOVE = 'move';
    case TYPE = 'type';
    case STAT = 'stat';
    case POKEMON = 'pokemon';
    case MOVE_DAMAGE_CLASS = 'move-damage-class';
}
