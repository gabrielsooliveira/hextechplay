<?php

use App\Http\Controllers\ClickChallenger\ClickChallengerController;
use Illuminate\Support\Facades\Route;

Route::prefix('clickchallenger')->group(function () {
    Route::get('/', [ClickChallengerController::class, 'index'])->name('clickchallenger.index');
    Route::get('/roleplay', [ClickChallengerController::class, 'roleplay'])->name('clickchallenger.roleplay');
});
