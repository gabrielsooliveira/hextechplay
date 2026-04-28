<?php

use App\Http\Controllers\GuessTheRank\GuessTheRankController;
use Illuminate\Support\Facades\Route;

Route::prefix('guesstherank')->group(function () {
    Route::get('/', [GuessTheRankController::class, 'index'])->name('guesstherank.index');
    Route::post('/game', [GuessTheRankController::class, 'game'])->name('guesstherank.game');
    Route::get('/game', function () {
        return redirect()->route('guesstherank.index');
    });
});
