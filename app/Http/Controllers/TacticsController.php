<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Team;

class TacticsController extends Controller
{
    public function index()
    {
        $team = auth()->user()->team;
        $players = $team->players;
        $formation = $team->formation ?? '4-4-2';
        $starting_xi = $team->starting_xi ?? [];

        return view('manager.tactics', compact('team', 'players', 'formation', 'starting_xi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'formation' => 'required|string',
            'starting_xi' => 'required|array|min:11|max:11',
            'starting_xi.*' => 'exists:players,id'
        ]);

        $team = auth()->user()->team;

        // Verify all players belong to this team
        foreach ($request->starting_xi as $playerId) {
            $player = Player::find($playerId);
            if ($player->team_id !== $team->id) {
                return redirect()->back()->with('error', 'Invalid player selection.');
            }
        }

        $team->update([
            'formation' => $request->formation,
            'starting_xi' => $request->starting_xi
        ]);

        return redirect()->route('manager.tactics.index')->with('success', 'Tactics updated successfully.');
    }
}
