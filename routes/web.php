<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\StartScreenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [StartScreenController::class, 'showWelcomePage']);

Route::post('/display-name', [StartScreenController::class, 'storeDisplayName'])->name('store.display.name');

Route::get('/yahtzee', [GameController::class, 'showGameInterface'])->name('game.interface');

Route::post('/game/roll-dice', [GameController::class, 'rollDice'])->name('game.roll');

Route::post('/game/update-score', [GameController::class, 'updateScore'])->name('game.updateScore');
