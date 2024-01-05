<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameBot extends Model
{
    // Indicate that the model shouldn't use timestamps (created_at and updated_at)
    public $timestamps = false;

    // Define the table associated with the model
    protected $table = 'game_bots';

    // Columns that are mass assignable
    protected $fillable = ['game_id', 'bot_id'];

    /**
     * Define the relationship to the Game model
     */
    public function game() {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * Define the relationship to the Bot model
     */
    public function bot() {
        return $this->belongsTo(Bot::class, 'bot_id');
    }
}
