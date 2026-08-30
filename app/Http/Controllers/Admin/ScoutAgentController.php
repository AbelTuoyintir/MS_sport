<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScoutAgent;
use Illuminate\Http\Request;

class ScoutAgentController extends Controller
{
    public function index()
    {
        $agents = ScoutAgent::withCount('teams')->orderBy('id', 'desc')->get();
        return view('admin.scouts.index', compact('agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'experience_rating' => 'required|integer|min:1|max:5',
            'specialization' => 'required|string|max:255',
            'weekly_fee' => 'required|numeric|min:0',
        ]);

        ScoutAgent::create($validated);

        return redirect()->back()->with('success', 'Scouting agent registered successfully.');
    }

    public function destroy($id)
    {
        $agent = ScoutAgent::findOrFail($id);
        $agent->delete();

        return redirect()->back()->with('success', 'Scouting agent removed successfully.');
    }
}
