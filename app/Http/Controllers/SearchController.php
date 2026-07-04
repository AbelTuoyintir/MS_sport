<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $teams = [];
        $players = [];

        if ($query) {
            $teams = Team::where('team_name', 'LIKE', "%{$query}%")
                ->orWhere('city', 'LIKE', "%{$query}%")
                ->get();

            $players = Player::with('team')
                ->where('name', 'LIKE', "%{$query}%")
                ->get();
        }

        return view('search', compact('teams', 'players', 'query'));
    }
}
