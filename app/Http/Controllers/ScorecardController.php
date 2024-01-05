<?php

namespace App\Http\Controllers;

use App\Models\Scorecard;
use Illuminate\Support\Facades\Log;

class ScorecardController {

    public function setScore(Scorecard $scorecard, $field, $value) {
        if (in_array($field, $this->getValidScoreFields())) {
            $scorecard->$field = $value;
            $scorecard->save();
        } else {
            throw new \Exception("Invalid score field: $field");
        }
    }

    public function getScore(Scorecard $scorecard, $field) {
        if (in_array($field, $this->getValidScoreFields())) {
            return $scorecard->$field;
        } else {
            throw new \Exception("Invalid score field: $field");
        }
    }
    
    private function getValidScoreFields() {
        return [
            'aces', 'twos', 'threes', 'fours', 'fives', 'sixes',
            'three_of_a_kind', 'four_of_a_kind', 'full_house',
            'small_straight', 'large_straight', 'yahtzee', 'chance'
        ];
    }

    public function calculateUpperSectionScore($dice, $value) {
        $count = array_reduce($dice, function($carry, $item) use ($value) {
            return $item == $value ? $carry + 1 : $carry;
        });
        return $count * $value;
    }
    
    public function calculateThreeOfAKind($dice) {
        return $this->hasNOfAKind($dice, 3) ? array_sum($dice) : 0;
    }

    public function calculateFourOfAKind($dice) {
        return $this->hasNOfAKind($dice, 4) ? array_sum($dice) : 0;
    }

    public function calculateFullHouse($dice) {
        $values = array_count_values($dice);
        return (in_array(2, $values) && in_array(3, $values)) ? Scorecard::$full_house : 0;
    }

    public function calculateSmallStraight($dice) {
        sort($dice);
        $str = implode('', array_unique($dice));
        return (strpos($str, '1234') !== false || strpos($str, '2345') !== false || strpos($str, '3456') !== false) 
                ? Scorecard::$small_straight : 0;
    }

    public function calculateLargeStraight($dice) {
        sort($dice);
        $str = implode('', $dice);
        return ($str == '12345' || $str == '23456') ? Scorecard::$large_straight : 0;
    }

    public function calculateYahtzee($dice) {
        return count(array_unique($dice)) == 1 ? Scorecard::$yahtzee : 0;
    }

    public function calculateChance($dice) {
        return array_sum($dice);
    }

    private function hasNOfAKind($dice, $n) {
        $values = array_count_values($dice);
        foreach ($values as $count) {
            if ($count >= $n) return true;
        }
        return false;
    }
}
