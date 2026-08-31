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
            'nationality' => 'nullable|string|max:255',
            'experience_rating' => 'required|integer|min:1|max:100',
            'weekly_fee' => 'required|numeric|min:0',
        ]);

        $validated['status'] = 'available';
        ScoutAgent::create($validated);

        return redirect()->back()->with('success', 'Scouting Agent registered successfully.');
    }

    public function destroy($id)
    {
        $scout = ScoutAgent::findOrFail($id);
        $scout->delete();

        return redirect()->back()->with('success', 'Scouting Agent removed from registry.');
    }
}
