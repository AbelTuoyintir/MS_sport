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
        $userTeam = auth()->user()->team;

        $availableAgents = ScoutAgent::where('status', 'available')->get();
        $myScouts = $userTeam ? $userTeam->scoutAgents : collect();

        return view('manager.scouts', compact('availableAgents', 'myScouts', 'userTeam'));
    }

    public function sign($id)
    {
        $userTeam = auth()->user()->team;
        if (!$userTeam) {
            return redirect()->back()->with('error', 'You must have an assigned team to hire a scout.');
        }

        $scout = ScoutAgent::findOrFail($id);

        if ($scout->status === 'hired') {
            return redirect()->back()->with('error', 'This scout is already hired by another club.');
        }

        $scout->update([
            'status' => 'hired',
            'team_id' => $userTeam->id,
        ]);

        return redirect()->back()->with('success', "{$scout->name} has been signed as your scouting agent!");
    }

    public function release($id)
    {
        $userTeam = auth()->user()->team;
        $scout = ScoutAgent::findOrFail($id);

        if ($scout->team_id !== $userTeam?->id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $scout->update([
            'status' => 'available',
            'team_id' => null,
        ]);

        return redirect()->back()->with('success', "{$scout->name} has been released from your club.");
    }

    public function submitPlayer(Request $request)
    {
        $userTeam = auth()->user()->team;
        if (!$userTeam) {
            return redirect()->back()->with('error', 'You must have a team assigned to submit players.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:GK,DEF,MID,FWD',
            'rating' => 'required|integer|min:50|max:99',
            'nationality' => 'nullable|string|max:10',
            'destination' => 'required|in:squad,market',
            'asking_price' => 'nullable|numeric|min:0',
            'listing_type' => 'nullable|in:permanent,loan_half,loan_full',
        ]);

        $nationality = $validated['nationality'] ?? '🇬🇭';

        // Create player assigned to manager's team
        $player = Player::create([
            'team_id' => $userTeam->id,
            'name' => $validated['name'],
            'position' => $validated['position'],
            'rating' => $validated['rating'],
            'nationality' => $nationality,
            'number' => rand(1, 99),
            'goals' => 0,
            'assists' => 0,
            'appearances' => 0,
        ]);

        if ($validated['destination'] === 'market') {
            $type = $validated['listing_type'] ?? 'permanent';
            $askingPrice = $validated['asking_price'] ?? ($player->rating * 10000);

            TransferListing::create([
                'player_id' => $player->id,
                'team_id' => $userTeam->id,
                'asking_price' => $askingPrice,
                'reason' => 'Newly discovered talent submitted directly to transfer market.',
                'type' => $type,
                'status' => 'active',
            ]);

            return redirect()->back()->with('success', "New scouted player '{$player->name}' created and listed on the transfer market!");
        }

        return redirect()->back()->with('success', "New scouted player '{$player->name}' created and added directly to your squad!");
    }
}
