<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class StatsController extends Controller
{
    public function index()
    {
        $topScorers = Player::with('team')
            ->where('goals', '>', 0)
            ->orderBy('goals', 'desc')
            ->take(20)
            ->get();

        $topAssists = Player::with('team')
            ->where('assists', '>', 0)
            ->orderBy('assists', 'desc')
            ->take(20)
            ->get();

        $mostYellowCards = Player::with('team')
            ->where('yellow_cards', '>', 0)
            ->orderBy('yellow_cards', 'desc')
            ->take(10)
            ->get();

        $mostRedCards = Player::with('team')
            ->where('red_cards', '>', 0)
            ->orderBy('red_cards', 'desc')
            ->take(10)
            ->get();

        $mostAppearances = Player::with('team')
            ->where('appearances', '>', 0)
            ->orderBy('appearances', 'desc')
            ->take(10)
            ->get();

        return view('stats', compact('topScorers', 'topAssists', 'mostYellowCards', 'mostRedCards', 'mostAppearances'));
    }
}
