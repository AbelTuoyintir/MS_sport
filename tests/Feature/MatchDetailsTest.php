<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_match_details_shows_h2h()
    {
        $team1 = Team::create([
            'reference_code' => 'T1',
            'team_name' => 'Team 1',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $team2 = Team::create([
            'reference_code' => 'T2',
            'team_name' => 'Team 2',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
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

        // Create a past H2H game
        Game::create([
            'home_team_id' => $team2->id,
            'away_team_id' => $team1->id,
            'home_score' => 2,
            'away_score' => 1,
            'kickoff' => now()->subMonths(6),
            'matchweek' => 1,
            'status' => 'finished',
            'venue' => 'Stadium'
        ]);

        $response = $this->get("/matches/{$game->id}");

        $response->assertStatus(200);
        $response->assertSee('Head to Head');
        $response->assertSee('Team 2');
        $response->assertSee('2 - 1');
    }
}
