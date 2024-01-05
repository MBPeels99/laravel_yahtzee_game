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
        Schema::create('game_players', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('player_id');
            
            // Set up the foreign keys
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');

            // Composite primary key
            $table->primary(['game_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};
