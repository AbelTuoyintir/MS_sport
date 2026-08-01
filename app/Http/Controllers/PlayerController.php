<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:GK,DEF,MID,FWD',
            'age' => 'nullable|integer',
            'number' => 'nullable|integer',
        ]);

        $validated['team_id'] = auth()->user()->team_id;
        $validated['rating'] = rand(60, 85);

        Player::create($validated);

        return back()->with('success', 'Player added successfully.');
    }

    public function update(Request $request, $id)
    {
        $player = Player::where('team_id', auth()->user()->team_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:GK,DEF,MID,FWD',
            'age' => 'nullable|integer',
            'number' => 'nullable|integer',
        ]);

        $player->update($validated);

        return back()->with('success', 'Player updated successfully.');
    }

    public function destroy($id)
    {
        $player = Player::where('team_id', auth()->user()->team_id)->findOrFail($id);
        $player->delete();

        return back()->with('success', 'Player removed successfully.');
    }

    public function showPublic($id)
    {
        $player = Player::with('team')->findOrFail($id);

        $recent_events = \App\Models\MatchEvent::with(['game.homeTeam', 'game.awayTeam'])
            ->where('player_id', $id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('players.show', compact('player', 'recent_events'));
    }

    public function compare(Request $request)
    {
        $all_players = Player::with('team')->orderBy('name')->get();

        $player1 = null;
        $player2 = null;

        if ($request->has('player1_id')) {
            $player1 = Player::with('team')->find($request->player1_id);
        }
        if ($request->has('player2_id')) {
            $player2 = Player::with('team')->find($request->player2_id);
        }

        $player1_value = $player1 ? $this->calculateMarketValue($player1) : 0;
        $player2_value = $player2 ? $this->calculateMarketValue($player2) : 0;

        return view('players.compare', compact('all_players', 'player1', 'player2', 'player1_value', 'player2_value'));
    }

    private function calculateMarketValue($player)
    {
        // Base value from rating
        $base = ($player->rating * $player->rating) * 300;

        // Premium stats
        $goalsPremium = $player->goals * 60000;
        $assistsPremium = $player->assists * 45000;
        $cleanSheetsPremium = $player->clean_sheets * 40000;
        $appearancesPremium = $player->appearances * 10000;

        $value = $base + $goalsPremium + $assistsPremium + $cleanSheetsPremium + $appearancesPremium;

        // Discipline penalties
        $value -= ($player->red_cards * 50000);
        $value -= ($player->yellow_cards * 10000);

        // Age factor
        if ($player->age) {
            if ($player->age < 23) {
                $value *= 1.15; // Young prospect premium
            } elseif ($player->age <= 29) {
                $value *= 1.05; // Peak years premium
            } else {
                $deduction = ($player->age - 30) * 0.08;
                $value *= max(0.4, 1.0 - $deduction); // Veterans decline gracefully to 40% minimum
            }
        }

        return max(50000, round($value));
    }
}
