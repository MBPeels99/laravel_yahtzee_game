<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model {

    protected $table = 'scorecards';

    protected $fillable = [
        'aces', 'twos', 'threes', 'fours', 'fives', 'sixes',
        'three_of_a_kind', 'four_of_a_kind', 'full_house',
        'small_straight', 'large_straight', 'yahtzee', 'chance'
    ];

    // Upper Section Scores - These are dynamic scores based on dice values and not set as static
    public $aces = 0;
    public $twos = 0;
    public $threes = 0;
    public $fours = 0;
    public $fives = 0;
    public $sixes = 0;

    // Lower Section Scores - These have static values
    public static $three_of_a_kind = 0; // This could be dynamic if you base it on dice values
    public static $four_of_a_kind = 0;  // This could be dynamic if you base it on dice values
    public static $full_house = 25;
    public static $small_straight = 30;
    public static $large_straight = 40;
    public static $yahtzee = 50;
    public static $chance = 0; // This could be dynamic if you base it on dice values

    public function player() {
        return $this->belongsTo(Player::class);
    }
}
