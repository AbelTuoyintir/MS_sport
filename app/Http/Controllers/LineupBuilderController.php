<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;

class LineupBuilderController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::with('players')->get();
        $selectedTeamId = $request->input('team_id', $teams->first()?->id);
        $selectedTeam = $teams->firstWhere('id', $selectedTeamId);

        $formation = $request->input('formation', '4-3-3');

        $players = $selectedTeam ? $selectedTeam->players : Player::limit(20)->get();

        // Calculate team overall rating & chemistry
        $avgRating = round($players->avg('rating') ?? 78);
        $chemistry = min(100, max(50, round($avgRating * 1.05)));

        return view('lineup_builder', compact('teams', 'selectedTeam', 'players', 'formation', 'avgRating', 'chemistry'));
    }
}
