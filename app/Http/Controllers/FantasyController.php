<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class FantasyController extends Controller
{
    public function index()
    {
        $players = Player::with('team')->get()->map(function ($player) {
            $player->price = $this->calculatePlayerPrice($player);
            $player->fantasy_points = $this->calculateFantasyPoints($player);
            return $player;
        });

        $userSquad = session()->get('fantasy_squad', [
            'squad_name' => 'Apex FC',
            'manager_name' => auth()->check() ? auth()->user()->name : 'Fantasy Manager',
            'player_ids' => [],
            'captain_id' => null,
            'vice_captain_id' => null,
        ]);

        $selectedPlayers = collect();
        if (!empty($userSquad['player_ids'])) {
            $selectedPlayers = $players->whereIn('id', $userSquad['player_ids'])->values();
        }

        $totalSpent = $selectedPlayers->sum('price');
        $budgetLeft = round(100.0 - $totalSpent, 1);

        $totalPoints = $selectedPlayers->sum(function ($p) use ($userSquad) {
            $pts = $p->fantasy_points;
            if ($p->id == $userSquad['captain_id']) {
                $pts *= 2;
            }
            return $pts;
        });

        return view('fantasy.index', compact(
            'players',
            'userSquad',
            'selectedPlayers',
            'totalSpent',
            'budgetLeft',
            'totalPoints'
        ));
    }

    public function storeSquad(Request $request)
    {
        $validated = $request->validate([
            'squad_name' => 'required|string|max:100',
            'manager_name' => 'required|string|max:100',
            'player_ids' => 'required|array|min:1|max:11',
            'player_ids.*' => 'exists:players,id',
            'captain_id' => 'required|exists:players,id',
            'vice_captain_id' => 'nullable|exists:players,id',
        ]);

        $selectedPlayers = Player::whereIn('id', $validated['player_ids'])->get();
        $totalCost = $selectedPlayers->sum(function ($p) {
            return $this->calculatePlayerPrice($p);
        });

        if ($totalCost > 100.0) {
            return redirect()->back()->withErrors(['budget' => 'Your fantasy squad total cost (£' . number_format($totalCost, 1) . 'M) exceeds the £100.0M budget limit!']);
        }

        if (!in_array($validated['captain_id'], $validated['player_ids'])) {
            return redirect()->back()->withErrors(['captain' => 'The designated captain must be a selected member of your squad.']);
        }

        session()->put('fantasy_squad', [
            'squad_name' => $validated['squad_name'],
            'manager_name' => $validated['manager_name'],
            'player_ids' => $validated['player_ids'],
            'captain_id' => $validated['captain_id'],
            'vice_captain_id' => $validated['vice_captain_id'] ?? null,
        ]);

        return redirect()->route('fantasy.index')->with('success', 'Fantasy squad updated successfully! Good luck this matchweek.');
    }

    public function leaderboard()
    {
        $players = Player::with('team')->get()->keyBy('id');

        $leaderboard = [
            [
                'rank' => 1,
                'manager_name' => 'Alex Ferguson',
                'squad_name' => 'Red Devils XI',
                'captain_id' => $players->first() ? $players->first()->id : 1,
                'total_points' => 148,
                'weekly_points' => 62,
                'team_value' => '99.5M',
            ],
            [
                'rank' => 2,
                'manager_name' => 'Pep Tactical',
                'squad_name' => 'Tiki-Taka Stars',
                'captain_id' => $players->skip(1)->first() ? $players->skip(1)->first()->id : 2,
                'total_points' => 142,
                'weekly_points' => 58,
                'team_value' => '98.8M',
            ],
            [
                'rank' => 3,
                'manager_name' => 'Klopp Gegenpress',
                'squad_name' => 'Heavy Metal FC',
                'captain_id' => $players->skip(2)->first() ? $players->skip(2)->first()->id : 3,
                'total_points' => 135,
                'weekly_points' => 51,
                'team_value' => '100.0M',
            ],
            [
                'rank' => 4,
                'manager_name' => 'Special One',
                'squad_name' => 'Park The Bus',
                'captain_id' => $players->first() ? $players->first()->id : 1,
                'total_points' => 129,
                'weekly_points' => 47,
                'team_value' => '96.2M',
            ],
        ];

        // Insert active user's session squad if set
        $userSquad = session()->get('fantasy_squad');
        if ($userSquad && !empty($userSquad['player_ids'])) {
            $userSelected = Player::whereIn('id', $userSquad['player_ids'])->get();
            $userPts = $userSelected->sum(function ($p) use ($userSquad) {
                $pts = $this->calculateFantasyPoints($p);
                if ($p->id == $userSquad['captain_id']) {
                    $pts *= 2;
                }
                return $pts;
            });
            $userCost = $userSelected->sum(fn($p) => $this->calculatePlayerPrice($p));

            $leaderboard[] = [
                'rank' => 5,
                'manager_name' => $userSquad['manager_name'] . ' (You)',
                'squad_name' => $userSquad['squad_name'],
                'captain_id' => $userSquad['captain_id'],
                'total_points' => $userPts,
                'weekly_points' => round($userPts * 0.4),
                'team_value' => number_format($userCost, 1) . 'M',
                'is_user' => true,
            ];

            // Re-sort leaderboard by total_points descending
            usort($leaderboard, fn($a, $b) => $b['total_points'] <=> $a['total_points']);
            foreach ($leaderboard as $idx => &$item) {
                $item['rank'] = $idx + 1;
            }
        }

        return view('fantasy.leaderboard', compact('leaderboard', 'players'));
    }

    public function calculatePlayerPrice(Player $player): float
    {
        $goals = $player->goals ?? 0;
        $assists = $player->assists ?? 0;
        $rating = $player->rating ?? 75;

        $price = 4.5 + ($goals * 0.4) + ($assists * 0.3) + (($rating - 70) * 0.15);
        return round(max(4.0, min(14.5, $price)), 1);
    }

    public function calculateFantasyPoints(Player $player): int
    {
        $pos = strtoupper($player->position ?? 'MID');
        $goals = $player->goals ?? 0;
        $assists = $player->assists ?? 0;
        $cleanSheets = $player->clean_sheets ?? 0;
        $appearances = $player->appearances ?? 1;
        $yellows = $player->yellow_cards ?? 0;
        $reds = $player->red_cards ?? 0;
        $rating = $player->rating ?? 75;

        $pts = 0;
        // Appearances
        $pts += ($appearances >= 5) ? 2 : 1;

        // Goals
        if (in_array($pos, ['GK', 'DEF', 'CB', 'RB', 'LB'])) {
            $pts += $goals * 6;
        } elseif (in_array($pos, ['MID', 'CM', 'AM', 'DM', 'LM', 'RM'])) {
            $pts += $goals * 5;
        } else {
            $pts += $goals * 4;
        }

        // Assists
        $pts += $assists * 3;

        // Clean sheets
        if (in_array($pos, ['GK', 'DEF', 'CB', 'RB', 'LB'])) {
            $pts += $cleanSheets * 4;
        } elseif (in_array($pos, ['MID', 'CM', 'AM', 'DM', 'LM', 'RM'])) {
            $pts += $cleanSheets * 1;
        }

        // Rating bonus
        if ($rating >= 85) {
            $pts += 3;
        } elseif ($rating >= 80) {
            $pts += 2;
        } elseif ($rating >= 75) {
            $pts += 1;
        }

        // Discipline
        $pts -= ($yellows * 1);
        $pts -= ($reds * 3);

        return max(0, $pts);
    }
}
