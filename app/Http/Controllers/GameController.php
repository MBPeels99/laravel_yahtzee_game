<?php
namespace App\Http\Controllers;

use App\Logic\GameLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;



class GameController extends Controller{

    private $gameLogic;
    public $dice = [1,1,1,1,1];
    public $lockedDice = [false, false, false, false, false];
    public $numOfDice = 5;
    public $round = 1;
    public $turn = 0;

    public function __construct(GameLogic $gameLogic) {
        $this->gameLogic = $gameLogic;
        /*$displayName = Session::get('displayName');
        log::info("GameController displayName: $displayName");
        // Initialize the game with just one player for now
        $this->gameLogic->initializeGame([session('displayName')]);*/
    }
    
    public function showGameInterface(){
        $displayName = session('displayName');
        Log::info("GameController displayName: $displayName");
        // If needed, initialize your game logic here as well
        $this->gameLogic->initializeGame([$displayName]);
    
        // Load necessary data and show the game interface view.
        return view('games.game_interface');
    }
    
    public function rollDice(Request $request) {
        $data = $request->json()->all(); 
        log::info("This is the data value in rollDice", $data);      
        $this->gameLogic->rollUnlockedDice($data);
        $diceValues = $this->gameLogic->getDiceValues();
        log::info("These are the dice per roll:",$diceValues);
        $this->turn++;        
        return response()->json(['dice' => $diceValues]);
    }
    
    public function updateScore(Request $request) {
        // Fetch the category the user wants to score for and the current dice values
        $category = $request->input('category');
        $diceValues = $this->gameLogic->getDiceValues();
        log::info("Dice values in update score:", $diceValues);

        $scorecard = $this->gameLogic->getCurrentPlayerScorecard();
        
        try {
             // Based on category, call the relevant method from Scorecard to calculate and set the score
            switch($category) {
                case 'aces':
                case 'twos':
                case 'threes':
                case 'fours':
                case 'fives':
                case 'sixes':
                    $value = substr($category, 0, -1) == 'ace' ? 1 : intval(substr($category, 0, 1));
                    $scorecard->setScore($category, $scorecard->calculateUpperSectionScore($diceValues, $value));
                    break;
                case 'three_of_a_kind':
                    $scorecard->setScore($category, $scorecard->calculateThreeOfAKind($diceValues));
                    break;
                case 'four_of_a_kind':
                    $scorecard->setScore($category, $scorecard->calculateFourOfAKind($diceValues));
                    break;
                case 'full_house':
                    $scorecard->setScore($category, $scorecard->calculateFullHouse($diceValues));
                    break;
                case 'small_straight':
                    $scorecard->setScore($category, $scorecard->calculateSmallStraight($diceValues));
                    break;
                case 'large_straight':
                    $scorecard->setScore($category, $scorecard->calculateLargeStraight($diceValues));
                    break;
                case 'yahtzee':
                    $scorecard->setScore($category, $scorecard->calculateYahtzee($diceValues));
                    break;
                case 'chance':
                    $scorecard->setScore($category, $scorecard->calculateChance($diceValues));
                    break;
                default:
                    throw new \Exception("Invalid category: $category");
            }

            // You can now save this updated score in the database if needed
            // ...

            return response()->json(['score' => $scorecard->getScore($category)]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}

?>
