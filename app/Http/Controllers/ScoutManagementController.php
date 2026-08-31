<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\ScoutAgent;
use App\Models\TransferListing;
use Illuminate\Http\Request;

class ScoutManagementController extends Controller
{
    public function index()
    {
        $teamId = auth()->user()->team_id;

        $signedScouts = ScoutAgent::where('team_id', $teamId)->get();
        $availableScouts = ScoutAgent::whereNull('team_id')->get();
        $myScoutedPlayers = Player::where('team_id', $teamId)->orderBy('created_at', 'desc')->take(10)->get();

        return view('manager.scouts.index', compact('signedScouts', 'availableScouts', 'myScoutedPlayers'));
    }

    public function sign($id)
    {
        $scout = ScoutAgent::whereNull('team_id')->findOrFail($id);
        $scout->update(['team_id' => auth()->user()->team_id]);

        return redirect()->back()->with('success', "Scouting agent {$scout->name} successfully signed to your club!");
    }

    public function release($id)
    {
        $scout = ScoutAgent::where('team_id', auth()->user()->team_id)->findOrFail($id);
        $scout->update(['team_id' => null]);

        return redirect()->back()->with('success', "Scouting agent {$scout->name} has been released.");
    }

    public function submitPlayer(Request $request)
    {
        $teamId = auth()->user()->team_id;

        // Ensure team has at least one active signed scout agent
        $hasScout = ScoutAgent::where('team_id', $teamId)->exists();
        if (!$hasScout) {
            return redirect()->back()->with('error', 'You must sign at least one scouting agent before submitting new players.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:GK,DEF,MID,FWD',
            'age' => 'required|integer|min:16|max:40',
            'rating' => 'required|integer|min:50|max:99',
            'estimated_value' => 'required|numeric|min:0',
            'submission_type' => 'required|in:squad,market',
        ]);

        // Create player record
        $player = Player::create([
            'team_id' => $teamId,
            'name' => $validated['name'],
            'position' => $validated['position'],
            'age' => $validated['age'],
            'rating' => $validated['rating'],
            'nationality' => '🇬🇭',
            'number' => rand(1, 99),
            'goals' => 0,
            'assists' => 0,
            'appearances' => 0,
        ]);

        if ($validated['submission_type'] === 'squad') {
            return redirect()->back()->with('success', "Scouted recruit {$player->name} (OVR {$player->rating}) has been signed directly to your squad!");
        } else {
            // Submit to market as active transfer listing
            TransferListing::create([
                'player_id' => $player->id,
                'team_id' => $teamId,
                'asking_price' => $validated['estimated_value'],
                'reason' => 'Newly discovered scout recruit submitted to transfer market',
                'type' => 'permanent',
                'status' => 'active',
            ]);

            return redirect()->back()->with('success', "Scouted recruit {$player->name} (OVR {$player->rating}) has been submitted and listed on the transfer market!");
        }
    }
}
