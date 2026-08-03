<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_page_is_accessible()
    {
        Team::create([
            'reference_code' => 'TEST-123',
            'team_name' => 'Test Team',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('League Statistics');
    }

    public function test_stats_page_shows_custom_metrics()
    {
        $team = Team::create([
            'reference_code' => 'TEST-123',
            'team_name' => 'Test Team',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#123456',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        // Create player with goals, assists, rating, clean sheets, motm awards
        Player::create([
            'team_id' => $team->id,
            'name' => 'Super Striker',
            'position' => 'FWD',
            'goals' => 10,
            'assists' => 5,
            'rating' => 92,
            'appearances' => 12,
            'clean_sheets' => 4,
            'motm_awards' => 3,
            'nationality' => 'GH'
        ]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('Super Striker');
        $response->assertSee('10'); // Goals
        $response->assertSee('5');  // Assists
        $response->assertSee('92'); // Rating
        $response->assertSee('4');  // Clean Sheets
        $response->assertSee('3');  // MOTM
    }

    public function test_stats_page_shows_prediction_leaderboard()
    {
        $teamHome = Team::create([
            'reference_code' => 'HOME-123',
            'team_name' => 'Home Team',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#111111',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $teamAway = Team::create([
            'reference_code' => 'AWAY-123',
            'team_name' => 'Away Team',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#222222',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        // Create finished game
        $game = Game::create([
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id,
            'home_score' => 2,
            'away_score' => 1,
            'kickoff' => now(),
            'matchweek' => 1,
            'status' => 'finished',
            'venue' => 'Main Stadium'
        ]);

        // Create predictions
        // Exact match -> 3 pts
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'John Doe Exact',
            'home_score_prediction' => 2,
            'away_score_prediction' => 1
        ]);

        // Correct outcome -> 1 pt
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'Jane Smith Outcome',
            'home_score_prediction' => 3,
            'away_score_prediction' => 0
        ]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('John Doe Exact');
        $response->assertSee('Jane Smith Outcome');
        $response->assertSee('3 pts');
        $response->assertSee('1 pts');
    }
}
