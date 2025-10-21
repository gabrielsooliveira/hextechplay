<?php

use App\Http\Controllers\LoreQuestion\LoreQuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('lorequestion')->group(function () {
    Route::get('/', [LoreQuestionController::class, 'index'])->name('lorequestion.index');
    Route::get('/roleplay', [LoreQuestionController::class, 'roleplay'])->name('lorequestion.roleplay');
    Route::post('/finish', [LoreQuestionController::class, 'finishGame'])->name('finishGame');
    Route::get('/start', [LoreQuestionController::class, 'startGame'])->name('startGame');
});
