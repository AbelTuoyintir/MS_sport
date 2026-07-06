<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Player;

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

    public function tactics()
    {
        $user = auth()->user();
        $team = Team::with('players')->find($user->team_id);

        if (!$team) {
            return redirect()->route('manager.dashboard')->with('error', 'No team assigned.');
        }

        $players = $team->players;
        return view('manager.tactics', compact('team', 'players'));
    }

    public function updateTactics(Request $request)
    {
        $user = auth()->user();
        $team = Team::find($user->team_id);

        if (!$team) {
             return redirect()->route('manager.dashboard')->with('error', 'No team assigned.');
        }

        $validated = $request->validate([
            'formation' => 'required|string',
            'starting_xi' => 'required|array|min:11|max:11',
            'starting_xi.*' => 'exists:players,id'
        ]);

        $team->update([
            'formation' => $validated['formation'],
            'starting_xi' => $validated['starting_xi']
        ]);

        return back()->with('success', 'Tactics updated successfully.');
    }
}
