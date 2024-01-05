<?php

namespace App\Http\Controllers;

use App\Models\Player;

class PlayerController {

    public function setScorecard(Player $player, $scorecard) {
        // Logic to set the scorecard for a player
        $player->scorecard()->save($scorecard);
    }

    public function getScorecard(Player $player) {
        // Fetch and return the scorecard for a player
        return $player->scorecard;
    }

    // You might have more game-specific methods related to players here
}
