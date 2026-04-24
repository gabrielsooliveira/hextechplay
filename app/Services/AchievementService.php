<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;

class AchievementService
{
    /**
     * Check and award badges based on a specific metric and game.
     */
    public function checkAchievements(User $user, string $game, string $requirementType, int $currentValue, ?string $mode = null): array
    {
        $newlyUnlocked = [];

        // Get all badges for this game and requirement type and mode
        $query = Badge::where('game', $game)
            ->where('requirement_type', $requirementType);
            
        if ($mode !== null) {
            $query->where(function($q) use ($mode) {
                $q->where('mode', $mode)->orWhereNull('mode');
            });
        } else {
            $query->whereNull('mode');
        }

        $badges = $query->get();

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
    public function incrementStatAndCheck(User $user, string $game, string $stat, int $amount = 1, ?string $mode = null): array
    {
        $stats = $user->game_stats ?? [];
        $statKey = $mode ? "{$stat}_{$mode}" : $stat;
        
        if (!isset($stats[$game])) {
            $stats[$game] = [];
        }

        if (!isset($stats[$game][$statKey])) {
            $stats[$game][$statKey] = 0;
        }

        $stats[$game][$statKey] += $amount;

        // Save stat
        $user->game_stats = $stats;
        $user->save();

        // Return newly unlocked achievements
        return $this->checkAchievements($user, $game, $stat, $stats[$game][$statKey], $mode);
    }

    /**
     * Update a high score stat if it's higher than the previous, and check achievements.
     */
    public function updateHighScoreAndCheck(User $user, string $game, string $stat, int $score, ?string $mode = null): array
    {
        $stats = $user->game_stats ?? [];
        $statKey = $mode ? "{$stat}_{$mode}" : $stat;
        
        if (!isset($stats[$game])) {
            $stats[$game] = [];
        }

        $currentHigh = $stats[$game][$statKey] ?? 0;

        if ($score > $currentHigh) {
            $stats[$game][$statKey] = $score;
            $user->game_stats = $stats;
            $user->save();
        }

        // We check achievements against the highest score
        return $this->checkAchievements($user, $game, $stat, $stats[$game][$statKey], $mode);
    }
}
