<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TacticsSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_simulate_friendly_match_with_opponent()
    {
        // 1. Setup home team (manager's team)
        $homeTeam = Team::create([
            'reference_code' => 'TEAM-HOME',
            'team_name' => 'Home Wanderers',
            'team_size' => '15',
            'division' => 'premier',
            'primary_color' => '#111111',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password',
            'home_stadium' => 'Home Stadium'
        ]);

        $manager = User::factory()->create([
            'role' => 'manager',
            'team_id' => $homeTeam->id
        ]);

        // Create starting XI for home team
        $homePlayerIds = [];
        for ($i = 0; $i < 11; $i++) {
            $player = Player::create([
                'team_id' => $homeTeam->id,
                'name' => "Home Player $i",
                'position' => 'MID',
                'goals' => 0,
                'rating' => 85,
                'nationality' => '🇬🇭'
            ]);
            $homePlayerIds[] = $player->id;
        }
        $homeTeam->update(['starting_xi' => $homePlayerIds]);

        // 2. Setup opponent team
        $awayTeam = Team::create([
            'reference_code' => 'TEAM-AWAY',
            'team_name' => 'Away Invaders',
            'team_size' => '15',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password',
            'home_stadium' => 'Away Stadium'
        ]);

        // Create starting XI for away team
        $awayPlayerIds = [];
        for ($i = 0; $i < 11; $i++) {
            $player = Player::create([
                'team_id' => $awayTeam->id,
                'name' => "Away Player $i",
                'position' => 'MID',
                'goals' => 0,
                'rating' => 80,
                'nationality' => '🇬🇭'
            ]);
            $awayPlayerIds[] = $player->id;
        }
        $awayTeam->update(['starting_xi' => $awayPlayerIds]);

        // 3. Act: Post to simulate endpoint
        $response = $this->actingAs($manager)->postJson('/manager/tactics/simulate', [
            'opponent_team_id' => $awayTeam->id
        ]);

        // 4. Assert response structure and status
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'home_team' => ['name', 'primary_color', 'badge'],
            'away_team' => ['name', 'primary_color', 'badge'],
            'home_score',
            'away_score',
            'events' => [
                '*' => ['minute', 'type', 'team_id', 'description']
            ],
            'stats' => [
                'possession',
                'shots',
                'shots_on_target',
                'fouls',
                'corners'
            ]
        ]);

        // Confirm score matches simulation
        $data = $response->json();
        $this->assertEquals('Home Wanderers', $data['home_team']['name']);
        $this->assertEquals('Away Invaders', $data['away_team']['name']);
        $this->assertIsInt($data['home_score']);
        $this->assertIsInt($data['away_score']);
        $this->assertNotEmpty($data['events']);
    }

    public function test_guest_cannot_simulate_tactics()
    {
        $awayTeam = Team::create([
            'reference_code' => 'TEAM-AWAY',
            'team_name' => 'Away Invaders',
            'team_size' => '15',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $response = $this->postJson('/manager/tactics/simulate', [
            'opponent_team_id' => $awayTeam->id
        ]);

        // Guests should be redirected to login (or 401 Unauthorized if JSON request)
        $response->assertStatus(401);
    }
}
