<?php

use App\Http\Controllers\ClickChallenger\ClickChallengerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('clickchallenger')->group(function () {
    Route::get('/', [ClickChallengerController::class, 'index'])->name('clickchallenger.index');
    Route::post('/roleplay', [ClickChallengerController::class, 'roleplay'])->name('clickchallenger.roleplay');
    Route::get('/roleplay', function () {
        return redirect()->route('clickchallenger.index');
    });
    Route::post('/score', [ClickChallengerController::class, 'submitScore'])->name('clickchallenger.score');
});
