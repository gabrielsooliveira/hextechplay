<?php

use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

foreach (glob(__DIR__ . '/web/*.php') as $routeFile) {
    require $routeFile;
}
