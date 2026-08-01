<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Team;

class TacticsController extends Controller
{
    public function index()
    {
        $team = auth()->user()->team;
        $players = $team->players;
        $formation = $team->formation ?? '4-4-2';
        $starting_xi = $team->starting_xi ?? [];

        // Fetch opposing teams for friendly simulation
        $opponents = Team::where('id', '!=', $team->id)->orderBy('team_name')->get();

        return view('manager.tactics', compact('team', 'players', 'formation', 'starting_xi', 'opponents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'formation' => 'required|string',
            'starting_xi' => 'required|array|min:11|max:11',
            'starting_xi.*' => 'exists:players,id'
        ]);

        $team = auth()->user()->team;

        // Verify all players belong to this team
        foreach ($request->starting_xi as $playerId) {
            $player = Player::find($playerId);
            if ($player->team_id !== $team->id) {
                return redirect()->back()->with('error', 'Invalid player selection.');
            }
        }

        $team->update([
            'formation' => $request->formation,
            'starting_xi' => $request->starting_xi
        ]);

        return redirect()->route('manager.tactics.index')->with('success', 'Tactics updated successfully.');
    }

    public function simulate(Request $request)
    {
        $request->validate([
            'opponent_team_id' => 'required|exists:teams,id'
        ]);

        $myTeam = auth()->user()->team;
        $opponentTeam = Team::with('players')->findOrFail($request->opponent_team_id);

        if ($myTeam->id === $opponentTeam->id) {
            return response()->json(['error' => 'Cannot simulate a match against yourself!'], 400);
        }

        // Calculate average ratings
        $myPlayers = Player::whereIn('id', $myTeam->starting_xi ?? [])->get();
        if ($myPlayers->count() < 11) {
            $myPlayers = $myTeam->players->take(11);
        }
        $myRating = $myPlayers->avg('rating') ?: 75;

        // Opponent Starting XI / first 11
        $oppStartingXiIds = $opponentTeam->starting_xi ?? [];
        $oppPlayers = Player::whereIn('id', $oppStartingXiIds)->get();
        if ($oppPlayers->count() < 11) {
            $oppPlayers = $opponentTeam->players->take(11);
        }
        $oppRating = $oppPlayers->avg('rating') ?: 75;

        // Simulate score based on ratings
        $myGoalChance = ($myRating / ($myRating + $oppRating)) * 5;
        $oppGoalChance = ($oppRating / ($myRating + $oppRating)) * 5;

        $myScore = max(0, round($myGoalChance + rand(-2, 2)));
        $oppScore = max(0, round($oppGoalChance + rand(-2, 2)));

        // Generate play-by-play events
        $events = [];

        // Match Start
        $events[] = [
            'minute' => 0,
            'type' => 'kickoff',
            'team_id' => null,
            'description' => "Referee blows the whistle! Kickoff at {$myTeam->home_stadium}. Friendly tactical simulator is underway!"
        ];

        // Distributed goals and key moments across minutes
        $matchMinutes = [];
        for ($i = 0; $i < $myScore; $i++) {
            $matchMinutes[] = ['type' => 'goal', 'team' => 'home', 'min' => rand(1, 89)];
        }
        for ($i = 0; $i < $oppScore; $i++) {
            $matchMinutes[] = ['type' => 'goal', 'team' => 'away', 'min' => rand(1, 89)];
        }

        // Add some random highlight moments: card, shot saved
        $highlightsCount = rand(3, 5);
        for ($i = 0; $i < $highlightsCount; $i++) {
            $type = rand(0, 1) ? 'card' : 'shot_saved';
            $team = rand(0, 1) ? 'home' : 'away';
            $matchMinutes[] = ['type' => $type, 'team' => $team, 'min' => rand(1, 89)];
        }

        // Sort events by minute
        usort($matchMinutes, function ($a, $b) {
            return $a['min'] <=> $b['min'];
        });

        $currentHomeScore = 0;
        $currentAwayScore = 0;
        $halfTimeIncluded = false;

        foreach ($matchMinutes as $mEvent) {
            $min = $mEvent['min'];
            if ($min >= 45 && !$halfTimeIncluded) {
                $events[] = [
                    'minute' => 45,
                    'type' => 'half_time',
                    'team_id' => null,
                    'description' => "Half Time! Teams head to the tunnel. Score: {$myTeam->team_name} {$currentHomeScore} - {$currentAwayScore} {$opponentTeam->team_name}."
                ];
                $halfTimeIncluded = true;
            }

            $isHome = $mEvent['team'] === 'home';
            $activeTeam = $isHome ? $myTeam : $opponentTeam;
            $activePlayers = $isHome ? $myPlayers : $oppPlayers;

            if ($mEvent['type'] === 'goal') {
                if ($isHome) {
                    $currentHomeScore++;
                } else {
                    $currentAwayScore++;
                }

                $scorer = $activePlayers->random();
                $desc = "⚽ GOAL! {$scorer->name} finds the back of the net with a brilliant strike for {$activeTeam->team_name}! Score: {$currentHomeScore} - {$currentAwayScore}.";

                $events[] = [
                    'minute' => $min,
                    'type' => 'goal',
                    'team_id' => $activeTeam->id,
                    'description' => $desc
                ];
            } elseif ($mEvent['type'] === 'card') {
                $player = $activePlayers->random();
                $isRed = rand(1, 10) === 10;
                $cardType = $isRed ? 'red_card' : 'yellow_card';
                $emoji = $isRed ? '🟥' : '🟨';
                $desc = "{$emoji} BOOKING! {$player->name} ({$activeTeam->team_name}) receives a " . ($isRed ? 'straight red card!' : 'yellow card for a late challenge.');

                $events[] = [
                    'minute' => $min,
                    'type' => $cardType,
                    'team_id' => $activeTeam->id,
                    'description' => $desc
                ];
            } else {
                // Shot saved
                $player = $activePlayers->random();
                $otherPlayers = !$isHome ? $myPlayers : $oppPlayers;
                $gk = $otherPlayers->where('position', 'GK')->first() ?: $otherPlayers->random();
                $desc = "🧤 SAVED! {$player->name} unleashes a powerful shot, but {$gk->name} makes an outstanding diving save to keep it out!";

                $events[] = [
                    'minute' => $min,
                    'type' => 'shot_saved',
                    'team_id' => $activeTeam->id,
                    'description' => $desc
                ];
            }
        }

        // Half time check if it didn't trigger
        if (!$halfTimeIncluded) {
            $events[] = [
                'minute' => 45,
                'type' => 'half_time',
                'team_id' => null,
                'description' => "Half Time! Score: {$myTeam->team_name} {$currentHomeScore} - {$currentAwayScore} {$opponentTeam->team_name}."
            ];
        }

        // Full Time
        $events[] = [
            'minute' => 90,
            'type' => 'full_time',
            'team_id' => null,
            'description' => "Full Time whistle blows! Final score: {$myTeam->team_name} {$myScore} - {$oppScore} {$opponentTeam->team_name}."
        ];

        // Ensure events are chronological
        usort($events, function ($a, $b) {
            if ($a['minute'] === $b['minute']) {
                // Keep kickoff first, full_time last
                if ($a['type'] === 'kickoff') return -1;
                if ($b['type'] === 'kickoff') return 1;
                if ($a['type'] === 'full_time') return 1;
                if ($b['type'] === 'full_time') return -1;
            }
            return $a['minute'] <=> $b['minute'];
        });

        // Compute Match Stats
        $possession = rand(40, 60);
        $myShots = rand(8, 22);
        $oppShots = rand(8, 22);

        $stats = [
            'possession' => [$possession, 100 - $possession],
            'shots' => [$myShots, $oppShots],
            'shots_on_target' => [round($myShots * 0.4), round($oppShots * 0.4)],
            'fouls' => [rand(5, 15), rand(5, 15)],
            'corners' => [rand(2, 9), rand(2, 9)]
        ];

        return response()->json([
            'home_team' => [
                'name' => $myTeam->team_name,
                'primary_color' => $myTeam->primary_color,
                'badge' => strtoupper(substr($myTeam->team_name, 0, 2))
            ],
            'away_team' => [
                'name' => $opponentTeam->team_name,
                'primary_color' => $opponentTeam->primary_color,
                'badge' => strtoupper(substr($opponentTeam->team_name, 0, 2))
            ],
            'home_score' => $myScore,
            'away_score' => $oppScore,
            'events' => $events,
            'stats' => $stats
        ]);
    }
}
