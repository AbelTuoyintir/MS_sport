<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
        ]);

        $ip = $request->ip();

        $existing = Vote::where('player_id', $validated['player_id'])
                        ->where('ip_address', $ip)
                        ->where('created_at', '>', now()->subDay())
                        ->first();

        if ($existing) {
            return back()->with('error', 'You have already voted for this player in the last 24 hours.');
        }

        Vote::create([
            'player_id' => $validated['player_id'],
            'ip_address' => $ip,
        ]);

        return back()->with('success', 'Thank you for your vote!');
    }
}
