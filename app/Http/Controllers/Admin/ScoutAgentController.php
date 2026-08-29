<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScoutAgent;
use Illuminate\Http\Request;

class ScoutAgentController extends Controller
{
    public function index()
    {
        $scouts = ScoutAgent::with('team')->orderBy('created_at', 'desc')->get();
        return view('admin.scouts.index', compact('scouts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'experience_rating' => 'required|integer|min:50|max:99',
            'weekly_fee' => 'required|numeric|min:100',
            'nationality' => 'nullable|string|max:10',
        ]);

        if (empty($validated['nationality'])) {
            $validated['nationality'] = '🇬🇭';
        }

        ScoutAgent::create($validated);

        return redirect()->back()->with('success', 'Scouting agent registered successfully!');
    }

    public function destroy($id)
    {
        $scout = ScoutAgent::findOrFail($id);
        $scout->delete();

        return redirect()->back()->with('success', 'Scouting agent removed successfully.');
    }
}
