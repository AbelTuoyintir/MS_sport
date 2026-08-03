<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StandingsService;
use App\Models\Game;
use App\Models\Article;
use App\Models\Player;

class HomeController extends Controller
{
    protected $standingsService;

    public function __construct(StandingsService $standingsService)
    {
        $this->standingsService = $standingsService;
    }

    public function index()
    {
        $standings = $this->standingsService->getStandings();
        $recent_games = Game::with(['homeTeam', 'awayTeam'])->orderBy('kickoff', 'desc')->take(10)->get();
        $news = Article::with('comments')->where('is_published', true)->orderBy('created_at', 'desc')->take(5)->get();
        $top_scorers = Player::with('team')->where('goals', '>', 0)->orderBy('goals', 'desc')->take(5)->get();
        $top_assists = Player::with('team')->where('assists', '>', 0)->orderBy('assists', 'desc')->take(5)->get();
        $all_teams = \App\Models\Team::all();

        // Predictor Leaderboard logic
        $predictions = \App\Models\Prediction::with('game')->get();
        $leaderboard = $predictions->groupBy('user_name')->map(function($userPredictions, $name) {
            $score = 0;
            foreach($userPredictions as $p) {
                if ($p->game->status === 'finished') {
                    // Exact score: 3 points
                    if ($p->home_score_prediction == $p->game->home_score && $p->away_score_prediction == $p->game->away_score) {
                        $score += 3;
                    }
                    // Correct result: 1 point
                    elseif (($p->home_score_prediction > $p->away_score_prediction && $p->game->home_score > $p->game->away_score) ||
                            ($p->home_score_prediction < $p->away_score_prediction && $p->game->home_score < $p->game->away_score) ||
                            ($p->home_score_prediction == $p->away_score_prediction && $p->game->home_score == $p->game->away_score)) {
                        $score += 1;
                    }
                }
            }
            return [
                'name' => $name,
                'score' => $score,
                'count' => $userPredictions->count()
            ];
        })->sortByDesc('score')->take(5);

        return view('home', compact('standings', 'recent_games', 'news', 'top_scorers', 'top_assists', 'all_teams', 'leaderboard'));
    }

    public function matchDetails($id)
    {
        $game = Game::with(['homeTeam.players', 'awayTeam.players', 'events.player', 'events.team', 'squads.player'])
            ->findOrFail($id);

        return view('matches.show', compact('game'));
    }
}
