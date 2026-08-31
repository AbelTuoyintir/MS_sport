<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\ScoutAgent;
use App\Models\TransferListing;
use Illuminate\Http\Request;

class ScoutManagementController extends Controller
{
    public function index()
    {
        $team = auth()->user()->team;
        $myScouts = ScoutAgent::where('team_id', $team->id)->get();
        $availableScouts = ScoutAgent::where('status', 'available')->orWhereNull('team_id')->get();

        return view('manager.scouts.index', compact('team', 'myScouts', 'availableScouts'));
    }

    public function signScout(Request $request, $id)
    {
        $team = auth()->user()->team;
        $scout = ScoutAgent::findOrFail($id);

        if ($scout->team_id && $scout->team_id !== $team->id) {
            return redirect()->back()->with('error', 'Scout is already hired by another team.');
        }

        $scout->update([
            'team_id' => $team->id,
            'status' => 'hired',
        ]);

        return redirect()->back()->with('success', "Scouting Agent {$scout->name} successfully signed to your club.");
    }

    public function releaseScout(Request $request, $id)
    {
        $team = auth()->user()->team;
        $scout = ScoutAgent::where('team_id', $team->id)->findOrFail($id);

        $scout->update([
            'team_id' => null,
            'status' => 'available',
        ]);

        return redirect()->back()->with('success', "Scouting Agent {$scout->name} released to the free agent pool.");
    }

    public function submitDiscoveredPlayer(Request $request)
    {
        $team = auth()->user()->team;

        $validated = $request->validate([
            'scout_agent_id' => 'required|exists:scout_agents,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:10',
            'age' => 'required|integer|min:15|max:40',
            'nationality' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:50|max:99',
            'asking_price' => 'nullable|numeric|min:0',
            'list_on_market' => 'nullable|boolean',
            'deal_type' => 'nullable|in:permanent,loan_half,loan_full',
        ]);

        $scout = ScoutAgent::where('team_id', $team->id)->findOrFail($validated['scout_agent_id']);

        $player = Player::create([
            'team_id' => $team->id,
            'name' => $validated['name'],
            'position' => $validated['position'],
            'age' => $validated['age'],
            'nationality' => $validated['nationality'] ?? 'Ghanaian',
            'rating' => $validated['rating'],
            'status' => 'active',
            'number' => rand(1, 99),
        ]);

        if (!empty($validated['list_on_market'])) {
            TransferListing::create([
                'player_id' => $player->id,
                'team_id' => $team->id,
                'asking_price' => $validated['asking_price'] ?? 10000,
                'reason' => "Discovered & submitted by Scouting Agent {$scout->name}",
                'type' => str_contains($validated['deal_type'] ?? 'permanent', 'loan') ? 'loan' : 'permanent',
                'deal_type' => $validated['deal_type'] ?? 'permanent',
                'scout_agent_id' => $scout->id,
                'status' => 'active',
            ]);
        }

        return redirect()->back()->with('success', "Discovered player {$player->name} submitted to team squad successfully!");
    }
}
