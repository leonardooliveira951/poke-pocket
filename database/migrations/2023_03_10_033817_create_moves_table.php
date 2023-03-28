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
        Schema::create('moves', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description', 250);
            $table->decimal('power')->nullable();
            $table->decimal('accuracy')->nullable();
            $table->decimal('pp')->nullable();
            $table->smallInteger('priority')->default(0);
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('move_damage_class_id')->constrained('move_damage_classes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moves');
    }
};
