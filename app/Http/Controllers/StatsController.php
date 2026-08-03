<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Prediction;

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
            ->where('appearances', '>', 0)
            ->orderBy('rating', 'desc')
            ->take(20)
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

        // Calculate Predictions Leaderboard
        $predictions = Prediction::with('game')->get();
        $leaderboardData = [];

        foreach ($predictions as $prediction) {
            $game = $prediction->game;
            if (!$game || $game->status !== 'finished') {
                continue;
            }

            $user = $prediction->user_name;
            if (!isset($leaderboardData[$user])) {
                $leaderboardData[$user] = [
                    'user_name' => $user,
                    'total_points' => 0,
                    'exact_matches' => 0,
                    'correct_outcomes' => 0,
                    'total_predictions' => 0,
                ];
            }

            $leaderboardData[$user]['total_predictions']++;

            $predHome = (int)$prediction->home_score_prediction;
            $predAway = (int)$prediction->away_score_prediction;
            $actHome = (int)$game->home_score;
            $actAway = (int)$game->away_score;

            if ($predHome === $actHome && $predAway === $actAway) {
                // Exact Score Match
                $leaderboardData[$user]['total_points'] += 3;
                $leaderboardData[$user]['exact_matches']++;
            } else {
                // Check if correct outcome
                $predDiff = $predHome - $predAway;
                $actDiff = $actHome - $actAway;

                if (($predDiff > 0 && $actDiff > 0) || ($predDiff < 0 && $actDiff < 0) || ($predDiff === 0 && $actDiff === 0)) {
                    // Correct outcome match
                    $leaderboardData[$user]['total_points'] += 1;
                    $leaderboardData[$user]['correct_outcomes']++;
                }
            }
        }

        $predictionsLeaderboard = collect($leaderboardData)
            ->sortByDesc(function ($user) {
                return [
                    $user['total_points'],
                    $user['exact_matches'],
                    $user['total_predictions']
                ];
            })
            ->values();

        return view('stats', compact(
            'topScorers',
            'topAssists',
            'topRated',
            'mostCleanSheets',
            'mostMotmAwards',
            'mostYellowCards',
            'mostRedCards',
            'mostAppearances',
            'predictionsLeaderboard'
        ));
    }
}
