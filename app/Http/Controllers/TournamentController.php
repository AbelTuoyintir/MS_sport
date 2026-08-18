<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $teams = Team::all();

        // If fewer than 8 teams, fallback to synthetic tournament list for complete bracket rendering
        if ($teams->count() < 8) {
            $quarterfinalTeams = [
                (object)['id' => 1, 'name' => 'Apex Champions FC', 'logo' => null, 'rating' => 88],
                (object)['id' => 2, 'name' => 'Titan Athletic', 'logo' => null, 'rating' => 84],
                (object)['id' => 3, 'name' => 'Vanguard City', 'logo' => null, 'rating' => 82],
                (object)['id' => 4, 'name' => 'Nova Rovers', 'logo' => null, 'rating' => 81],
                (object)['id' => 5, 'name' => 'Eclipse Dynamo', 'logo' => null, 'rating' => 85],
                (object)['id' => 6, 'name' => 'Zenith Strikers', 'logo' => null, 'rating' => 80],
                (object)['id' => 7, 'name' => 'Pulse United', 'logo' => null, 'rating' => 83],
                (object)['id' => 8, 'name' => 'Ironclad Wanderers', 'logo' => null, 'rating' => 79],
            ];
        } else {
            $quarterfinalTeams = $teams->take(8)->map(function ($team) {
                return (object)[
                    'id' => $team->id,
                    'name' => $team->name,
                    'logo' => $team->logo_path,
                    'rating' => rand(78, 89)
                ];
            })->values()->toArray();
        }

        // Bracket Structure: Quarterfinals (4 matches), Semifinals (2 matches), Final (1 match)
        $quarterfinals = [
            [
                'id' => 'QF1',
                'stage' => 'Quarterfinal 1',
                'team1' => $quarterfinalTeams[0],
                'team2' => $quarterfinalTeams[1],
                'score1' => 3,
                'score2' => 1,
                'agg' => '3 - 1',
                'winner' => $quarterfinalTeams[0],
                'status' => 'Completed'
            ],
            [
                'id' => 'QF2',
                'stage' => 'Quarterfinal 2',
                'team1' => $quarterfinalTeams[2],
                'team2' => $quarterfinalTeams[3],
                'score1' => 2,
                'score2' => 2,
                'pens' => '5 - 4',
                'winner' => $quarterfinalTeams[2],
                'status' => 'Completed'
            ],
            [
                'id' => 'QF3',
                'stage' => 'Quarterfinal 3',
                'team1' => $quarterfinalTeams[4],
                'team2' => $quarterfinalTeams[5],
                'score1' => 4,
                'score2' => 0,
                'winner' => $quarterfinalTeams[4],
                'status' => 'Completed'
            ],
            [
                'id' => 'QF4',
                'stage' => 'Quarterfinal 4',
                'team1' => $quarterfinalTeams[6],
                'team2' => $quarterfinalTeams[7],
                'score1' => 1,
                'score2' => 2,
                'winner' => $quarterfinalTeams[7],
                'status' => 'Completed'
            ],
        ];

        $semifinals = [
            [
                'id' => 'SF1',
                'stage' => 'Semifinal 1',
                'team1' => $quarterfinals[0]['winner'],
                'team2' => $quarterfinals[1]['winner'],
                'score1' => 2,
                'score2' => 1,
                'winner' => $quarterfinals[0]['winner'],
                'status' => 'Completed'
            ],
            [
                'id' => 'SF2',
                'stage' => 'Semifinal 2',
                'team1' => $quarterfinals[2]['winner'],
                'team2' => $quarterfinals[3]['winner'],
                'score1' => 1,
                'score2' => 3,
                'winner' => $quarterfinals[3]['winner'],
                'status' => 'Completed'
            ],
        ];

        $final = [
            'id' => 'F1',
            'stage' => 'Grand Final',
            'team1' => $semifinals[0]['winner'],
            'team2' => $semifinals[1]['winner'],
            'date' => 'Upcoming Sunday, 20:00 UTC',
            'stadium' => 'Apex National Arena',
            'status' => 'Upcoming'
        ];

        $tournamentStats = [
            'total_goals' => 19,
            'avg_goals_per_game' => 3.16,
            'top_scorer' => 'Lucas Vance (5 goals)',
            'clean_sheets' => 2,
        ];

        return view('tournaments.index', compact('quarterfinals', 'semifinals', 'final', 'tournamentStats'));
    }

    public function predict(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:100',
            'predicted_champion' => 'required|string|max:100',
            'final_score_home' => 'required|integer|min:0',
            'final_score_away' => 'required|integer|min:0',
        ]);

        $game = Game::first();
        if (!$game) {
            $homeTeam = Team::firstOrCreate(
                ['team_name' => 'Apex Champions FC'],
                [
                    'reference_code' => Team::generateReferenceCode(),
                    'division' => '1',
                    'team_size' => 11,
                    'primary_color' => '#f0c040',
                    'secondary_color' => '#000000',
                    'accent_color' => '#00e5ff',
                    'password' => 'secret123'
                ]
            );
            $awayTeam = Team::firstOrCreate(
                ['team_name' => 'Titan Athletic'],
                [
                    'reference_code' => Team::generateReferenceCode(),
                    'division' => '1',
                    'team_size' => 11,
                    'primary_color' => '#ff3b3b',
                    'secondary_color' => '#ffffff',
                    'accent_color' => '#00e5ff',
                    'password' => 'secret123'
                ]
            );
            $game = Game::create([
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'kickoff' => now()->addDays(7),
                'matchweek' => 1,
                'status' => 'upcoming',
            ]);
        }

        Prediction::create([
            'game_id' => $game->id,
            'user_name' => $validated['user_name'] . ' [Cup Predictor]',
            'home_score_prediction' => $validated['final_score_home'],
            'away_score_prediction' => $validated['final_score_away'],
        ]);

        return back()->with('success', 'Tournament prediction submitted! You chose ' . $validated['predicted_champion'] . ' to win the Apex Cup.');
    }
}
