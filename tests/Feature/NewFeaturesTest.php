<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Team;
use App\Models\Player;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_leaderboard_page_is_accessible(): void
    {
        $response = $this->get('/leaderboard');

        $response->assertStatus(200);
        $response->assertSee('Prediction Leaderboard');
    }

    public function test_compare_page_is_accessible(): void
    {
        $response = $this->get('/compare');

        $response->assertStatus(200);
        $response->assertSee('Player Comparison');
    }

    public function test_player_comparison_with_players(): void
    {
        $team = Team::create([
            'reference_code' => 'TEST-001',
            'team_name' => 'Accra Lions',
            'team_size' => '25',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password',
            'home_stadium' => 'Lions Stadium',
            'city' => 'Accra',
            'founded_year' => 2015,
            'formation' => '4-3-3',
        ]);

        $player1 = Player::create([
            'team_id' => $team->id,
            'name' => 'Michael Essien',
            'position' => 'MID',
            'goals' => 5,
            'assists' => 4,
            'rating' => 88,
            'appearances' => 10,
        ]);

        $player2 = Player::create([
            'team_id' => $team->id,
            'name' => 'Asamoah Gyan',
            'position' => 'FWD',
            'goals' => 12,
            'assists' => 2,
            'rating' => 85,
            'appearances' => 9,
        ]);

        $response = $this->get('/compare?player1=' . $player1->id . '&player2=' . $player2->id);

        $response->assertStatus(200);
        $response->assertSee('Michael Essien');
        $response->assertSee('Asamoah Gyan');
        $response->assertSee('Performance Summary');
    }
}
