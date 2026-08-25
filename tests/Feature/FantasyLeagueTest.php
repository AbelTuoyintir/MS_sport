<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use App\Models\FantasyTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FantasyLeagueTest extends TestCase
{
    use RefreshDatabase;

    public function test_fantasy_hub_is_accessible()
    {
        $response = $this->get('/fantasy');
        $response->assertStatus(200);
        $response->assertSee('Fantasy League Hub');
    }

    public function test_user_can_create_fantasy_squad_within_budget()
    {
        $team = Team::create([
            'team_name' => 'Arsenal FC',
            'primary_color' => '#ef0107',
            'secondary_color' => '#ffffff',
            'reference_code' => 'ARS12345',
            'team_size' => 18,
            'division' => 'Premier Division',
            'password' => 'secret123',
        ]);
        $players = [];
        for ($i = 1; $i <= 5; $i++) {
            $players[] = Player::create([
                'team_id' => $team->id,
                'name' => "Player $i",
                'position' => 'FWD',
                'rating' => 75,
                'goals' => 2,
                'assists' => 1,
            ]);
        }

        $playerIds = collect($players)->pluck('id')->toArray();

        $response = $this->post('/fantasy', [
            'name' => 'Dream Team XI',
            'player_ids' => $playerIds,
            'captain_id' => $playerIds[0],
        ]);

        $response->assertRedirect('/fantasy');
        $this->assertDatabaseHas('fantasy_teams', [
            'name' => 'Dream Team XI',
        ]);
    }

    public function test_fantasy_leaderboard_is_accessible()
    {
        FantasyTeam::create([
            'name' => 'Champions Team',
            'budget_remaining' => 20.0,
            'total_points' => 150,
        ]);

        $response = $this->get('/fantasy/leaderboard');
        $response->assertStatus(200);
        $response->assertSee('Champions Team');
        $response->assertSee('150');
    }
}
