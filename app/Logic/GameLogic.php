<?php

namespace App\Logic;

use App\Models\Player;
use App\Models\Dice;
use Illuminate\Support\Facades\Log;

class GameLogic {
    private $players = [];
    private $dice = [];
    private $currentPlayerIndex = 0;
    private $numOfDice = 5;

    public function __construct() {
        // Initialize dice
        for ($i = 0; $i < $this->numOfDice; $i++) {
            $this->dice[] = new Dice();
        }
    }

    public function initializeGame($playerNames) {
        $this->players = [];  // Clear any existing players

        // If $playerNames is a string (single player name), convert it to an array
        if (is_string($playerNames)) {
            $playerNames = [$playerNames];
        }

        log::info("This is playerNames = ",$playerNames);

        foreach ($playerNames as $name) {
            $player = Player::create(['display_name' => $name]);
            $this->players[] = $player;
        }
    }
    

    public function rollDice() {
        foreach ($this->dice as $dice) {
            $dice->roll();
        }
    }

    public function nextPlayer() {
        $this->currentPlayerIndex = ($this->currentPlayerIndex + 1) % count($this->players);
    }

    public function rollUnlockedDice($data) {
        $values = $data['dice']['values'] ?? [1,1,1,1,1];
        $lock = $data['dice']['lock'] ?? [false, false, false, false, false];
        Log::info("The dice values in the method rollUnlockedDice are:", $values);
        foreach ($this->dice as $index => $dice) {
            if ($lock[$index]) {
                $dice->setValue($values[$index]);
            } else {
                $dice->roll();
            }
        }
    }
    
    public function getDiceValues() {
        $values = [];
        foreach ($this->dice as $dice) {
            $values[] = $dice->getValue();
        }
        Log::info("These are values that are in getDiceValues: ", $values);
        return $values;
    }
    
    public function getCurrentPlayerScorecard() {
        $playerController = new \App\Http\Controllers\PlayerController();
        $scorecard = $playerController->getScorecard($this->players[$this->currentPlayerIndex]);
        Log::info("Current player's scorecard:", [$scorecard]);
        return $scorecard;
    }
}
