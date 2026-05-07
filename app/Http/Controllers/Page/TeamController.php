<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Team;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('players')->get();
        return Inertia::render('Teams/Index', [
            'teams' => $teams
        ]);
    }

    public function show(Team $team)
    {
        $team->load(['players', 'titles']);
        return Inertia::render('Teams/Show', [
            'team' => $team
        ]);
    }
}
