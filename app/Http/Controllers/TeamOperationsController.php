<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Equipment;
use App\Models\ScoutReport;
use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use Illuminate\Http\Request;

class TeamOperationsController extends Controller
{
    public function financeIndex()
    {
        $finances = Finance::where('team_id', auth()->user()->team_id)->orderBy('date', 'desc')->get();
        $balance = Finance::where('team_id', auth()->user()->team_id)->where('type', 'income')->sum('amount') -
                   Finance::where('team_id', auth()->user()->team_id)->where('type', 'expense')->sum('amount');

        return view('manager.operations.finance', compact('finances', 'balance'));
    }

    public function equipmentIndex()
    {
        $equipment = Equipment::where('team_id', auth()->user()->team_id)->get();
        return view('manager.operations.equipment', compact('equipment'));
    }

    public function scoutingIndex()
    {
        $reports = ScoutReport::where('team_id', auth()->user()->team_id)->orderBy('rating', 'desc')->get();
        return view('manager.operations.scouting', compact('reports'));
    }

    public function aiScouting(Request $request)
    {
        $userTeamId = auth()->user()->team_id;
        $userTeam = Team::with('players')->find($userTeamId);
        $otherTeams = Team::where('id', '!=', $userTeamId)->get();

        $selectedOpponentId = $request->query('opponent_id') ?? $otherTeams->first()?->id;
        $opponent = $selectedOpponentId ? Team::with('players')->find($selectedOpponentId) : null;

        $analysis = null;
        if ($opponent) {
            $userPlayers = $userTeam ? $userTeam->players : collect();
            $userAvgRating = $userPlayers->count() > 0 ? round($userPlayers->avg('rating'), 1) : 75.0;

            $oppPlayers = $opponent->players;
            $oppAvgRating = $oppPlayers->count() > 0 ? round($oppPlayers->avg('rating'), 1) : 75.0;

            $starPlayer = $oppPlayers->sortByDesc('rating')->first();
            $topScorer = $oppPlayers->sortByDesc('goals')->first();

            // Form analysis from recent matches
            $recentMatches = Game::where(function ($query) use ($opponent) {
                $query->where('home_team_id', $opponent->id)
                      ->orWhere('away_team_id', $opponent->id);
            })->where('status', 'finished')
              ->orderBy('kickoff', 'desc')
              ->take(5)
              ->get();

            $oppForm = [];
            foreach ($recentMatches as $game) {
                $isHome = $game->home_team_id == $opponent->id;
                $oppGoals = $isHome ? $game->home_score : $game->away_score;
                $otherGoals = $isHome ? $game->away_score : $game->home_score;

                if ($oppGoals > $otherGoals) $oppForm[] = 'W';
                elseif ($oppGoals < $otherGoals) $oppForm[] = 'L';
                else $oppForm[] = 'D';
            }

            // Tactical Recommendations
            $oppFormation = $opponent->formation ?? '4-3-3';
            $counterFormation = match ($oppFormation) {
                '4-3-3' => '4-2-3-1',
                '4-4-2' => '3-5-2',
                '4-2-3-1' => '4-3-3',
                '3-5-2' => '4-3-3',
                '5-3-2' => '4-2-3-1',
                default => '4-3-3',
            };

            $tacticalAdvice = match ($oppFormation) {
                '4-3-3' => 'Overload the midfield double pivot to disrupt their build-up play and exploit space behind their high wing-backs.',
                '4-4-2' => 'Use a 3-man midfield engine to control possession in central channels and isolate their two central defenders.',
                '4-2-3-1' => 'Press their double pivot relentlessly and utilize wide wingers to stretch their compact defensive shape.',
                '3-5-2' => 'Target the spaces behind their wing-backs on rapid counter-attacks and maintain high defensive line discipline.',
                '5-3-2' => 'Maintain patience in possession, utilize overlap runs from full-backs, and switch play rapidly to break down their low block.',
                default => 'Control central midfield tempo and execute quick vertical passing to break down their shape.',
            };

            $keyThreat = $starPlayer ? "{$starPlayer->name} ({$starPlayer->position}, Rating: {$starPlayer->rating})" : "Balanced Squad Threat";

            $analysis = [
                'user_avg_rating' => $userAvgRating,
                'opp_avg_rating' => $oppAvgRating,
                'star_player' => $starPlayer,
                'top_scorer' => $topScorer,
                'form' => implode('-', $oppForm) ?: 'N/A',
                'opp_formation' => $oppFormation,
                'counter_formation' => $counterFormation,
                'tactical_advice' => $tacticalAdvice,
                'key_threat' => $keyThreat,
                'threat_level' => $oppAvgRating > $userAvgRating + 3 ? 'HIGH' : ($oppAvgRating < $userAvgRating - 3 ? 'LOW' : 'MODERATE'),
            ];
        }

        return view('manager.operations.ai_scouting', compact('userTeam', 'otherTeams', 'opponent', 'analysis'));
    }

    public function storeEquipment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0|max_digits:10',
            'condition' => 'required|string',
        ]);

        $validated['team_id'] = auth()->user()->team_id;
        Equipment::create($validated);

        return redirect()->back()->with('success', 'Equipment added.');
    }

    public function storeScout(Request $request)
    {
        $validated = $request->validate([
            'player_name' => 'required|string|max:255',
            'position' => 'nullable|string',
            'age' => 'nullable|integer',
            'rating' => 'required|integer|min:1|max:5',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'status' => 'required|in:shortlisted,trial,monitoring,ignored',
        ]);

        $validated['team_id'] = auth()->user()->team_id;
        ScoutReport::create($validated);

        return redirect()->back()->with('success', 'Scout report added.');
    }

    public function storeFinance(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['team_id'] = auth()->user()->team_id;
        Finance::create($validated);

        return redirect()->back()->with('success', 'Financial record added.');
    }
}
