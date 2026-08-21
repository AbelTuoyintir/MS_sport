<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Game;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $teams = Team::all();

        // Standard knockout cup structure for the Apex Champions Cup
        if ($teams->count() >= 8) {
            $bracketTeams = $teams->take(8)->values();
        } else {
            // Seeded/fallback default cup teams
            $bracketTeams = collect([
                ['id' => 1, 'team_name' => 'Man City', 'primary_color' => '#1a3cff'],
                ['id' => 2, 'team_name' => 'Arsenal', 'primary_color' => '#ef0107'],
                ['id' => 3, 'team_name' => 'Liverpool', 'primary_color' => '#d00027'],
                ['id' => 4, 'team_name' => 'Aston Villa', 'primary_color' => '#6c1d45'],
                ['id' => 5, 'team_name' => 'Tottenham', 'primary_color' => '#132257'],
                ['id' => 6, 'team_name' => 'Chelsea', 'primary_color' => '#034694'],
                ['id' => 7, 'team_name' => 'Newcastle', 'primary_color' => '#241f20'],
                ['id' => 8, 'team_name' => 'Man Utd', 'primary_color' => '#e21a23'],
            ]);
        }

        $quarterFinals = [
            ['id' => 'qf1', 'home' => $bracketTeams[0], 'away' => $bracketTeams[7], 'home_score' => 3, 'away_score' => 1, 'status' => 'Finished', 'winner_id' => $bracketTeams[0]['id'] ?? 1],
            ['id' => 'qf2', 'home' => $bracketTeams[1], 'away' => $bracketTeams[6], 'home_score' => 2, 'away_score' => 0, 'status' => 'Finished', 'winner_id' => $bracketTeams[1]['id'] ?? 2],
            ['id' => 'qf3', 'home' => $bracketTeams[2], 'away' => $bracketTeams[5], 'home_score' => 4, 'away_score' => 2, 'status' => 'Finished', 'winner_id' => $bracketTeams[2]['id'] ?? 3],
            ['id' => 'qf4', 'home' => $bracketTeams[3], 'away' => $bracketTeams[4], 'home_score' => 1, 'away_score' => 2, 'status' => 'Finished', 'winner_id' => $bracketTeams[4]['id'] ?? 5],
        ];

        $semiFinals = [
            ['id' => 'sf1', 'home' => $bracketTeams[0], 'away' => $bracketTeams[1], 'home_score' => 2, 'away_score' => 1, 'status' => 'Finished', 'winner_id' => $bracketTeams[0]['id'] ?? 1],
            ['id' => 'sf2', 'home' => $bracketTeams[2], 'away' => $bracketTeams[4], 'home_score' => 3, 'away_score' => 2, 'status' => 'Finished', 'winner_id' => $bracketTeams[2]['id'] ?? 3],
        ];

        $final = [
            ['id' => 'fn1', 'home' => $bracketTeams[0], 'away' => $bracketTeams[2], 'home_score' => null, 'away_score' => null, 'status' => 'Upcoming (May 24)', 'winner_id' => null],
        ];

        $savedPrediction = session('tournament_prediction');

        return view('tournaments.index', compact('teams', 'bracketTeams', 'quarterFinals', 'semiFinals', 'final', 'savedPrediction'));
    }

    public function predictBracket(Request $request)
    {
        $request->validate([
            'fan_name' => 'required|string|max:255',
            'predicted_winner_id' => 'required',
            'predicted_final_score' => 'required|string|max:20',
        ]);

        $team = Team::find($request->predicted_winner_id);
        $winnerName = $team ? $team->team_name : $request->predicted_winner_id;

        $prediction = [
            'fan_name' => $request->fan_name,
            'predicted_winner' => $winnerName,
            'predicted_score' => $request->predicted_final_score,
            'submitted_at' => now()->toDayDateTimeString(),
        ];

        session(['tournament_prediction' => $prediction]);

        return redirect()->back()->with('success', 'Your Apex Champions Cup bracket prediction has been locked in!');
    }
}
