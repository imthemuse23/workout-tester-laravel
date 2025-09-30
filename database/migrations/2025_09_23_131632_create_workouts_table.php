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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('workout_name');
            $table->text('description')->nullable();
            $table->integer('duration');
            $table->string('image')->nullable(); // kolom untuk gambar
            $table->enum('difficulty', ['Beginner', 'Intermediate', 'Advanced']); // kolom enum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
