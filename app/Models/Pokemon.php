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
        return $this->hasMany(Move::class);
    }

    public function stats()
    {
        return $this->belongsToMany(Stat::class)
            ->withTimestamps()
            ->withPivot('value');
    }
}
