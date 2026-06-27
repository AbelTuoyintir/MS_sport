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
        $all_teams = \App\Models\Team::all();

        return view('home', compact('standings', 'recent_games', 'news', 'top_scorers', 'all_teams'));
    }
}
