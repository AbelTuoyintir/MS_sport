<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class TacticsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_access_tactics_page()
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

        $response = $this->actingAs($user)->get('/manager/tactics');

        $response->assertStatus(200);
        $response->assertSee('Tactics');
    }

    public function test_manager_can_save_tactics()
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

        $playerIds = [];
        for ($i = 0; $i < 11; $i++) {
            $player = Player::create([
                'team_id' => $team->id,
                'name' => "Player $i",
                'position' => 'MID',
                'goals' => 0,
                'rating' => 80,
                'nationality' => 'GH'
            ]);
            $playerIds[] = $player->id;
        }

        $response = $this->actingAs($user)->post('/manager/tactics', [
            'formation' => '4-3-3',
            'starting_xi' => $playerIds
        ]);

        $response->assertRedirect();
        $this->assertEquals('4-3-3', $team->refresh()->formation);
        $this->assertEquals($playerIds, $team->starting_xi);
    }
}
