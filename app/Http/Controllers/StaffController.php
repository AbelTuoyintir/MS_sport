<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'joined_at' => 'nullable|date',
        ]);

        $validated['team_id'] = auth()->user()->team_id;

        Staff::create($validated);

        return redirect()->back()->with('success', 'Staff member added successfully.');
    }

    public function destroy($id)
    {
        $staff = Staff::where('team_id', auth()->user()->team_id)->findOrFail($id);
        $staff->delete();

        return redirect()->back()->with('success', 'Staff member removed successfully.');
    }
}
