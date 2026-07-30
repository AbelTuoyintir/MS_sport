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

    public function test_non_manager_cannot_access_simulation_page()
    {
        $user = User::factory()->create([
            'role' => 'fan'
        ]);

        $response = $this->actingAs($user)->get('/manager/tactics/simulate');

        $response->assertRedirect();
    }

    public function test_manager_can_access_simulation_page()
    {
        $team = Team::create([
            'reference_code' => 'TEST-123',
            'team_name' => 'Test Team',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $user = User::factory()->create([
            'role' => 'manager',
            'team_id' => $team->id
        ]);

        $response = $this->actingAs($user)->get('/manager/tactics/simulate');

        $response->assertStatus(200);
        $response->assertSee('Tactical Match Simulator');
        $response->assertSee('Simulator Standby');
    }

    public function test_manager_can_run_simulation()
    {
        $team = Team::create([
            'reference_code' => 'TEST-123',
            'team_name' => 'My Dream Team',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $opponent = Team::create([
            'reference_code' => 'TEST-456',
            'team_name' => 'Opponent FC',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $user = User::factory()->create([
            'role' => 'manager',
            'team_id' => $team->id
        ]);

        // Add some players to My Dream Team
        for ($i = 0; $i < 5; $i++) {
            Player::create([
                'team_id' => $team->id,
                'name' => "My Player $i",
                'position' => 'MID',
                'goals' => 0,
                'rating' => 85,
                'nationality' => 'GH'
            ]);
        }

        // Add some players to Opponent FC
        for ($i = 0; $i < 5; $i++) {
            Player::create([
                'team_id' => $opponent->id,
                'name' => "Opponent Player $i",
                'position' => 'MID',
                'goals' => 0,
                'rating' => 75,
                'nationality' => 'NG'
            ]);
        }

        $response = $this->actingAs($user)->post('/manager/tactics/simulate', [
            'opponent_team_id' => $opponent->id,
            'strategy' => 'attacking'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Live-Text Commentary Play-by-Play');
        $response->assertSee('My Dream Team');
        $response->assertSee('Opponent FC');
    }
}
