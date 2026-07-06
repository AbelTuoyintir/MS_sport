<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\Game;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        $top_scorers = Player::with('team')->orderBy('goals', 'desc')->take(10)->get();
        $top_assists = Player::with('team')->orderBy('assists', 'desc')->take(10)->get();
        $most_yellows = Player::with('team')->orderBy('yellow_cards', 'desc')->take(10)->get();
        $most_reds = Player::with('team')->orderBy('red_cards', 'desc')->take(10)->get();
        $most_appearances = Player::with('team')->orderBy('appearances', 'desc')->take(10)->get();

        $team_stats = Team::with('players')->get()->map(function($team) {
            return (object) [
                'id' => $team->id,
                'name' => $team->team_name,
                'color' => $team->primary_color,
                'goals' => $team->players->sum('goals'),
                'yellow_cards' => $team->players->sum('yellow_cards'),
                'red_cards' => $team->players->sum('red_cards'),
            ];
        })->sortByDesc('goals');

        return view('stats', compact('top_scorers', 'top_assists', 'most_yellows', 'most_reds', 'most_appearances', 'team_stats'));
    }
}
