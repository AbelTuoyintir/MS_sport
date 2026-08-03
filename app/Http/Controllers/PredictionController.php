<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function leaderboard()
    {
        $predictions = Prediction::with('game')
            ->whereHas('game', function($query) {
                $query->where('status', 'finished');
            })
            ->get();

        $leaderboard = [];

        foreach ($predictions as $prediction) {
            $user = $prediction->user_name;
            $game = $prediction->game;

            if (!$game) continue;

            if (!isset($leaderboard[$user])) {
                $leaderboard[$user] = [
                    'user_name' => $user,
                    'total_predictions' => 0,
                    'exact_scores' => 0,
                    'correct_outcomes' => 0,
                    'points' => 0,
                ];
            }

            $leaderboard[$user]['total_predictions']++;

            $hp = (int)$prediction->home_score_prediction;
            $ap = (int)$prediction->away_score_prediction;
            $ha = (int)$game->home_score;
            $aa = (int)$game->away_score;

            $exact = ($hp === $ha && $ap === $aa);
            $correctOutcome = false;

            if (($hp > $ap && $ha > $aa) || ($hp < $ap && $ha < $aa) || ($hp === $ap && $ha === $aa)) {
                $correctOutcome = true;
            }

            if ($exact) {
                $leaderboard[$user]['exact_scores']++;
                $leaderboard[$user]['points'] += 3;
            } elseif ($correctOutcome) {
                $leaderboard[$user]['correct_outcomes']++;
                $leaderboard[$user]['points'] += 1;
            }
        }

        usort($leaderboard, function($a, $b) {
            if ($a['points'] !== $b['points']) {
                return $b['points'] <=> $a['points'];
            }
            if ($a['exact_scores'] !== $b['exact_scores']) {
                return $b['exact_scores'] <=> $a['exact_scores'];
            }
            return $b['total_predictions'] <=> $a['total_predictions'];
        });

        return view('predictions.leaderboard', compact('leaderboard'));
    }

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
}
