<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Game;
use Illuminate\Http\Request;

class MatchPredictorController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::with('players')->get();

        $homeTeamId = $request->input('home_team_id', $teams->first()?->id);
        $awayTeamId = $request->input('away_team_id', $teams->skip(1)->first()?->id ?? $homeTeamId);

        $homeTeam = $teams->firstWhere('id', $homeTeamId);
        $awayTeam = $teams->firstWhere('id', $awayTeamId);

        $prediction = null;
        if ($homeTeam && $awayTeam) {
            $prediction = $this->calculatePrediction($homeTeam, $awayTeam);
        }

        return view('predictor', compact('teams', 'homeTeam', 'awayTeam', 'prediction'));
    }

    private function calculatePrediction(Team $homeTeam, Team $awayTeam)
    {
        // Calculate Squad Ratings & Attack/Defense Strength
        $homeAvgRating = $homeTeam->players->avg('rating') ?? 75;
        $awayAvgRating = $awayTeam->players->avg('rating') ?? 75;

        $homeAtt = $homeTeam->players->where('position', 'FW')->avg('rating') ?: ($homeAvgRating + 2);
        $homeDef = $homeTeam->players->where('position', 'DF')->avg('rating') ?: ($homeAvgRating - 1);

        $awayAtt = $awayTeam->players->where('position', 'FW')->avg('rating') ?: ($awayAvgRating + 2);
        $awayDef = $awayTeam->players->where('position', 'DF')->avg('rating') ?: ($awayAvgRating - 1);

        // Calculate expected goals (xG) based on attack vs opponent defense + home advantage factor
        $homeXG = round(max(0.4, ($homeAtt / max(1, $awayDef)) * 1.55 + 0.25), 2);
        $awayXG = round(max(0.3, ($awayAtt / max(1, $homeDef)) * 1.35), 2);

        // Win probabilities
        $totalXG = $homeXG + $awayXG;
        $homeWinProb = round(($homeXG / $totalXG) * 55 + 10);
        $awayWinProb = round(($awayXG / $totalXG) * 50);
        $drawProb = max(5, 100 - ($homeWinProb + $awayWinProb));

        // Projected score line
        $projectedHomeGoals = round($homeXG);
        $projectedAwayGoals = round($awayXG);

        // Key battles
        $keyBattles = [
            [
                'title' => 'Midfield Dominance & Control',
                'home_player' => $homeTeam->players->where('position', 'MF')->sortByDesc('rating')->first()?->name ?? 'Home Midfielder',
                'away_player' => $awayTeam->players->where('position', 'MF')->sortByDesc('rating')->first()?->name ?? 'Away Midfielder',
                'analysis' => 'Battle for tempo regulation and second-ball recoveries in the central third.'
            ],
            [
                'title' => 'Forward Edge & Finishing Precision',
                'home_player' => $homeTeam->players->where('position', 'FW')->sortByDesc('rating')->first()?->name ?? 'Home Striker',
                'away_player' => $awayTeam->players->where('position', 'FW')->sortByDesc('rating')->first()?->name ?? 'Away Striker',
                'analysis' => 'Conversion efficiency against opposition high-line pressing traps.'
            ]
        ];

        return [
            'home_xg' => $homeXG,
            'away_xg' => $awayXG,
            'home_win_prob' => $homeWinProb,
            'away_win_prob' => $awayWinProb,
            'draw_prob' => $drawProb,
            'projected_score' => "{$projectedHomeGoals} - {$projectedAwayGoals}",
            'home_att' => round($homeAtt, 1),
            'home_def' => round($homeDef, 1),
            'away_att' => round($awayAtt, 1),
            'away_def' => round($awayDef, 1),
            'key_battles' => $keyBattles,
        ];
    }
}
