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
        $allAgents = ScoutAgent::where('is_active', true)->get();
        $team = auth()->user()->team;
        $myAgents = $team ? $team->scoutAgents : collect();
        $myPlayers = $team ? $team->players()->orderBy('created_at', 'desc')->take(10)->get() : collect();

        return view('manager.operations.scouting_hub', compact('allAgents', 'myAgents', 'myPlayers'));
    }

    public function signAgent(Request $request, $id)
    {
        $team = auth()->user()->team;
        if (!$team) {
            return redirect()->back()->with('error', 'You do not have an assigned team.');
        }

        $agent = ScoutAgent::findOrFail($id);
        if (!$team->scoutAgents->contains($agent->id)) {
            $team->scoutAgents()->attach($agent->id, ['signed_at' => now()]);
        }

        return redirect()->back()->with('success', "Scouting agent {$agent->name} signed to your squad!");
    }

    public function releaseAgent(Request $request, $id)
    {
        $team = auth()->user()->team;
        if (!$team) {
            return redirect()->back()->with('error', 'You do not have an assigned team.');
        }

        $team->scoutAgents()->detach($id);

        return redirect()->back()->with('success', 'Scouting agent contract released.');
    }

    public function submitPlayer(Request $request)
    {
        $team = auth()->user()->team;
        if (!$team) {
            return redirect()->back()->with('error', 'You do not have an assigned team.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|in:GK,DEF,MID,FWD',
            'rating' => 'required|integer|min:50|max:99',
            'age' => 'required|integer|min:16|max:40',
            'nationality' => 'nullable|string|max:255',
            'destination' => 'required|in:squad,market',
            'asking_price' => 'nullable|numeric|min:0',
            'listing_type' => 'nullable|in:permanent,loan_half,loan_full',
        ]);

        $player = Player::create([
            'team_id' => $team->id,
            'name' => $validated['name'],
            'position' => $validated['position'],
            'rating' => $validated['rating'],
            'age' => $validated['age'],
            'nationality' => $validated['nationality'] ?? 'Ghanaian',
            'goals' => 0,
            'assists' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0,
            'appearances' => 0,
        ]);

        if ($validated['destination'] === 'market') {
            $askingPrice = $validated['asking_price'] ?? ($player->rating * 50000);
            $listingType = $validated['listing_type'] ?? 'permanent';

            TransferListing::create([
                'player_id' => $player->id,
                'team_id' => $team->id,
                'asking_price' => $askingPrice,
                'reason' => 'Scouted talent listed directly to market by Manager',
                'type' => $listingType,
                'status' => 'active',
            ]);

            return redirect()->back()->with('success', "Discovered player {$player->name} submitted directly to Transfer Market!");
        }

        return redirect()->back()->with('success', "Discovered player {$player->name} signed directly to your squad!");
    }
}
