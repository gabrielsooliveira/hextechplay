<?php

namespace App\Http\Controllers\Match;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index()
    {
        return inertia('Match');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'players' => 'required|array|size:10',
            'players.*.name' => 'required|string',
            'players.*.elo' => 'required|numeric',
        ]);

        $players = $request->players;

        $combinations = $this->getCombinations(array_keys($players), 5);
        
        $bestDiff = null;
        $bestTeamAIndices = [];
        $totalElo = array_sum(array_column($players, 'elo'));

        foreach ($combinations as $combo) {
            $sumA = 0;
            foreach ($combo as $index) {
                $sumA += $players[$index]['elo'];
            }
            $sumB = $totalElo - $sumA;
            $diff = abs($sumA - $sumB);

            if ($bestDiff === null || $diff < $bestDiff) {
                $bestDiff = $diff;
                $bestTeamAIndices = $combo;
                if ($bestDiff === 0) {
                    break; // Found perfect match
                }
            }
        }

        $teamA = [];
        $teamB = [];
        foreach ($players as $index => $player) {
            if (in_array($index, $bestTeamAIndices)) {
                $teamA[] = $player;
            } else {
                $teamB[] = $player;
            }
        }

        return response()->json([
            'team1' => $teamA,
            'team2' => $teamB,
            'team1_elo' => array_sum(array_column($teamA, 'elo')),
            'team2_elo' => array_sum(array_column($teamB, 'elo')),
            'diff' => $bestDiff
        ]);
    }

    private function getCombinations($array, $length) {
        $combinations = [];
        $combinationsHelper = function($sequence, $startIndex) use (&$combinationsHelper, &$combinations, $array, $length) {
            if (count($sequence) == $length) {
                $combinations[] = $sequence;
                return;
            }
            for ($i = $startIndex; $i < count($array); $i++) {
                $newSequence = array_merge($sequence, [$array[$i]]);
                $combinationsHelper($newSequence, $i + 1);
            }
        };
        $combinationsHelper([], 0);
        return $combinations;
    }
}
