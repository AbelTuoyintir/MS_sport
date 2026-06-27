<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'user_name' => 'required|string|max:100',
            'home_score_prediction' => 'required|integer|min:0',
            'away_score_prediction' => 'required|integer|min:0',
        ]);

        Prediction::create($validated);

        return back()->with('success', 'Prediction submitted! Good luck.');
    }
}
