<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model {
    // Indicate table associated with the model
    protected $table = 'games';

    // Columns that are mass assignable
    protected $fillable = ['game_state', 'created_at', 'updated_at', 'completed'];

    // Relationship: a game can have many players
    public function players() {
        return $this->belongsToMany(Player::class, 'game_players');
    }

    // Any other model-specific business logic or helper methods can be added here.
}
