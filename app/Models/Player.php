<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Player extends Model {
    protected $table = 'players';
    protected $fillable = ['display_name', 'username', 'password'];
    protected $hidden = ['password'];

    /**
     * The games that the player is part of.
     */
    public function games() {
        return $this->belongsToMany(Game::class, 'game_players');
    }

    public function getNameAttribute() {
        return $this->attributes['display_name'];
    }

    /**
     * Get the scorecard associated with the player.
     */
    public function scorecard() {
        return $this->hasOne(Scorecard::class);
    }

    public function setPasswordAttribute($value) {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
    
}

