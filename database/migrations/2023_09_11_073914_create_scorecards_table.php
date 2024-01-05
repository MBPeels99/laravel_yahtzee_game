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
        Schema::create('scorecards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->integer('aces')->default(0);
            $table->integer('twos')->default(0);
            $table->integer('threes')->default(0);
            $table->integer('fours')->default(0);
            $table->integer('fives')->default(0);
            $table->integer('sixes')->default(0);
            $table->integer('three_of_a_kind')->default(0);
            $table->integer('four_of_a_kind')->default(0);
            $table->integer('full_house')->default(25);
            $table->integer('small_straight')->default(30);
            $table->integer('large_straight')->default(40);
            $table->integer('yahtzee')->default(50);
            $table->integer('chance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scorecards');
    }
};
