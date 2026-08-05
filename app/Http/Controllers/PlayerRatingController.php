<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlayerRating;
use App\Models\Player;
use App\Models\Game;

class PlayerRatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'game_id' => 'required|exists:games,id',
            'rating' => 'required|integer|min:1|max:10',
        ]);

        $sessionId = session()->getId();

        // Check if game is finished
        $game = Game::findOrFail($validated['game_id']);
        if ($game->status !== 'finished') {
            return redirect()->back()->with('error', 'You can only rate players in completed matches.');
        }

        // Prevent duplicate rating by same session
        PlayerRating::updateOrCreate(
            [
                'player_id' => $validated['player_id'],
                'game_id' => $validated['game_id'],
                'session_id' => $sessionId,
            ],
            [
                'rating' => $validated['rating'],
            ]
        );

        return redirect()->back()->with('success', 'Thank you for your player performance rating!');
    }
}
