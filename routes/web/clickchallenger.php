<?php

use App\Http\Controllers\ClickChallenger\ClickChallengerController;
use Illuminate\Support\Facades\Route;

Route::prefix('clickchallenger')->group(function () {
    Route::get('/', [ClickChallengerController::class, 'index'])->name('clickchallenger.index');
    Route::post('/roleplay', [ClickChallengerController::class, 'roleplay'])->name('clickchallenger.roleplay');
    Route::post('/submit-score', [ClickChallengerController::class, 'submitScore'])->name('clickchallenger.submitScore');
});
