<?php

namespace App\Http\Controllers\ClickChallenger;

use App\Http\Controllers\Controller;
use App\Services\AchievementService;
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
        $mode = $request->input('mode', 'classic');

        if (!auth()->check()) {
            $mode = 'classic';
        }

        return inertia('ClickChallenger/Game', [
            'mode' => $mode
        ]);
    }

    public function submitScore(Request $request, AchievementService $achievementService)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0',
            'mode' => 'nullable|string',
        ]);

        if (auth()->check()) {
            $unlockedBadges = $achievementService->updateHighScoreAndCheck(
                auth()->user(),
                'ClickChallenger',
                'score',
                $validated['score'],
                $validated['mode'] ?? null
            );
            return response()->json(['success' => true, 'new_badges' => $unlockedBadges]);
        }

        return response()->json(['success' => true]);
    }
}
