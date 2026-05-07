<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Player;
use Inertia\Inertia;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('team')->get();
        return Inertia::render('Players/Index', [
            'players' => $players
        ]);
    }

    public function show(Player $player)
    {
        $player->load(['team', 'titles']);
        return Inertia::render('Players/Show', [
            'player' => $player
        ]);
    }
}
