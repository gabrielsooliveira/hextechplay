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

    public function submitScore(Request $request, \App\Services\AchievementService $achievementService)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0',
        ]);

        if (auth()->check()) {
            $unlockedBadges = $achievementService->updateHighScoreAndCheck(auth()->user(), 'ClickChallenger', 'score', $validated['score']);
            return response()->json(['success' => true, 'new_badges' => $unlockedBadges]);
        }

        return response()->json(['success' => true]);
    }
}
