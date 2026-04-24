<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;

class AchievementService
{
    /**
     * Check and award badges based on a specific metric and game.
     */
    public function checkAchievements(User $user, string $game, string $requirementType, int $currentValue): array
    {
        $newlyUnlocked = [];

        // Get all badges for this game and requirement type
        $badges = Badge::where('game', $game)
            ->where('requirement_type', $requirementType)
            ->get();

        $userBadgeIds = $user->badges()->pluck('badges.id')->toArray();

        foreach ($badges as $badge) {
            if (!in_array($badge->id, $userBadgeIds)) {
                // If the user meets or exceeds the requirement value
                if ($currentValue >= $badge->requirement_value) {
                    $user->badges()->attach($badge->id);
                    $newlyUnlocked[] = $badge;
                }
            }
        }

        return $newlyUnlocked; // Array of badges unlocked in this check
    }

    /**
     * Increment a specific stat for a user and check achievements.
     */
    public function incrementStatAndCheck(User $user, string $game, string $stat, int $amount = 1): array
    {
        $stats = $user->game_stats ?? [];
        
        if (!isset($stats[$game])) {
            $stats[$game] = [];
        }

        if (!isset($stats[$game][$stat])) {
            $stats[$game][$stat] = 0;
        }

        $stats[$game][$stat] += $amount;

        // Save stat
        $user->game_stats = $stats;
        $user->save();

        // Return newly unlocked achievements
        return $this->checkAchievements($user, $game, $stat, $stats[$game][$stat]);
    }

    /**
     * Update a high score stat if it's higher than the previous, and check achievements.
     */
    public function updateHighScoreAndCheck(User $user, string $game, string $stat, int $score): array
    {
        $stats = $user->game_stats ?? [];
        
        if (!isset($stats[$game])) {
            $stats[$game] = [];
        }

        $currentHigh = $stats[$game][$stat] ?? 0;

        if ($score > $currentHigh) {
            $stats[$game][$stat] = $score;
            $user->game_stats = $stats;
            $user->save();
        }

        // We check achievements against the highest score
        return $this->checkAchievements($user, $game, $stat, $stats[$game][$stat]);
    }
}
