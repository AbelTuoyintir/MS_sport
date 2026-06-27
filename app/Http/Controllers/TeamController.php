<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('primaryOwner')->get();
        return response()->json($teams);
    }

    public function show($id)
    {
        $team = Team::with(['owners', 'payment', 'players'])->findOrFail($id);
        return response()->json($team);
    }
}
