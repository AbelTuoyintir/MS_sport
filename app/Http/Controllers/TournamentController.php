<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Game;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display the Apex Champions Cup Tournament Hub.
     */
    public function index()
    {
        $teams = Team::all();

        // If less than 8 teams exist, provide fallback team objects
        if ($teams->count() < 8) {
            $fallbackNames = ['Man City', 'Arsenal', 'Liverpool', 'Aston Villa', 'Tottenham', 'Chelsea', 'Newcastle', 'Real Madrid'];
            $teams = collect();
            foreach ($fallbackNames as $idx => $name) {
                $t = new Team();
                $t->id = $idx + 1;
                $t->team_name = $name;
                $t->primary_color = ['#1a3cff', '#ef0107', '#d00027', '#6c1d45', '#132257', '#034694', '#241f20', '#e21a23'][$idx];
                $teams->push($t);
            }
        }

        $top8 = $teams->take(8)->values();

        // Quarter Final Pairings (4 matches)
        $qf = [
            [
                'id' => 'qf1',
                'home' => $top8[0] ?? null,
                'away' => $top8[1] ?? null,
                'score' => '2 – 1',
                'winner' => $top8[0]->team_name ?? 'Team A',
                'status' => 'Finished',
                'date' => 'Apr 12, 2025'
            ],
            [
                'id' => 'qf2',
                'home' => $top8[2] ?? null,
                'away' => $top8[3] ?? null,
                'score' => '3 – 2',
                'winner' => $top8[2]->team_name ?? 'Team C',
                'status' => 'Finished',
                'date' => 'Apr 12, 2025'
            ],
            [
                'id' => 'qf3',
                'home' => $top8[4] ?? null,
                'away' => $top8[5] ?? null,
                'score' => '1 – 0',
                'winner' => $top8[4]->team_name ?? 'Team E',
                'status' => 'Finished',
                'date' => 'Apr 13, 2025'
            ],
            [
                'id' => 'qf4',
                'home' => $top8[6] ?? null,
                'away' => $top8[7] ?? null,
                'score' => '2 – 2 (4-3 p)',
                'winner' => $top8[6]->team_name ?? 'Team G',
                'status' => 'Finished',
                'date' => 'Apr 13, 2025'
            ],
        ];

        // Semi Final Pairings (2 matches)
        $sf = [
            [
                'id' => 'sf1',
                'home_name' => $qf[0]['winner'],
                'away_name' => $qf[1]['winner'],
                'score' => '1 – 2',
                'winner' => $qf[1]['winner'],
                'status' => 'Finished',
                'date' => 'Apr 26, 2025'
            ],
            [
                'id' => 'sf2',
                'home_name' => $qf[2]['winner'],
                'away_name' => $qf[3]['winner'],
                'score' => '3 – 1',
                'winner' => $qf[2]['winner'],
                'status' => 'Finished',
                'date' => 'Apr 27, 2025'
            ],
        ];

        // Final Match
        $final = [
            'id' => 'f1',
            'home_name' => $sf[0]['winner'],
            'away_name' => $sf[1]['winner'],
            'score' => 'vs',
            'winner' => null,
            'status' => 'UPCOMING',
            'date' => 'May 18, 2025 · Wembley Stadium'
        ];

        // Cup Stats
        $stats = [
            'total_goals' => 14,
            'avg_goals_per_game' => 2.33,
            'favorite_team' => $top8[0]->team_name ?? 'Man City',
            'clean_sheets' => 2,
        ];

        return view('tournaments.index', compact('teams', 'qf', 'sf', 'final', 'stats'));
    }

    /**
     * Submit a bracket prediction for the cup.
     */
    public function predictBracket(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'predicted_champion' => 'required|string|max:255',
        ]);

        return redirect()->back()->with('success', '🏆 Bracket prediction submitted successfully! Good luck, ' . e($validated['user_name']) . '!');
    }
}
