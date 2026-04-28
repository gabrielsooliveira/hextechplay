<?php

use App\Http\Controllers\LoreQuestion\LoreQuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('lorequestion')->group(function () {
    Route::get('/', [LoreQuestionController::class, 'index'])->name('lorequestion.index');
    Route::post('/roleplay', [LoreQuestionController::class, 'roleplay'])->name('lorequestion.roleplay');
    Route::get('/start', [LoreQuestionController::class, 'startGame'])->name('startGame');
    Route::post('/finish', [LoreQuestionController::class, 'finishGame'])->name('finishGame');
});
