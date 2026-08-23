<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use Illuminate\Http\Request;

class ClubRankingController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::with('players')->get();
        $finishedGames = Game::where('status', 'finished')->get();

        $rankings = $teams->map(function ($team) use ($finishedGames) {
            // Match record
            $homeGames = $finishedGames->where('home_team_id', $team->id);
            $awayGames = $finishedGames->where('away_team_id', $team->id);

            $played = $homeGames->count() + $awayGames->count();
            $wins = 0;
            $draws = 0;
            $losses = 0;
            $gf = 0;
            $ga = 0;
            $cleanSheets = 0;

            foreach ($homeGames as $game) {
                $gf += $game->home_score;
                $ga += $game->away_score;
                if ($game->home_score > $game->away_score) $wins++;
                elseif ($game->home_score == $game->away_score) $draws++;
                else $losses++;

                if ($game->away_score == 0) $cleanSheets++;
            }

            foreach ($awayGames as $game) {
                $gf += $game->away_score;
                $ga += $game->home_score;
                if ($game->away_score > $game->home_score) $wins++;
                elseif ($game->away_score == $game->home_score) $draws++;
                else $losses++;

                if ($game->home_score == 0) $cleanSheets++;
            }

            $points = ($wins * 3) + $draws;
            $gd = $gf - $ga;

            // Player stats & valuations
            $players = $team->players;
            $squadSize = $players->count();
            $avgRating = $squadSize > 0 ? round($players->avg('rating'), 1) : 75;

            $totalMarketValue = $players->sum(function ($player) {
                $rating = $player->rating ?: 70;
                $goals = $player->goals ?: 0;
                $assists = $player->assists ?: 0;
                $age = $player->age ?: 25;
                $val = ($rating * 0.8) + ($goals * 1.5) + ($assists * 1.0) + max(0, (30 - $age) * 0.4);
                return round(max(2.0, $val), 1);
            });

            if ($squadSize == 0) {
                $totalMarketValue = 45.0; // Default baseline for teams without players registered
            }

            // CPI (Club Power Index 0 - 100)
            $winRate = $played > 0 ? ($wins / $played) : 0.5;
            $cleanSheetRatio = $played > 0 ? ($cleanSheets / $played) : 0.3;
            $goalFactor = min(1.0, max(0.0, ($gd + 20) / 40));
            $ratingFactor = min(1.0, $avgRating / 100);
            $valuationFactor = min(1.0, $totalMarketValue / 200);

            $cpiScore = round(
                ($winRate * 30) +
                ($goalFactor * 25) +
                ($cleanSheetRatio * 15) +
                ($ratingFactor * 20) +
                ($valuationFactor * 10),
                1
            );

            // Offense and Defense index
            $offenseIndex = round(min(99, max(50, ($gf * 2.5) + ($avgRating * 0.7))), 1);
            $defenseIndex = round(min(99, max(50, ($cleanSheets * 5.0) + ($avgRating * 0.7) - ($ga * 0.8))), 1);

            // Financial Tier
            if ($totalMarketValue >= 100) {
                $tier = 'Superclub Elite';
                $tierColor = 'amber-400';
            } elseif ($totalMarketValue >= 60) {
                $tier = 'Continental Contender';
                $tierColor = 'cyan-400';
            } elseif ($totalMarketValue >= 30) {
                $tier = 'Mid-Table Titan';
                $tierColor = 'emerald-400';
            } else {
                $tier = 'Rising Challenger';
                $tierColor = 'gray-400';
            }

            return [
                'team' => $team,
                'played' => $played,
                'wins' => $wins,
                'draws' => $draws,
                'losses' => $losses,
                'gf' => $gf,
                'ga' => $ga,
                'gd' => $gd,
                'clean_sheets' => $cleanSheets,
                'points' => $points,
                'squad_size' => $squadSize,
                'avg_rating' => $avgRating,
                'total_market_value' => round($totalMarketValue, 1),
                'cpi' => $cpiScore,
                'offense_index' => $offenseIndex,
                'defense_index' => $defenseIndex,
                'tier' => $tier,
                'tier_color' => $tierColor,
            ];
        })->sortByDesc('cpi')->values();

        // Assign rankings
        $rankings = $rankings->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });

        // Comparison selection
        $team1Id = $request->query('team1_id', $rankings->first()['team']->id ?? null);
        $team2Id = $request->query('team2_id', $rankings->skip(1)->first()['team']->id ?? null);

        $compare1 = $rankings->firstWhere('team.id', $team1Id) ?? $rankings->first();
        $compare2 = $rankings->firstWhere('team.id', $team2Id) ?? ($rankings->skip(1)->first() ?? $compare1);

        return view('rankings', compact('rankings', 'compare1', 'compare2'));
    }
}
