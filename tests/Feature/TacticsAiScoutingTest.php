<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TacticsAiScoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_ai_scouting()
    {
        $response = $this->get(route('manager.scouting.ai'));
        $response->assertRedirect('/login');
    }

    public function test_manager_can_access_ai_scouting_and_view_opponent_analysis()
    {
        // Create user team
        $userTeam = Team::create([
            'team_name' => 'User FC',
            'reference_code' => 'USR123',
            'team_size' => 11,
            'division' => 'Division 1',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'accent_color' => '#ff0000',
            'password' => 'password',
            'formation' => '4-3-3'
        ]);

        $manager = User::factory()->create([
            'role' => 'manager',
            'team_id' => $userTeam->id,
        ]);

        // Create opponent team and star player
        $opponentTeam = Team::create([
            'team_name' => 'Rival City',
            'reference_code' => 'RIV456',
            'team_size' => 11,
            'division' => 'Division 1',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'accent_color' => '#ff0000',
            'password' => 'password',
            'formation' => '4-4-2'
        ]);

        Player::create([
            'team_id' => $opponentTeam->id,
            'name' => 'Star Striker',
            'position' => 'FW',
            'rating' => 88,
            'goals' => 12,
            'assists' => 5,
        ]);

        $response = $this->actingAs($manager)->get(route('manager.scouting.ai', ['opponent_id' => $opponentTeam->id]));

        $response->assertStatus(200);
        $response->assertSee('Rival City');
        $response->assertSee('Star Striker');
        $response->assertSee('3-5-2'); // Counter formation for 4-4-2
        $response->assertSee('AI Counter-Formation Recommendation');
    }
}
