<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerComparisonTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_player_comparison_hub()
    {
        $response = $this->get(route('players.compare'));

        $response->assertStatus(200);
        $response->assertSee('Comparison Hub');
        $response->assertSee('Select Players to Compare');
    }

    public function test_can_compare_two_players_and_view_metrics_and_estimated_market_valuations()
    {
        // Create teams
        $teamA = Team::create([
            'reference_code' => 'TMA-001',
            'team_name' => 'Accra United',
            'team_size' => '25',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => bcrypt('password'),
        ]);

        $teamB = Team::create([
            'reference_code' => 'TMB-001',
            'team_name' => 'Kumasi Stars',
            'team_size' => '25',
            'division' => 'premier',
            'primary_color' => '#00ff00',
            'secondary_color' => '#000000',
            'registration_status' => 'approved',
            'password' => bcrypt('password'),
        ]);

        // Create players
        $player1 = Player::create([
            'team_id' => $teamA->id,
            'name' => 'Abel Kojo',
            'position' => 'FWD',
            'goals' => 12,
            'assists' => 5,
            'rating' => 84,
            'nationality' => '🇬🇭',
            'appearances' => 15,
            'number' => 9,
            'age' => 21,
        ]);

        $player2 = Player::create([
            'team_id' => $teamB->id,
            'name' => 'Kwame Mensah',
            'position' => 'MID',
            'goals' => 3,
            'assists' => 11,
            'rating' => 82,
            'nationality' => '🇬🇭',
            'appearances' => 14,
            'number' => 8,
            'age' => 26,
        ]);

        // Compare them via GET query params
        $response = $this->get(route('players.compare', [
            'player1_id' => $player1->id,
            'player2_id' => $player2->id
        ]));

        $response->assertStatus(200);

        // Verify player names are rendered
        $response->assertSee('Abel Kojo');
        $response->assertSee('Kwame Mensah');

        // Verify team names are rendered
        $response->assertSee('Accra United');
        $response->assertSee('Kumasi Stars');

        // Verify overall ratings and goals/assists are displayed
        $response->assertSee('Overall Rating');
        $response->assertSee('Goals Scored');
        $response->assertSee('Assists Created');

        // Verify estimated market valuations exist
        $response->assertSee('Estimated Valuation');
        $response->assertSee('GH₵');
    }
}
