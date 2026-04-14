<?php

use App\Http\Controllers\Match\MatchController;
use Illuminate\Support\Facades\Route;

Route::get('/match', [MatchController::class, 'index'])->name('match');
Route::post('/match/generate', [MatchController::class, 'generate'])->name('match.generate');
