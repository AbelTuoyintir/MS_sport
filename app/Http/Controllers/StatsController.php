<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class StatsController extends Controller
{
    public function index()
    {
        $topScorers = Player::with('team')
            ->where('goals', '>', 0)
            ->orderBy('goals', 'desc')
            ->take(20)
            ->get();

        $topAssists = Player::with('team')
            ->where('assists', '>', 0)
            ->orderBy('assists', 'desc')
            ->take(20)
            ->get();

        $topRated = Player::with('team')
            ->where('rating', '>', 0)
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        $mostMOTM = Player::with('team')
            ->where('motm_awards', '>', 0)
            ->orderBy('motm_awards', 'desc')
            ->take(10)
            ->get();

        $mostYellowCards = Player::with('team')
            ->where('yellow_cards', '>', 0)
            ->orderBy('yellow_cards', 'desc')
            ->take(10)
            ->get();

        $mostRedCards = Player::with('team')
            ->where('red_cards', '>', 0)
            ->orderBy('red_cards', 'desc')
            ->take(10)
            ->get();

        $mostAppearances = Player::with('team')
            ->where('appearances', '>', 0)
            ->orderBy('appearances', 'desc')
            ->take(10)
            ->get();

        // New Advanced Player Statistics
        $topRated = Player::with('team')
            ->where('rating', '>', 0)
            ->where('appearances', '>', 0)
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        $mostCleanSheets = Player::with('team')
            ->where('clean_sheets', '>', 0)
            ->orderBy('clean_sheets', 'desc')
            ->take(10)
            ->get();

        $mostMotmAwards = Player::with('team')
            ->where('motm_awards', '>', 0)
            ->orderBy('motm_awards', 'desc')
            ->take(10)
            ->get();

        // Prediction Leaderboard scoring logic
        $predictions = \App\Models\Prediction::whereHas('game', function ($query) {
            $query->where('status', 'finished');
        })->with('game')->get();

        $leaderboard = [];

        foreach ($predictions as $prediction) {
            $game = $prediction->game;
            $actual_home = $game->home_score;
            $actual_away = $game->away_score;
            $pred_home = $prediction->home_score_prediction;
            $pred_away = $prediction->away_score_prediction;

            $points = 0;
            $is_exact = false;
            $is_outcome = false;

            if ($pred_home === $actual_home && $pred_away === $actual_away) {
                $points = 3;
                $is_exact = true;
            } else {
                $actual_diff = $actual_home - $actual_away;
                $pred_diff = $pred_home - $pred_away;

                if (($actual_diff > 0 && $pred_diff > 0) ||
                    ($actual_diff < 0 && $pred_diff < 0) ||
                    ($actual_diff === 0 && $pred_diff === 0)) {
                    $points = 1;
                    $is_outcome = true;
                }
            }

            $name = $prediction->user_name;
            if (!isset($leaderboard[$name])) {
                $leaderboard[$name] = [
                    'user_name' => $name,
                    'predictions_count' => 0,
                    'exact_scores' => 0,
                    'correct_outcomes' => 0,
                    'points' => 0
                ];
            }

            $leaderboard[$name]['predictions_count']++;
            if ($is_exact) {
                $leaderboard[$name]['exact_scores']++;
            } elseif ($is_outcome) {
                $leaderboard[$name]['correct_outcomes']++;
            }
            $leaderboard[$name]['points'] += $points;
        }

        $predictionsLeaderboard = collect($leaderboard)->sortByDesc('points')->values();

        return view('stats', compact(
            'topScorers',
            'topAssists',
            'mostYellowCards',
            'mostRedCards',
            'mostAppearances',
            'topRated',
            'mostCleanSheets',
            'mostMotmAwards',
            'predictionsLeaderboard'
        ));
    }
}
