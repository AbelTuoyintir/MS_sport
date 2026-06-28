<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use App\Models\Injury;
use App\Models\Player;
use Illuminate\Http\Request;

class TeamActivityController extends Controller
{
    public function trainingIndex()
    {
        $sessions = TrainingSession::where('team_id', auth()->user()->team_id)->orderBy('scheduled_at', 'desc')->get();
        return view('manager.training.index', compact('sessions'));
    }

    public function storeTraining(Request $request)
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string',
            'focus' => 'required|string',
            'plan' => 'nullable|string',
        ]);

        $validated['team_id'] = auth()->user()->team_id;
        TrainingSession::create($validated);

        return redirect()->back()->with('success', 'Training session scheduled.');
    }

    public function injuryIndex()
    {
        $team_id = auth()->user()->team_id;
        $injuries = Injury::whereHas('player', function($q) use ($team_id) {
            $q->where('team_id', $team_id);
        })->with('player')->orderBy('started_at', 'desc')->get();

        $players = Player::where('team_id', $team_id)->get();

        return view('manager.injuries.index', compact('injuries', 'players'));
    }

    public function storeInjury(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'type' => 'required|string',
            'severity' => 'required|in:minor,moderate,severe',
            'started_at' => 'required|date',
            'expected_return_at' => 'nullable|date',
        ]);

        Injury::create($validated);

        // Update player status
        Player::find($validated['player_id'])->update(['status' => 'injured']);

        return redirect()->back()->with('success', 'Injury recorded.');
    }
}
