<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'height',
        'weight',
        'base_experience',
        'sprites',
    ];

    public function moves()
    {
        return $this->belongsToMany(Move::class)
            ->withTimestamps();;
    }

    public function stats()
    {
        return $this->belongsToMany(Stat::class)
            ->withTimestamps()
            ->withPivot('value');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class)
            ->withTimestamps();
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class)
            ->withTimestamps();
    }
}
