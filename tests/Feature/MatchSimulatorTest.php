<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchSimulatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_match_page_contains_live_simulator_elements()
    {
        $team1 = Team::create([
            'reference_code' => 'T1',
            'team_name' => 'Home Lions',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#00e5ff',
            'secondary_color' => '#0d1117',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $team2 = Team::create([
            'reference_code' => 'T2',
            'team_name' => 'Away Eagles',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#f0c040',
            'secondary_color' => '#0d1117',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $game = Game::create([
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'kickoff' => now(),
            'matchweek' => 1,
            'status' => 'upcoming',
            'venue' => 'Stadium'
        ]);

        $response = $this->get("/matches/{$game->id}");

        $response->assertStatus(200);
        $response->assertSee('apex-simulator-container');
        $response->assertSee('start-sim-btn');
        $response->assertSee('cheer-home-btn');
        $response->assertSee('cheer-away-btn');
        $response->assertSee('sim-commentary-box');
    }
}
