<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatsAndPredictionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the /stats endpoint loads and contains the new premium player metrics.
     */
    public function test_stats_contains_new_metrics()
    {
        // Seed a team
        $team = Team::create([
            'reference_code' => 'TEAM-A',
            'team_name' => 'Avengers FC',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#121212',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        // Seed some players with new metrics
        Player::create([
            'team_id' => $team->id,
            'name' => 'Tony Stark',
            'position' => 'midfielder',
            'rating' => 9.2,
            'clean_sheets' => 5,
            'motm_awards' => 4,
            'goals' => 10,
            'assists' => 12,
            'appearances' => 15,
            'yellow_cards' => 1,
            'red_cards' => 0,
        ]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('Tony Stark');
        $response->assertSee('Avengers FC');
        $response->assertViewHas('topRated');
        $response->assertViewHas('cleanSheets');
        $response->assertViewHas('motmAwards');
    }

    /**
     * Test that the /leaderboard endpoint computes dynamic scoring correctly:
     * - 3 points for exact score predictions.
     * - 1 point for predicting correct match outcomes without the exact score.
     * - 0 points for incorrect predictions.
     */
    public function test_predictions_leaderboard_computes_points_correctly()
    {
        // Seed two teams
        $homeTeam = Team::create([
            'reference_code' => 'HOME-TEAM',
            'team_name' => 'Asgard United',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $awayTeam = Team::create([
            'reference_code' => 'AWAY-TEAM',
            'team_name' => 'Wakanda City',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#00ff00',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        // Create a finished game: Asgard United 3 - 1 Wakanda City
        $game = Game::create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'home_score' => 3,
            'away_score' => 1,
            'status' => 'finished',
            'kickoff' => now()->subDay(),
            'matchweek' => 1,
        ]);

        // 1. Thor predicts 3-1: EXACT MATCH (Should be 3 points)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'Thor Odinson',
            'home_score_prediction' => 3,
            'away_score_prediction' => 1,
        ]);

        // 2. Loki predicts 2-0: Correct Outcome (Home Win) but not exact score (Should be 1 point)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'Loki Laufeyson',
            'home_score_prediction' => 2,
            'away_score_prediction' => 0,
        ]);

        // 3. Bruce predicts 1-1: Incorrect Outcome (Should be 0 points)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'Bruce Banner',
            'home_score_prediction' => 1,
            'away_score_prediction' => 1,
        ]);

        $response = $this->get('/leaderboard');

        $response->assertStatus(200);

        // Check view data
        $leaderboard = $response->viewData('leaderboard');

        $this->assertCount(3, $leaderboard);

        // Thor Odinson should have 3 points
        $this->assertEquals(3, $leaderboard['Thor Odinson']['points']);
        $this->assertEquals(1, $leaderboard['Thor Odinson']['exact']);
        $this->assertEquals(0, $leaderboard['Thor Odinson']['outcome']);

        // Loki Laufeyson should have 1 point
        $this->assertEquals(1, $leaderboard['Loki Laufeyson']['points']);
        $this->assertEquals(0, $leaderboard['Loki Laufeyson']['exact']);
        $this->assertEquals(1, $leaderboard['Loki Laufeyson']['outcome']);

        // Bruce Banner should have 0 points
        $this->assertEquals(0, $leaderboard['Bruce Banner']['points']);
        $this->assertEquals(0, $leaderboard['Bruce Banner']['exact']);
        $this->assertEquals(0, $leaderboard['Bruce Banner']['outcome']);
    }
}
