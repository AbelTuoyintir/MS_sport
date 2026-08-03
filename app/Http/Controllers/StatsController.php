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

        $cleanSheets = Player::with('team')
            ->where('clean_sheets', '>', 0)
            ->orderBy('clean_sheets', 'desc')
            ->take(10)
            ->get();

        $topRated = Player::with('team')
            ->where('rating', '>', 0)
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        $motmAwards = Player::with('team')
            ->where('motm_awards', '>', 0)
            ->orderBy('motm_awards', 'desc')
            ->take(10)
            ->get();

        // Calculate prediction leaderboard points:
        // 3 pts for exact score, 1 pt for correct match outcome without exact score.
        $predictions = Prediction::with('game')->get();

        $leaderboard = $predictions->filter(function($prediction) {
            return $prediction->game && $prediction->game->status === 'finished';
        })->map(function($prediction) {
            $game = $prediction->game;
            $points = 0;

            $actual_home = (int) $game->home_score;
            $actual_away = (int) $game->away_score;
            $pred_home = (int) $prediction->home_score_prediction;
            $pred_away = (int) $prediction->away_score_prediction;

            if ($actual_home === $pred_home && $actual_away === $pred_away) {
                $points = 3;
            } else {
                $actual_outcome = $actual_home <=> $actual_away;
                $pred_outcome = $pred_home <=> $pred_away;
                if ($actual_outcome === $pred_outcome) {
                    $points = 1;
                }
            }

            return [
                'user_name' => $prediction->user_name,
                'points' => $points,
            ];
        })->groupBy('user_name')->map(function($userPredictions, $userName) {
            return (object)[
                'user_name' => $userName,
                'points' => $userPredictions->sum('points'),
                'predictions_count' => $userPredictions->count()
            ];
        })->sortByDesc('points')->values();

        return view('stats', compact(
            'topScorers',
            'topAssists',
            'mostYellowCards',
            'mostRedCards',
            'mostAppearances',
            'cleanSheets',
            'topRated',
            'motmAwards',
            'leaderboard'
        ));
    }
}
