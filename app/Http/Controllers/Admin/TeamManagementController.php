<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeamManagementController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('team_name')->paginate(20);
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'team_size' => 'required|integer|min:1',
            'division' => 'required|string|max:100',
            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'home_stadium' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'founded_year' => 'nullable|string|max:4',
            'registration_status' => 'required|in:pending,approved,rejected',
        ]);

        $validated['reference_code'] = Team::generateReferenceCode();
        $validated['password'] = Hash::make('password123'); // Default password

        Team::create($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Team created successfully.');
    }

    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'team_size' => 'required|integer|min:1',
            'division' => 'required|string|max:100',
            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'home_stadium' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'founded_year' => 'nullable|string|max:4',
            'registration_status' => 'required|in:pending,approved,rejected',
        ]);

        $team->update($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Team updated successfully.');
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Team deleted successfully.');
    }
}
