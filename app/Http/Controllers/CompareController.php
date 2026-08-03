<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class CompareController extends Controller
{
    public function compare(Request $request)
    {
        $allPlayers = Player::with('team')->orderBy('name')->get();

        $player1 = null;
        $player2 = null;

        if ($request->has('player1') && $request->has('player2')) {
            $player1 = Player::with('team')->find($request->get('player1'));
            $player2 = Player::with('team')->find($request->get('player2'));
        }

        return view('compare', compact('allPlayers', 'player1', 'player2'));
    }
}
