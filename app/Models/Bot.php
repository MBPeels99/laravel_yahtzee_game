<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    // Indicate that the model shouldn't use timestamps (created_at and updated_at)
    public $timestamps = false;

    // Define the table associated with the model
    protected $table = 'bots';

    // Columns that are mass assignable
    protected $fillable = ['display_name', 'bot_level'];

    /**
     * Define the relationship to the Game model
     */
    public function games() {
        return $this->belongsToMany(Game::class, 'game_bots');
    }
}
