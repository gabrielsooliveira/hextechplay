<?php

use Illuminate\Support\Facades\Route;

Route::get('/clickchallenger', function () {
    return inertia("ClickChallenger/Game");
})->name('clickchallenger.game');
