<?php

use App\Http\Controllers\LoreQuestion\LoreQuestionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('lorequestion')->group(function () {
    Route::get('/', [LoreQuestionController::class, 'index'])->name('lorequestion.index');
    Route::post('/roleplay', [LoreQuestionController::class, 'roleplay'])->name('lorequestion.roleplay');
    Route::get('/roleplay', function () {
        return redirect()->route('lorequestion.index');
    });
    Route::post('/finish', [LoreQuestionController::class, 'finishGame'])->name('finishGame');
    Route::get('/start', [LoreQuestionController::class, 'startGame'])->name('startGame');
});
