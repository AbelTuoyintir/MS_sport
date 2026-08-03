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

    public function test_stats_page_displays_advanced_stats_and_leaders()
    {
        $team = Team::create([
            'reference_code' => 'T1',
            'team_name' => 'Team One',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#111111',
            'secondary_color' => '#222222',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        // Create player with goals
        $player1 = Player::create([
            'team_id' => $team->id,
            'name' => 'Goal Machine',
            'position' => 'FW',
            'age' => 25,
            'goals' => 15,
            'assists' => 2,
            'rating' => 8.5,
            'clean_sheets' => 0,
            'motm_awards' => 3,
            'appearances' => 10,
        ]);

        // Create goalkeeper with clean sheets
        $player2 = Player::create([
            'team_id' => $team->id,
            'name' => 'Wall Keeper',
            'position' => 'GK',
            'age' => 28,
            'goals' => 0,
            'assists' => 1,
            'rating' => 7.9,
            'clean_sheets' => 6,
            'motm_awards' => 1,
            'appearances' => 10,
        ]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('League Statistics');

        // Assert player names and their stats are visible
        $response->assertSee('Goal Machine');
        $response->assertSee('Wall Keeper');
        $response->assertSee('15'); // Player 1 goals
        $response->assertSee('6');  // Player 2 clean sheets
        $response->assertSee('8.5'); // Player 1 rating
        $response->assertSee('3');  // Player 1 MOTM awards
    }

    public function test_prediction_leaderboard_point_calculations()
    {
        $team1 = Team::create([
            'reference_code' => 'T1',
            'team_name' => 'Team One',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#111111',
            'secondary_color' => '#222222',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $team2 = Team::create([
            'reference_code' => 'T2',
            'team_name' => 'Team Two',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#333333',
            'secondary_color' => '#444444',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        // Create a finished game with score 2-1 (Home win)
        $game = Game::create([
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'home_score' => 2,
            'away_score' => 1,
            'kickoff' => now()->subDay(),
            'matchweek' => 1,
            'status' => 'finished',
            'venue' => 'Stadium'
        ]);

        // User A predicts exact score: 2-1 (Should get 3 points)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'User Exact',
            'home_score_prediction' => 2,
            'away_score_prediction' => 1
        ]);

        // User B predicts correct outcome but wrong score: 3-1 (Should get 1 point)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'User Outcome',
            'home_score_prediction' => 3,
            'away_score_prediction' => 1
        ]);

        // User C predicts incorrect outcome: 1-1 (Should get 0 points)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'User Incorrect',
            'home_score_prediction' => 1,
            'away_score_prediction' => 1
        ]);

        $response = $this->get('/stats');

        $response->assertStatus(200);

        // Assert leaderboard ranks and scores
        $response->assertSee('User Exact');
        $response->assertSee('User Outcome');
        $response->assertSee('User Incorrect');

        // Check if correct point values are displayed
        // User Exact should have 3 points
        $response->assertSee('3');
        // User Outcome should have 1 point
        $response->assertSee('1');
    }
}
