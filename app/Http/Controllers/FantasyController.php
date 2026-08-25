<?php

namespace App\Http\Controllers;

use App\Models\FantasyTeam;
use App\Models\FantasyPlayer;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FantasyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $fantasyTeam = null;

        if ($user) {
            $fantasyTeam = FantasyTeam::with(['fantasyPlayers.player.team'])->where('user_id', $user->id)->first();
        }

        // Available players with fantasy valuation and stats
        $players = Player::with('team')->get()->map(function ($player) {
            $cost = round(5.0 + (($player->rating ?? 75) - 60) * 0.2, 1);
            if ($cost < 4.0) $cost = 4.0;
            if ($cost > 15.0) $cost = 15.0;

            $points = ($player->goals * 4) + ($player->assists * 3) + ($player->clean_sheets * 4)
                      - ($player->yellow_cards * 1) - ($player->red_cards * 3) + (($player->potm_count ?? 0) * 3);

            $player->fantasy_cost = $cost;
            $player->fantasy_points = max(0, $points);
            return $player;
        })->sortByDesc('fantasy_points')->values();

        $leaderboard = FantasyTeam::with('user')->orderBy('total_points', 'desc')->take(10)->get();

        return view('fantasy.index', compact('fantasyTeam', 'players', 'leaderboard'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'player_ids' => 'required|array|min:5|max:11',
            'player_ids.*' => 'exists:players,id',
            'captain_id' => 'required|exists:players,id',
        ]);

        $user = Auth::user();

        // Calculate total cost
        $selectedPlayers = Player::whereIn('id', $request->player_ids)->get();
        $totalCost = 0;
        foreach ($selectedPlayers as $player) {
            $cost = round(5.0 + (($player->rating ?? 75) - 60) * 0.2, 1);
            if ($cost < 4.0) $cost = 4.0;
            if ($cost > 15.0) $cost = 15.0;
            $totalCost += $cost;
        }

        if ($totalCost > 100.0) {
            return redirect()->back()->withErrors(['budget' => 'Squad cost exceeds £100.0M budget limit!']);
        }

        // Calculate total fantasy points
        $totalPoints = 0;
        foreach ($selectedPlayers as $player) {
            $pts = ($player->goals * 4) + ($player->assists * 3) + ($player->clean_sheets * 4)
                   - ($player->yellow_cards * 1) - ($player->red_cards * 3) + (($player->potm_count ?? 0) * 3);
            if ($player->id == $request->captain_id) {
                $pts *= 2; // Captain double points
            }
            $totalPoints += max(0, $pts);
        }

        $fantasyTeam = FantasyTeam::updateOrCreate(
            ['user_id' => $user ? $user->id : null, 'name' => $request->name],
            [
                'budget_remaining' => 100.0 - $totalCost,
                'total_points' => $totalPoints,
            ]
        );

        // Sync squad players
        FantasyPlayer::where('fantasy_team_id', $fantasyTeam->id)->delete();
        foreach ($request->player_ids as $pid) {
            FantasyPlayer::create([
                'fantasy_team_id' => $fantasyTeam->id,
                'player_id' => $pid,
                'is_captain' => ($pid == $request->captain_id),
                'is_starter' => true,
            ]);
        }

        return redirect()->route('fantasy.index')->with('success', 'Fantasy Squad saved successfully!');
    }

    public function leaderboard()
    {
        $leaderboard = FantasyTeam::with(['user', 'fantasyPlayers.player'])->orderBy('total_points', 'desc')->paginate(20);
        return view('fantasy.leaderboard', compact('leaderboard'));
    }
}
