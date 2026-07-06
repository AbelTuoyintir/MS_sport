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

        return view('home', compact('standings', 'recent_games', 'news', 'top_scorers', 'top_assists', 'all_teams'));
    }

    public function matchDetails($id)
    {
        $game = Game::with(['homeTeam.players', 'awayTeam.players', 'events.player', 'events.team', 'squads.player'])
            ->findOrFail($id);

        $h2h_matches = Game::where(function($q) use ($game) {
                $q->where('home_team_id', $game->home_team_id)
                  ->where('away_team_id', $game->away_team_id);
            })->orWhere(function($q) use ($game) {
                $q->where('home_team_id', $game->away_team_id)
                  ->where('away_team_id', $game->home_team_id);
            })
            ->where('status', 'finished')
            ->orderBy('kickoff', 'desc')
            ->get();

        $h2h_stats = [
            'home_wins' => 0,
            'away_wins' => 0,
            'draws' => 0,
            'total' => $h2h_matches->count()
        ];

        foreach($h2h_matches as $m) {
            if ($m->home_score === $m->away_score) {
                $h2h_stats['draws']++;
            } elseif ($m->home_team_id === $game->home_team_id) {
                if ($m->home_score > $m->away_score) $h2h_stats['home_wins']++;
                else $h2h_stats['away_wins']++;
            } else {
                if ($m->home_score > $m->away_score) $h2h_stats['away_wins']++;
                else $h2h_stats['home_wins']++;
            }
        }

        return view('matches.show', compact('game', 'h2h_matches', 'h2h_stats'));
    }
}
