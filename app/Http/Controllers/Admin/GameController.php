<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->orderBy('kickoff', 'desc')->paginate(20);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $teams = Team::all();
        return view('admin.games.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'kickoff' => 'required|date',
            'matchweek' => 'required|integer|min:1',
            'venue' => 'nullable|string|max:255',
        ]);

        Game::create($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game scheduled successfully.');
    }

    public function edit(Game $game)
    {
        $teams = Team::all();
        return view('admin.games.edit', compact('game', 'teams'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'kickoff' => 'required|date',
            'matchweek' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,live,finished',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'venue' => 'nullable|string|max:255',
            'live_minute' => 'nullable|string|max:255',
        ]);

        $game->update($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game updated successfully.');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('admin.games.index')->with('success', 'Game deleted successfully.');
    }
}
