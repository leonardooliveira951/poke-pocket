<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ability_pokemon', function (Blueprint $table) {
            $table->id();
            $table->integer('pokemon_id')->constrained();
            $table->integer('ability_id')->constrained();
            $table->boolean('is_hidden')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ability_pokemon');
    }
};
