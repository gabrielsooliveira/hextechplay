<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return inertia('Errors/NotFound')->toResponse(request())->setStatusCode(404);
})->name('notFound');
