<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use App\Models\Article;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_teams' => Team::count(),
            'total_players' => Player::count(),
            'total_matches' => Game::count(),
            'total_goals' => Player::sum('goals'),
        ];

        $recent_teams = Team::orderBy('created_at', 'desc')->take(5)->get();
        $recent_games = Game::with(['homeTeam', 'awayTeam'])->orderBy('kickoff', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_teams', 'recent_games'));
    }
}
