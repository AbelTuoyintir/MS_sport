<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('home');
        }

        $teams = Team::where('team_name', 'LIKE', "%{$query}%")
            ->orWhere('city', 'LIKE', "%{$query}%")
            ->get();

        $players = Player::with('team')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('nationality', 'LIKE', "%{$query}%")
            ->get();

        return view('search.results', compact('teams', 'players', 'query'));
    }
}
