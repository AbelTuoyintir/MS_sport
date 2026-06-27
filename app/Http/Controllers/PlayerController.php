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
}
