<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    use HasFactory;

    protected $fillable = [
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function moveDamageClass()
    {
        return $this->belongsTo(MoveDamageClass::class);
    }
}
