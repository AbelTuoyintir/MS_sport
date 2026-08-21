<?php

namespace App\Http\Controllers;

use App\Models\Injury;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class DisciplineInjuryController extends Controller
{
    public function index()
    {
        $injuries = Injury::with(['player.team'])->orderBy('created_at', 'desc')->get();

        // Discipline & Cards Stats
        $teamsWithCards = Team::with(['players' => function($q) {
            $q->orderBy('yellow_cards', 'desc');
        }])->get()->map(function($team) {
            $team->total_yellows = $team->players->sum('yellow_cards');
            $team->total_reds = $team->players->sum('red_cards');
            $team->fair_play_score = max(0, 100 - ($team->total_yellows * 2 + $team->total_reds * 5));
            return $team;
        })->sortByDesc('fair_play_score');

        $topSuspendedPlayers = Player::with('team')
            ->where('red_cards', '>', 0)
            ->orWhere('yellow_cards', '>=', 5)
            ->orderBy('yellow_cards', 'desc')
            ->limit(10)
            ->get();

        return view('discipline_injuries', compact('injuries', 'teamsWithCards', 'topSuspendedPlayers'));
    }
}
