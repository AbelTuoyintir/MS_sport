<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class PublicGameController extends Controller
{
    public function show($id)
    {
        $game = Game::with([
            'homeTeam.players',
            'awayTeam.players',
            'events.player',
            'events.team'
        ])->findOrFail($id);

        return view('matches.show', compact('game'));
    }
}
