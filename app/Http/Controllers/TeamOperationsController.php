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

    public function scoutingAiIndex()
    {
        $team = auth()->user()->team;
        $opponents = \App\Models\Team::where('id', '!=', $team->id)->orderBy('team_name', 'asc')->get();
        return view('manager.operations.scouting_ai', compact('team', 'opponents'));
    }

    public function scoutingAiGenerate(Request $request)
    {
        $request->validate([
            'opponent_team_id' => 'required|exists:teams,id',
            'opponent_formation' => 'required|string',
            'tactic_style' => 'required|string',
            'intensity' => 'required|string',
        ]);

        $myTeam = auth()->user()->team;
        $opponents = \App\Models\Team::where('id', '!=', $myTeam->id)->orderBy('team_name', 'asc')->get();
        $opponentTeam = \App\Models\Team::with('players')->findOrFail($request->opponent_team_id);

        // Fetch opponent players
        $opponentPlayers = $opponentTeam->players;
        $starPlayer = $opponentPlayers->sortByDesc('rating')->first();

        // Calculate Average Ratings
        $myPlayers = \App\Models\Player::whereIn('id', $myTeam->starting_xi ?? [])->get();
        if ($myPlayers->count() < 11) {
            $myPlayers = $myTeam->players->take(11);
        }
        $myRating = round($myPlayers->avg('rating') ?: 75, 1);
        $oppRating = round($opponentPlayers->avg('rating') ?: 75, 1);

        // Determine counter formation and recommendations
        $counterFormation = '4-3-3';
        $passingFocus = 'Mixed';
        $defensiveLine = 'Standard';
        $scoutingSummary = '';
        $starWatchInstruction = '';

        $oppFormation = $request->opponent_formation;
        $tacticStyle = $request->tactic_style;
        $intensity = $request->intensity;

        // Counter Formation logic
        if ($oppFormation === '4-3-3') {
            $counterFormation = '4-2-3-1';
            $passingFocus = 'Through the middle';
            $defensiveLine = 'High Press';
            $scoutingSummary = "The opponent plays a wide, attacking 4-3-3 with high wing-backs. This leaves spaces behind them on fast transitions. We recommend deploying a 4-2-3-1 to lock down the midfield pivot, while deploying fast wingers to hit them on the break.";
        } elseif ($oppFormation === '4-4-2') {
            $counterFormation = '4-3-3';
            $passingFocus = 'Flanks / Wing play';
            $defensiveLine = 'Standard';
            $scoutingSummary = "A classic rigid 4-4-2 setup. They are structurally solid but can be overloaded in the central midfield. A 4-3-3 with a 3-man midfield pivot creates a natural 3v2 numerical advantage. Focus passing down the wings to drag their central midfielders out of position.";
        } elseif ($oppFormation === '3-5-2') {
            $counterFormation = '4-4-2';
            $passingFocus = 'Wing play';
            $defensiveLine = 'Low Block';
            $scoutingSummary = "A 3-5-2 system offers them immense central density. However, they rely heavily on their wing-backs for width. By utilizing a 4-4-2 with traditional wide midfielders, we can isolate their wingbacks in 2v1 situations and attack the spaces beside their 3 center-backs.";
        } else {
            // 4-2-3-1 or other
            $counterFormation = '4-3-3 Holding';
            $passingFocus = 'Mixed';
            $defensiveLine = 'High Press';
            $scoutingSummary = "The opponent utilizes a modern, balanced layout. We recommend matching their intensity with a 4-3-3 Holding. The single defensive midfielder (DM) will track their central attacking midfielder, disrupting their central playmaking completely.";
        }

        // Tactic Style customization
        if ($tacticStyle === 'Tiki-Taka') {
            $scoutingSummary .= " Since the opponent prefers slow, possession-oriented Tiki-Taka, a high press with narrow compact lines is critical to intercepting short passes in their defensive third.";
        } elseif ($tacticStyle === 'Gengenpress') {
            $scoutingSummary .= " Facing an intensive high-pressing team means our players must transition quickly. Use direct long passes and long balls to completely bypass their forward press.";
        } elseif ($tacticStyle === 'Park the Bus') {
            $scoutingSummary .= " With the opponent sitting deep ('Parking the bus'), we should utilize overlapping wing-backs, slow down our tempo, and focus on cross-delivery and long-range shooting to unlock their low block.";
        }

        // Star Player Instruction
        if ($starPlayer) {
            $role = $starPlayer->position;
            if (in_array($role, ['FWD', 'Forward'])) {
                $starWatchInstruction = "{$starPlayer->name} (OVR {$starPlayer->rating}) is their primary goal threat. Instruct your center-backs to mark him tightly, drop the defensive line deeper, and avoid diving in to prevent them from exploiting gaps.";
            } elseif (in_array($role, ['MID', 'Midfielder'])) {
                $starWatchInstruction = "{$starPlayer->name} (OVR {$starPlayer->rating}) dictates their midfield play. We recommend assigned a dedicated central midfielder to man-mark him, restricting his time on the ball and cutting off their key passing lanes.";
            } else {
                $starWatchInstruction = "{$starPlayer->name} (OVR {$starPlayer->rating}) anchors their defensive line with extreme composure. Instruct your forwards to press him intensely when he has the ball, forcing him into rushed clearances and unforced errors.";
            }
        } else {
            $starWatchInstruction = "No premium star player stands out singularly, suggesting a very balanced squad. Focus on collective discipline rather than man-marking.";
        }

        // Predict win probability
        $ratingDiff = $myRating - $oppRating;
        if ($intensity === 'Overload') {
            $winProb = 50 + ($ratingDiff * 2) + 5;
        } elseif ($intensity === 'Conservative') {
            $winProb = 50 + ($ratingDiff * 2) - 5;
        } else {
            $winProb = 50 + ($ratingDiff * 2);
        }

        $winProb = max(15, min(95, $winProb));
        $drawProb = max(10, min(40, 25 - abs($ratingDiff)));
        $lossProb = 100 - $winProb - $drawProb;

        $report = [
            'opponent_name' => $opponentTeam->team_name,
            'opponent_badge' => strtoupper(substr($opponentTeam->team_name, 0, 2)),
            'opponent_color' => $opponentTeam->primary_color,
            'opponent_formation' => $oppFormation,
            'tactic_style' => $tacticStyle,
            'intensity' => $intensity,
            'my_rating' => $myRating,
            'opp_rating' => $oppRating,
            'star_player' => $starPlayer,
            'star_watch' => $starWatchInstruction,
            'counter_formation' => $counterFormation,
            'passing_focus' => $passingFocus,
            'defensive_line' => $defensiveLine,
            'scouting_summary' => $scoutingSummary,
            'probabilities' => [
                'win' => $winProb,
                'draw' => $drawProb,
                'loss' => $lossProb,
            ]
        ];

        return view('manager.operations.scouting_ai', compact('myTeam', 'opponents', 'report'));
    }
}
