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

        $top_scorers = Player::with('team')->orderBy('goals', 'desc')->take(5)->get();
        $recent_activities = collect(); // We can implement a more complex activity logger later if needed

        // For now, let's populate activities from recent events
        $recent_teams = Team::orderBy('created_at', 'desc')->take(3)->get();
        foreach($recent_teams as $team) {
            $recent_activities->push((object)[
                'type' => 'team',
                'message' => "Team registration: " . $team->team_name,
                'timestamp' => $team->created_at,
                'time' => $team->created_at->diffForHumans(),
                'color' => '#f0c040'
            ]);
        }

        $recent_players = Player::orderBy('created_at', 'desc')->take(3)->get();
        foreach($recent_players as $player) {
            $recent_activities->push((object)[
                'type' => 'player',
                'message' => "New player registered: " . $player->name,
                'timestamp' => $player->created_at,
                'time' => $player->created_at->diffForHumans(),
                'color' => '#22c55e'
            ]);
        }

        $recent_games = Game::with(['homeTeam', 'awayTeam'])->where('status', 'finished')->orderBy('updated_at', 'desc')->take(3)->get();
        foreach($recent_games as $game) {
            $recent_activities->push((object)[
                'type' => 'match',
                'message' => "Match result: {$game->homeTeam->team_name} {$game->home_score} - {$game->away_score} {$game->awayTeam->team_name}",
                'timestamp' => $game->updated_at,
                'time' => $game->updated_at->diffForHumans(),
                'color' => '#00e5ff'
            ]);
        }

        $recent_activities = $recent_activities->sortByDesc('timestamp')->take(5);

        return view('admin.dashboard', compact('stats', 'top_scorers', 'recent_activities'));
    }
}
