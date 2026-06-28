<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Equipment;
use App\Models\ScoutReport;
use Illuminate\Http\Request;

class TeamOperationsController extends Controller
{
    public function financeIndex()
    {
        $finances = Finance::where('team_id', auth()->user()->team_id)->orderBy('date', 'desc')->get();
        $balance = Finance::where('team_id', auth()->user()->team_id)->where('type', 'income')->sum('amount') -
                   Finance::where('team_id', auth()->user()->team_id)->where('type', 'expense')->sum('amount');

        return view('manager.operations.finance', compact('finances', 'balance'));
    }

    public function equipmentIndex()
    {
        $equipment = Equipment::where('team_id', auth()->user()->team_id)->get();
        return view('manager.operations.equipment', compact('equipment'));
    }

    public function scoutingIndex()
    {
        $reports = ScoutReport::where('team_id', auth()->user()->team_id)->orderBy('rating', 'desc')->get();
        return view('manager.operations.scouting', compact('reports'));
    }

    public function storeEquipment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'total_quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0|max_digits:10',
            'condition' => 'required|string',
        ]);

        $validated['team_id'] = auth()->user()->team_id;
        Equipment::create($validated);

        return redirect()->back()->with('success', 'Equipment added.');
    }

    public function storeScout(Request $request)
    {
        $validated = $request->validate([
            'player_name' => 'required|string|max:255',
            'position' => 'nullable|string',
            'age' => 'nullable|integer',
            'rating' => 'required|integer|min:1|max:5',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'status' => 'required|in:shortlisted,trial,monitoring,ignored',
        ]);

        $validated['team_id'] = auth()->user()->team_id;
        ScoutReport::create($validated);

        return redirect()->back()->with('success', 'Scout report added.');
    }

    public function storeFinance(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['team_id'] = auth()->user()->team_id;
        Finance::create($validated);

        return redirect()->back()->with('success', 'Financial record added.');
    }
}
