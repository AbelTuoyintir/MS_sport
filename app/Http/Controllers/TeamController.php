<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('primaryOwner')->get();
        return response()->json($teams);
    }

    public function show($id)
    {
        $team = Team::with(['owners', 'payment', 'players'])->findOrFail($id);
        return response()->json($team);
    }

    public function showPublic($id)
    {
        $team = Team::with(['players' => function($query) {
            $query->orderBy('goals', 'desc');
        }])->findOrFail($id);

        $recent_games = \App\Models\Game::with(['homeTeam', 'awayTeam'])
            ->where(function($query) use ($id) {
                $query->where('home_team_id', $id)
                      ->orWhere('away_team_id', $id);
            })
            ->where('status', 'finished')
            ->orderBy('kickoff', 'desc')
            ->take(5)
            ->get();

        $upcoming_games = \App\Models\Game::with(['homeTeam', 'awayTeam'])
            ->where(function($query) use ($id) {
                $query->where('home_team_id', $id)
                      ->orWhere('away_team_id', $id);
            })
            ->where('status', 'upcoming')
            ->orderBy('kickoff', 'asc')
            ->take(5)
            ->get();

        $stats = [
            'total_goals' => $team->players->sum('goals'),
            'total_assists' => $team->players->sum('assists'),
            'yellow_cards' => $team->players->sum('yellow_cards'),
            'red_cards' => $team->players->sum('red_cards'),
        ];

        return view('teams.show', compact('team', 'recent_games', 'upcoming_games', 'stats'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->user()->team_id != $id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'home_stadium' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'founded_year' => 'nullable|string|max:4',
            'description' => 'nullable|string',
        ]);

        $team->update($validated);

        return redirect()->back()->with('success', 'Team information updated.');
    }
}
