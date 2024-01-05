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
        Schema::create('game_bots', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('bot_id');
            
            // Set up the foreign keys
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('bot_id')->references('id')->on('bots')->onDelete('cascade');

            // Composite primary key
            $table->primary(['game_id', 'bot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_bots');
    }
};
