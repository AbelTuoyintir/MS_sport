<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $team = auth()->user()->team;

        if (!$team) {
            return view('manager.dashboard', ['upcoming_games' => collect(), 'recent_results' => collect()]);
        }

        $upcoming_games = Game::with(['homeTeam', 'awayTeam'])
            ->where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'upcoming')
            ->orderBy('kickoff', 'asc')
            ->take(5)
            ->get();

        $recent_results = Game::with(['homeTeam', 'awayTeam'])
            ->where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'finished')
            ->orderBy('kickoff', 'desc')
            ->take(5)
            ->get();

        $staff = $team->staff;

        return view('manager.dashboard', compact('upcoming_games', 'recent_results', 'staff'));
    }
}
