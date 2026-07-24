<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'user_name' => 'required|string|max:100',
            'home_score_prediction' => 'required|integer|min:0',
            'away_score_prediction' => 'required|integer|min:0',
        ]);

        Prediction::create($validated);

        return back()->with('success', 'Prediction submitted! Good luck.');
    }

    public function leaderboard()
    {
        $predictions = Prediction::with(['game' => function($q) {
            $q->where('status', 'finished');
        }])->get();

        $leaderboard = [];

        foreach ($predictions as $prediction) {
            $game = $prediction->game;
            if (!$game) {
                continue; // Skip predictions for non-finished or non-existent games
            }

            $user = $prediction->user_name;
            if (!isset($leaderboard[$user])) {
                $leaderboard[$user] = [
                    'user_name' => $user,
                    'points' => 0,
                    'exact' => 0,
                    'outcome' => 0,
                    'total_predictions' => 0,
                ];
            }

            $leaderboard[$user]['total_predictions']++;

            $pHome = (int) $prediction->home_score_prediction;
            $pAway = (int) $prediction->away_score_prediction;
            $gHome = (int) $game->home_score;
            $gAway = (int) $game->away_score;

            // Exact score match
            if ($pHome === $gHome && $pAway === $gAway) {
                $leaderboard[$user]['points'] += 3;
                $leaderboard[$user]['exact']++;
            } else {
                // Correct outcome match
                $realOutcome = ($gHome > $gAway) ? 1 : (($gHome < $gAway) ? -1 : 0);
                $predOutcome = ($pHome > $pAway) ? 1 : (($pHome < $pAway) ? -1 : 0);

                if ($realOutcome === $predOutcome) {
                    $leaderboard[$user]['points'] += 1;
                    $leaderboard[$user]['outcome']++;
                }
            }
        }

        // Sort by points desc, then exact matches desc, then total predictions asc
        uasort($leaderboard, function($a, $b) {
            if ($b['points'] !== $a['points']) {
                return $b['points'] - $a['points'];
            }
            if ($b['exact'] !== $a['exact']) {
                return $b['exact'] - $a['exact'];
            }
            return $a['total_predictions'] - $b['total_predictions'];
        });

        // Limit to top 20
        $leaderboard = array_slice($leaderboard, 0, 20);

        return view('predictions.leaderboard', compact('leaderboard'));
    }
}
