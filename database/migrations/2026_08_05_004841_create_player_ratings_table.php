<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_ratings', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $blueprint->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $blueprint->unsignedTinyInteger('rating');
            $blueprint->string('session_id')->nullable();
            $blueprint->timestamps();

            $blueprint->unique(['player_id', 'game_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_ratings');
    }
};
