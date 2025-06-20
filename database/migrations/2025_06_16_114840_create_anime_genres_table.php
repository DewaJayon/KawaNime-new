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
        Schema::create('anime_genres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id');
            $table->foreignId('genre_id');

            $table->foreign('anime_id')->references('id')->on('animes');
            $table->foreign('genre_id')->references('id')->on('genres');
            $table->index(['genre_id', 'anime_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_genres');
    }
};
