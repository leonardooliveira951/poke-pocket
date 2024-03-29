<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveDamageClass extends Model
{
    use HasFactory;

    protected $fillable = [
    ];

    public function moves()
    {
        return $this->hasMany(Move::class)
            ->withTimestamps();
    }
}
