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
        Schema::create('pokemon_stat', function (Blueprint $table) {
            $table->id();
            $table->integer('pokemon_id')->constrained();
            $table->integer('stat_id')->constrained();
            $table->integer('base_stat')->nullable();
            $table->integer('effort')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon_stat');
    }
};
