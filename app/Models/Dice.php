<?php

namespace App\Models;

class Dice {
    private $value;

    public function __construct() {
        $this->roll();
    }

    public function roll() {
        $this->value = rand(1, 6);
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        if ($value >= 1 && $value <= 6) {
            $this->value = $value;
        }
    }
    
}
