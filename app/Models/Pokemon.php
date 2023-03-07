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
        'sprites',
    ];

    // public function moves()
    // {
    //     return $this->hasMany(Move::class);
    // }
}
