<?php

use App\Http\Controllers\Page\PageController;
use App\Http\Controllers\User\TermsController;
use App\Http\Controllers\Page\TeamController;
use App\Http\Controllers\Page\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/games', [PageController::class, 'games'])->name('games');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/partners', [PageController::class, 'partners'])->name('partners');
Route::get('/privacy-policy', [PageController::class, 'privatePolicy'])->name('privacy.policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');

Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
Route::get('/players/{player}', [PlayerController::class, 'show'])->name('players.show');

Route::post('/contact/send', [PageController::class, 'send']);
Route::post('/terms/accept', [TermsController::class, 'accept'])->name('terms.accept');
