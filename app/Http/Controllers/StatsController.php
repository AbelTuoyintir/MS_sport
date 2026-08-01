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

        $topRated = Player::with('team')
            ->where('rating', '>', 0)
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        $cleanSheets = Player::with('team')
            ->where('clean_sheets', '>', 0)
            ->orderBy('clean_sheets', 'desc')
            ->take(10)
            ->get();

        $motmAwards = Player::with('team')
            ->where('motm_awards', '>', 0)
            ->orderBy('motm_awards', 'desc')
            ->take(10)
            ->get();

        return view('stats', compact(
            'topScorers',
            'topAssists',
            'mostYellowCards',
            'mostRedCards',
            'mostAppearances',
            'topRated',
            'cleanSheets',
            'motmAwards'
        ));
    }
}
