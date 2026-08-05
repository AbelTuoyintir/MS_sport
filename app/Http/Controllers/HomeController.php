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
        $rumours = $this->generateTransferRumours();

        return view('home', compact('standings', 'recent_games', 'news', 'top_scorers', 'top_assists', 'all_teams', 'rumours'));
    }

    private function generateTransferRumours()
    {
        $players = Player::with('team')->orderBy('rating', 'desc')->take(30)->get();
        $teams = \App\Models\Team::all();

        $rumours = [];
        if ($players->count() > 3 && $teams->count() > 1) {
            $templates = [
                "REVEALED: {team} are preparing an ambitious bid for {player}!",
                "SCOUT REPORT: {team} scouts were spotted monitoring {player}'s performance.",
                "TRANSFER TALK: {player} has emerged as a top target for {team}.",
                "EXCL: {team} have opened initial talks to sign {player} next season.",
                "EXCLUSIVE: {player} is reportedly considering options with {team} interested.",
                "RUMOUR: {team} are ready to meet the valuation for {player}!"
            ];

            for ($i = 0; $i < 5; $i++) {
                $player = $players->get(($i * 4) % $players->count());
                $possibleTeams = $teams->filter(fn($t) => $t->id !== $player->team_id);
                $team = $possibleTeams->isNotEmpty() ? $possibleTeams->values()->get($i % $possibleTeams->count()) : $teams->first();

                $template = $templates[$i % count($templates)];
                $title = str_replace(['{player}', '{team}'], [$player->name, $team->team_name], $template);

                $rumours[] = [
                    'title' => $title,
                    'probability' => rand(40, 95) . '% probability',
                    'urgency' => ['Hot', 'Developing', 'High Interest', 'Rumour', 'Breaking'][$i % 5]
                ];
            }
        } else {
            $rumours = [
                ['title' => "TRANSFER TALK: Cape Coast Stars are monitoring top forward targets.", 'probability' => '65% probability', 'urgency' => 'Hot'],
                ['title' => "REVEALED: Accra Lions prepare high-value offer for a premium playmaker.", 'probability' => '72% probability', 'urgency' => 'Developing'],
                ['title' => "EXCL: Kumasi Warriors manager reveals plans for winter reinforcements.", 'probability' => '85% probability', 'urgency' => 'Breaking'],
            ];
        }
        return $rumours;
    }

    public function matchDetails($id)
    {
        $game = Game::with(['homeTeam.players', 'awayTeam.players', 'events.player', 'events.team', 'squads.player'])
            ->findOrFail($id);

        $h2h = Game::where(function($query) use ($game) {
                $query->where('home_team_id', $game->home_team_id)
                      ->where('away_team_id', $game->away_team_id);
            })
            ->orWhere(function($query) use ($game) {
                $query->where('home_team_id', $game->away_team_id)
                      ->where('away_team_id', $game->home_team_id);
            })
            ->where('status', 'finished')
            ->where('id', '!=', $id)
            ->orderBy('kickoff', 'desc')
            ->get();

        $h2h_stats = [
            'home_wins' => 0,
            'away_wins' => 0,
            'draws' => 0,
            'home_goals' => 0,
            'away_goals' => 0,
        ];

        foreach ($h2h as $match) {
            if ($match->home_team_id === $game->home_team_id) {
                $h2h_stats['home_goals'] += $match->home_score;
                $h2h_stats['away_goals'] += $match->away_score;
                if ($match->home_score > $match->away_score) $h2h_stats['home_wins']++;
                elseif ($match->home_score < $match->away_score) $h2h_stats['away_wins']++;
                else $h2h_stats['draws']++;
            } else {
                $h2h_stats['home_goals'] += $match->away_score;
                $h2h_stats['away_goals'] += $match->home_score;
                if ($match->away_score > $match->home_score) $h2h_stats['home_wins']++;
                elseif ($match->away_score < $match->home_score) $h2h_stats['away_wins']++;
                else $h2h_stats['draws']++;
            }
        }

        return view('matches.show', compact('game', 'h2h', 'h2h_stats'));
    }
}
