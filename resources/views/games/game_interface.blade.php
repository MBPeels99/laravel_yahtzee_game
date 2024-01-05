@extends('layouts.layout')

@section('content')
<div class="container">
    <h1 class="text-center">Yahtzee Game</h1>
    <h1 class="text-center" id="round-display" name="round-display">Yahtzee Game! - Round 1 of 13</h1>
    <h2 id="turn-display" name="turn-display">Player Turn 1 of 3</h2>

    <div class="main-container">
            <!-- Display the dice -->
            <div class="game-area">
                <h2>Dice</h2>
                
                <!-- Placeholder for dice images/values with default dice position of 6 -->
                <div id="dice-container">
                    @for($i = 0; $i < 5; $i++)
                    <span class="dice" data-value="1"></span>
                    @endfor
                </div>
                <br />
                <button class="btn btn-primary mt-3" id="roll-dice">Roll Dice</button>
                <div id="select-category" style="display: none;">
                    <h2>Select a Category!</h2>
                </div>
                <div id="game-over" style="display: none;">
                    <h2>Game Over!</h2>
                </div>
            </div>

            <!-- Scoreblock Section -->
            <div class="scoreblock">
                <h2>Scoreblock</h2>
                <table>
                    <!-- Upper Section Headers -->
                    <tr>
                        <th>Upper Section</th><th>Score</th><th>Points</th>
                    </tr>
                    <!-- Aces Row -->
                    <tr data-score="aces"><th>ACES</th><th>TOTAL 1'S</th><td id="score-aces">0</td>
                    </tr>
                    <!-- Twos Row -->
                    <tr data-score="twos"><th>TWOS</th><th>TOTAL 2'S</th><td id="score-twos">0</td>
                    </tr>
                    <!-- Threes Row -->
                    <tr data-score="threes"><th>THREES</th><th>TOTAL 3'S</th><td id="score-threes">0</td>
                    </tr>
                    <!-- Fours Row -->
                    <tr data-score="fours"><th>FOURS</th><th>TOTAL 4'S</th><td id="score-fours">0</td>
                    </tr>
                    <!-- Fives Row -->
                    <tr data-score="fives"><th>FIVES</th><th>TOTAL 5'S</th><td id="score-fives">0</td>
                    </tr>
                    <!-- Sixes Row -->
                    <tr data-score="sixes"><th>SIXES</th><th>TOTAL 6'S</th><td id="score-sixes">0</td>
                    </tr>
                    <!-- Total Upper Row -->
                    <tr><th>TOTAL POINTS</th><th>------ ></th><td id="total-upper">0</td>
                    </tr>
                    <!-- Bonus Row -->
                    <tr><th>EXTRA BONUS<br/>If Total score is 63 or more</th><th>35 POINTS</th><td id="bonus-upper">0</td>
                    </tr>
                    <!-- Total Part 1 Row -->
                    <tr><th>TOTAL Part 1</th><th>------ ></th><td id="total-upper-part1">0</td>
                    </tr>
                    <!-- Lower Section Headers -->
                    <tr><th>Lower Section</th><th>Score</th><th>Points</th>
                    </tr>
                    <!-- 3 of a kind Row -->
                    <tr data-score="three_of_a_kind"><th>3 OF A KIND</th><th>Total of all dice</th><td id="score-three_of_a_kind">0</td>
                    </tr>
                    <!-- 4 of a kind Row -->
                    <tr data-score="four_of_a_kind"><th>4 OF A KIND</th><th>Total of all dice</th><td id="score-four_of_a_kind">0</td>
                    </tr>
                    <!-- Full House Row -->
                    <tr data-score="full_house"><th>FULL HOUSE</th><th>25 POINTS</th><td id="score-full_house">0</td>
                    </tr>
                    <!-- Small Straight Row -->
                    <tr data-score="small_straight"><th>SMALL STRAIGHT</th><th>30 POINTS</th><td id="score-small_straight">0</td>
                    </tr>
                    <!-- Large Straight Row -->
                    <tr data-score="large_straight"><th>LARGE STRAIGHT</th><th>40 POINTS</th><td id="score-large_straight">0</td>
                    </tr>
                    <!-- Yahtzee Row -->
                    <tr data-score="yahtzee"><th>YAHTZEE</th><th>50 POINTS</th><td id="score-yahtzee">0</td>
                    </tr>
                    <!-- Chance Row -->
                    <tr data-score="chance"><th>CHANCE</th><th>Total of all dice</th><td id="score-chance">0</td>
                    </tr>
                    <!-- Yahtzee Bonus Row -->
                    <tr data-score="yahtzee_bonus"><th>YAHTZEE BONUS</th><th>100 POINTS per bonus Yahtzee</th><td id="score-yahtzee_bonus">0</td>
                    </tr>
                    <!-- Total Lower Row -->
                    <tr><th>TOTAL Part 2</th><th>------ ></th><td id="total-lower">0</td>
                    </tr>
                    <!-- Grand Total Row -->
                    <tr><th>GRAND TOTAL</th><th>------ ></th><td id="grand-total">0</td>
                    </tr>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let selectedDice = [];
let round = 1;
let turn = 0;   

function updateDisplay() {
    document.getElementById('round-display').innerText = `Yahtzee Game! - Round ${round} of 13`;
    document.getElementById('turn-display').innerText = `Player Turn ${turn} of 3`;
}

