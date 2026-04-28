<?php

namespace App\Http\Controllers\GuessTheRank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class GuessTheRankController extends Controller
{
    public function index()
    {
        return Inertia::render('Menu', [
            'currentRoute' => Route::currentRouteName()
        ]);
    }

    public function game(Request $request)
    {
        return Inertia::render('GuessTheRank/Game', [
            'difficulty' => $request->input('difficulty', 'easy'),
            'questions' => $request->input('questionQuant', 5),
        ]);
    }
}
