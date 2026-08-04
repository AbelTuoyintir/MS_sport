<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->orderBy('kickoff', 'desc')->paginate(20);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $teams = Team::all();
        return view('admin.games.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'kickoff' => 'required|date',
            'matchweek' => 'required|integer|min:1',
            'venue' => 'nullable|string|max:255',
        ]);

        Game::create($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game scheduled successfully.');
    }

    public function edit(Game $game)
    {
        $game->load(['homeTeam.players', 'awayTeam.players']);
        $teams = Team::all();
        return view('admin.games.edit', compact('game', 'teams'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'kickoff' => 'required|date',
            'matchweek' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,live,finished',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'venue' => 'nullable|string|max:255',
            'live_minute' => 'nullable|string|max:255',
            'potm_id' => 'nullable|exists:players,id',
        ]);

        $oldStatus = $game->status;
        $oldPotmId = $game->potm_id;
        $game->update($validated);

        // If game just finished, we could trigger stats updates here if not using dynamic service
        if ($oldStatus !== 'finished' && $game->status === 'finished') {
            // Log activity or update player appearance counts
            foreach ($game->homeTeam->players as $player) {
                $player->increment('appearances');
            }
            foreach ($game->awayTeam->players as $player) {
                $player->increment('appearances');
            }

            // Goal fest check (5 or more goals)
            $totalGoals = $game->home_score + $game->away_score;
            if ($totalGoals >= 5) {
                \App\Models\Article::create([
                    'title' => "GOAL FEST: {$game->homeTeam->team_name} {$game->home_score} - {$game->away_score} {$game->awayTeam->team_name}!",
                    'slug' => \Illuminate\Support\Str::slug("goal-fest-{$game->homeTeam->team_name}-vs-{$game->awayTeam->team_name}-" . uniqid()),
                    'content' => "Spectators were treated to an absolute classic as {$game->homeTeam->team_name} and {$game->awayTeam->team_name} played out a high-scoring game finishing {$game->home_score} to {$game->away_score}. Matchweek {$game->matchweek} certainly delivered the drama!",
                    'tag' => 'Match Analysis',
                    'is_published' => true,
                ]);
            }
        }

        if ($game->status === 'finished' && $game->potm_id && $game->potm_id != $oldPotmId) {
            // Increment new potm
            $newPotm = \App\Models\Player::find($game->potm_id);
            if ($newPotm) {
                $newPotm->increment('motm_awards');
            }
            // Decrement old potm if existed
            if ($oldPotmId) {
                $oldPotm = \App\Models\Player::find($oldPotmId);
                if ($oldPotm && $oldPotm->motm_awards > 0) {
                    $oldPotm->decrement('motm_awards');
                }
            }
        }

        return redirect()->route('admin.games.index')->with('success', 'Game updated successfully.');
    }

    public function events(Game $game)
    {
        $game->load(['homeTeam.players', 'awayTeam.players', 'events.player']);
        return view('admin.games.events', compact('game'));
    }

    public function storeEvent(Request $request, Game $game)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'team_id' => 'required|exists:teams,id',
            'type' => 'required|in:goal,assist,yellow_card,red_card,own_goal',
            'minute' => 'required|string',
        ]);

        $game->events()->create($validated);

        // Update player stats
        $player = \App\Models\Player::find($validated['player_id']);
        if ($validated['type'] === 'goal') {
            $player->increment('goals');
            if ($validated['team_id'] == $game->home_team_id) {
                $game->increment('home_score');
            } else {
                $game->increment('away_score');
            }
        }
        if ($validated['type'] === 'assist') $player->increment('assists');
        if ($validated['type'] === 'yellow_card') $player->increment('yellow_cards');
        if ($validated['type'] === 'red_card') $player->increment('red_cards');
        if ($validated['type'] === 'own_goal') {
            if ($validated['team_id'] == $game->home_team_id) {
                $game->increment('away_score');
            } else {
                $game->increment('home_score');
            }
        }

        return redirect()->back()->with('success', 'Match event recorded.');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('admin.games.index')->with('success', 'Game deleted successfully.');
    }
}
