<?php

namespace App\Http\Controllers\ClickChallenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ClickChallengerController extends Controller
{
    public function index()
    {
        return inertia('Menu', [
            'currentRoute' => Route::currentRouteName()
        ]);
    }

    public function roleplay(Request $request)
    {
        return inertia('ClickChallenger/Game', [
            'mode' => $request->mode
        ]);
    }
}
