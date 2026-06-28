<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('primaryOwner')->get();
        return response()->json($teams);
    }

    public function show($id)
    {
        $team = Team::with(['owners', 'payment', 'players'])->findOrFail($id);
        return response()->json($team);
    }

    public function showPublic($id)
    {
        $team = Team::with(['players' => function($query) {
            $query->orderBy('goals', 'desc');
        }])->findOrFail($id);

        $recent_games = \App\Models\Game::with(['homeTeam', 'awayTeam'])
            ->where(function($query) use ($id) {
                $query->where('home_team_id', $id)
                      ->orWhere('away_team_id', $id);
            })
            ->orderBy('kickoff', 'desc')
            ->take(10)
            ->get();

        return view('teams.show', compact('team', 'recent_games'));
    }
}