function gameOver() {
    document.getElementById('roll-dice').style.display = 'none';
    document.getElementById('game-over').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    const rollButton = document.getElementById('roll-dice');
    const diceContainer = document.getElementById('dice-container');     

    document.getElementById('roll-dice').style.display = 'block';
    document.getElementById('select-category').style.display = 'none';

    rollButton.addEventListener('click', function() {
        handleRollDice(diceContainer);
    });

    handleRollDice(diceContainer);

    // get all rows with a data-score attribute
    const scoreRows = document.querySelectorAll('tr[data-score]');
    
    // add a click event listener to each row
    scoreRows.forEach(row => {
        row.addEventListener('click', function() {
            const scoreType = this.getAttribute('data-score');
            handleScoreUpdate(scoreType);
        });
    });

    document.addEventListener('click', function(event) {
        handleDiceClick(event);
    });
});

function handleRollDice(diceContainer) {
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const diceElements = document.querySelectorAll('.dice');
    const values = [];
    const lock = [];

    diceElements.forEach(dice => {
        values.push(parseInt(dice.getAttribute('data-value')));
        lock.push(dice.classList.contains('selected'));
    });

    turn += 1;
    if (turn == 3) {
        document.getElementById('roll-dice').style.display = 'none';
        document.getElementById('select-category').style.display = 'block'; // Display a message to prompt user to select a category
    }

    const payload = {
        dice: {
            values: values,
            lock: lock
        }
    };

    fetch('/game/roll-dice', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(payload)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.json();
    })
    .then(data => {
        let diceHtml = '';
        for (let i = 0; i < data.dice.length; i++) {
            diceHtml += `<span class="dice" data-value="${data.dice[i]}"></span>`;
        }
        diceContainer.innerHTML = diceHtml;

        selectedDice = [];
    })
    .catch(error => {
        console.error('There was an error rolling the dice:', error);
    });

    updateDisplay();
}

function handleScoreUpdate(scoreType) {
    if(turn >= 3){
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let diceContainer = document.getElementById('dice-container'); 

        const payload = {
            category: scoreType
        };
        console.log(payload);

        fetch('/game/update-score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            document.getElementById(`score-${scoreType}`).innerText = data.score;

            if (turn >= 3) {
                turn = 0;
                document.getElementById('roll-dice').style.display = 'block';
                document.getElementById('select-category').style.display = 'none';
                handleRollDice(diceContainer);
                round += 1;
            }
            updateDisplay();

            if (round > 13) {
                gameOver();
            }
        })
        .catch(error => {
            console.error('There was an error updating the score:', error);
        });
    }
}


function handleDiceClick(event) {
    if (event.target.classList.contains('dice')) {
        event.target.classList.toggle('selected');
        
        const diceIndex = [...event.target.parentElement.children].indexOf(event.target);

        if (event.target.classList.contains('selected')) {
            selectedDice.push(diceIndex);
        } else {
            const index = selectedDice.indexOf(diceIndex);
            if (index > -1) {
                selectedDice.splice(index, 1);
            }
        }

        console.log(selectedDice); // This line is for debugging.
    }
}

</script>
@endpush

@push('styles')
<style>
    h2 {
    text-align: center;
    }

    .main-container {
        display: flex;
        justify-content: space-between; /* This will push the child elements to the edges of the container */
        align-items: flex-start; /* This will align the tops of the child divs */
    }

    .game-area {
        flex: 1; /* This will allow the game area to take up any remaining space in the container */
        display: flex;
        flex-direction: column;
        align-items: center; /* This will center the dice horizontally */
    }

    .game-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    h1.text-center {
        text-align: center;
    }

    .scoreblock table {
        border-collapse: collapse;
        width: 100%;
    }

    .scoreblock th, .scoreblock td {
        border: 1px solid #000;
        padding: 8px 12px;
        text-align: left;
    }

    .scoreblock tr:hover {
        background-color: #f5f5f5;
        cursor: pointer;
    }

    .scoreblock {
        flex: 1; /* Let the score block also take remaining space */
        margin-left: 20px; /* Space between game area and score block */
    }

    .dice {
        background-image: url('{{ asset("images/dice_expanded.png") }}');
        background-size: 635px 272px; /* Adjusted for 6 dice faces each 635px wide */
        display: inline-block;
        width: 79px;
        height: 79px;
        border: 1px solid #000;
        line-height: 50px;
        text-align: center;
        margin: 0 10px;
        border-radius: 8px; /* Add rounded corners */
        background-color: #fff; /* White background */
        font-weight: bold;
        cursor: pointer; /* Makes it feel clickable */
        user-select: none; /* Disables text selection */
    }

    .dice[data-value="1"] { background-position: -27px -48px; }
    .dice[data-value="2"] { background-position: -121.5px -48px; }
    .dice[data-value="3"] { background-position: -223.5px -48px; }
    .dice[data-value="4"] { background-position: -325.5px -48px; }
    .dice[data-value="5"] { background-position: -427.5px -48px; }
    .dice[data-value="6"] { background-position: -529.5px -48px; }

    .dice.selected[data-value="1"] { background-position: -27px -145px; }
    .dice.selected[data-value="2"] { background-position: -121.5px -145px; }
    .dice.selected[data-value="3"] { background-position: -223.5px -145px; }
    .dice.selected[data-value="4"] { background-position: -325.5px -145px; }
    .dice.selected[data-value="5"] { background-position: -427.5px -145px; }
    .dice.selected[data-value="6"] { background-position: -529.5px -145px; }

    .player-scorecard {
        background-color: #f7f7f7;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
    }

    .dice.selected {
        opacity: 0.7; /* or any other style you want to indicate selection */
        background-color: #f5f5f5;
    }

</style>
@endpush