<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Player;

class TacticsSimulationController extends Controller
{
    public function showForm()
    {
        $user = auth()->user();
        if (!$user || !$user->team) {
            return redirect()->route('home')->with('error', 'You must manage a team to access the simulator.');
        }

        $myTeam = $user->team;
        $opponents = Team::where('id', '!=', $myTeam->id)->get();

        return view('manager.tactics.simulate', compact('myTeam', 'opponents'));
    }

    public function simulate(Request $request)
    {
        $request->validate([
            'opponent_team_id' => 'required|exists:teams,id',
            'strategy' => 'required|in:attacking,balanced,defensive'
        ]);

        $user = auth()->user();
        if (!$user || !$user->team) {
            return redirect()->route('home')->with('error', 'Unauthorized.');
        }

        $myTeam = $user->team;
        $opponentTeam = Team::findOrFail($request->opponent_team_id);
        $strategy = $request->strategy;

        // Calculate My Team average rating
        $myPlayersCount = count($myTeam->starting_xi ?? []);
        if ($myPlayersCount === 11) {
            $myRating = Player::whereIn('id', $myTeam->starting_xi)->avg('rating') ?? 70;
        } else {
            $myRating = Player::where('team_id', $myTeam->id)->avg('rating') ?? 70;
        }

        // Calculate Opponent Team average rating
        $oppRating = Player::where('team_id', $opponentTeam->id)->avg('rating') ?? 72;

        // Adjust rating based on Strategy
        $adjustedMyRating = $myRating;
        if ($strategy === 'attacking') {
            $adjustedMyRating += 5; // Higher attacking power, slightly riskier
        } elseif ($strategy === 'defensive') {
            $adjustedMyRating -= 2; // Better structure, fewer open play events
        }

        // Run simulation logic
        $homeScore = 0;
        $awayScore = 0;
        $events = [];

        // Fetch squad players for events
        $mySquad = Player::where('team_id', $myTeam->id)->get();
        if ($mySquad->isEmpty()) {
            $mySquad = collect([new Player(['name' => 'Manager (Guest)', 'position' => 'FWD', 'rating' => 70])]);
        }
        $oppSquad = Player::where('team_id', $opponentTeam->id)->get();
        if ($oppSquad->isEmpty()) {
            $oppSquad = collect([new Player(['name' => 'Opponent Striker', 'position' => 'FWD', 'rating' => 72])]);
        }

        // Minute distribution
        $minutes = [8, 15, 23, 31, 41, 45, 52, 60, 68, 77, 85, 90];
        $possessionRatio = $adjustedMyRating / ($adjustedMyRating + $oppRating);

        foreach ($minutes as $min) {
            if ($min == 45) {
                $events[] = [
                    'minute' => 45,
                    'type' => 'info',
                    'title' => 'Half-Time',
                    'description' => "Referee blows the whistle for half-time. Score: {$myTeam->team_name} {$homeScore} - {$awayScore} {$opponentTeam->team_name}.",
                ];
                continue;
            }

            if ($min == 90) {
                $events[] = [
                    'minute' => 90,
                    'type' => 'info',
                    'title' => 'Full-Time',
                    'description' => "Full-time! The simulation ends. Final Score: {$myTeam->team_name} {$homeScore} - {$awayScore} {$opponentTeam->team_name}.",
                ];
                continue;
            }

            // Decide who gets the highlight
            $rand = mt_rand(1, 100) / 100;
            $isMyEvent = $rand <= $possessionRatio;

            $activeTeam = $isMyEvent ? $myTeam : $opponentTeam;
            $activeSquad = $isMyEvent ? $mySquad : $oppSquad;
            $passiveTeam = $isMyEvent ? $opponentTeam : $myTeam;

            // Decide event type: Goal, Attempt, Card, Foul
            $roll = mt_rand(1, 100);

            if ($roll <= 35) {
                // Goal attempt
                $scorer = $activeSquad->random();
                $isGoal = mt_rand(1, 100) <= ($scorer->rating / 2 + ($strategy === 'attacking' && $isMyEvent ? 10 : 0));

                if ($isGoal) {
                    if ($isMyEvent) {
                        $homeScore++;
                    } else {
                        $awayScore++;
                    }
                    $events[] = [
                        'minute' => $min,
                        'type' => 'goal',
                        'title' => 'GOAL!',
                        'description' => "{$scorer->name} scores a brilliant goal for {$activeTeam->team_name}! A superb strike into the top corner.",
                        'team_id' => $activeTeam->id,
                        'player_name' => $scorer->name
                    ];
                } else {
                    $events[] = [
                        'minute' => $min,
                        'type' => 'attempt',
                        'title' => 'Chance Missed',
                        'description' => "{$scorer->name} takes a shot for {$activeTeam->team_name} but it's saved/goes wide.",
                        'team_id' => $activeTeam->id,
                    ];
                }
            } elseif ($roll <= 65) {
                // Foul or Card
                $player = $activeSquad->random();
                $cardRoll = mt_rand(1, 100);
                if ($cardRoll <= 40) {
                    $events[] = [
                        'minute' => $min,
                        'type' => 'card',
                        'title' => 'Yellow Card',
                        'description' => "{$player->name} receives a yellow card for a hard tackle.",
                        'team_id' => $activeTeam->id,
                        'player_name' => $player->name
                    ];
                } else {
                    $events[] = [
                        'minute' => $min,
                        'type' => 'foul',
                        'title' => 'Foul Commited',
                        'description' => "Foul by {$player->name} ({$activeTeam->team_name}) in the midfield.",
                        'team_id' => $activeTeam->id,
                    ];
                }
            } else {
                // Tactical observation
                $desc = $isMyEvent ? "{$myTeam->team_name} dominates possession, passing cleanly under the {$strategy} setup." : "{$opponentTeam->team_name} is pushing forward, trying to breach {$myTeam->team_name}'s defensive line.";
                $events[] = [
                    'minute' => $min,
                    'type' => 'tactical',
                    'title' => 'Tactical Play',
                    'description' => $desc,
                    'team_id' => $activeTeam->id,
                ];
            }
        }

        // Stats summary calculation
        $myPossession = round($possessionRatio * 100 + mt_rand(-5, 5));
        $myPossession = max(25, min(75, $myPossession));
        $oppPossession = 100 - $myPossession;

        $myShots = mt_rand(6, 18) + ($strategy === 'attacking' ? 3 : ($strategy === 'defensive' ? -2 : 0));
        $oppShots = mt_rand(6, 18) - ($strategy === 'defensive' ? 4 : 0);

        $myShotsOnTarget = round($myShots * (mt_rand(30, 60) / 100));
        $oppShotsOnTarget = round($oppShots * (mt_rand(30, 60) / 100));

        // Make sure goals doesn't exceed shots on target
        $myShotsOnTarget = max($homeScore, $myShotsOnTarget);
        $oppShotsOnTarget = max($awayScore, $oppShotsOnTarget);

        $myFouls = mt_rand(5, 15);
        $oppFouls = mt_rand(5, 15);

        $mySaves = $oppShotsOnTarget - $awayScore;
        $oppSaves = $myShotsOnTarget - $homeScore;

        $myCardsCount = collect($events)->where('team_id', $myTeam->id)->where('type', 'card')->count();
        $oppCardsCount = collect($events)->where('team_id', $opponentTeam->id)->where('type', 'card')->count();

        $stats = [
            'possession' => [$myPossession, $oppPossession],
            'shots' => [$myShots, $oppShots],
            'shots_on_target' => [$myShotsOnTarget, $oppShotsOnTarget],
            'fouls' => [$myFouls, $oppFouls],
            'saves' => [$mySaves, $oppSaves],
            'cards' => [$myCardsCount, $oppCardsCount],
        ];

        $simulationResult = [
            'my_team' => $myTeam,
            'opponent_team' => $opponentTeam,
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'strategy' => $strategy,
            'events' => $events,
            'stats' => $stats,
        ];

        $opponents = Team::where('id', '!=', $myTeam->id)->get();

        return view('manager.tactics.simulate', compact('myTeam', 'opponents', 'simulationResult'));
    }
}
