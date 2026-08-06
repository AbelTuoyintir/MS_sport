<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TacticsAiScoutingTest extends TestCase
{
    use RefreshDatabase;

    protected $myTeam;
    protected $opponentTeam;
    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup manager's team
        $this->myTeam = Team::create([
            'reference_code' => 'TEAM-MINE',
            'team_name' => 'Accra United',
            'team_size' => '25',
            'division' => 'premier',
            'primary_color' => '#123456',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password',
            'home_stadium' => 'Accra Arena'
        ]);

        $this->manager = User::factory()->create([
            'role' => 'manager',
            'team_id' => $this->myTeam->id
        ]);

        // Add some players to my team
        for ($i = 0; $i < 11; $i++) {
            Player::create([
                'team_id' => $this->myTeam->id,
                'name' => "My Player $i",
                'position' => 'MID',
                'goals' => 0,
                'rating' => 80,
                'nationality' => '🇬🇭'
            ]);
        }

        // 2. Setup opponent team
        $this->opponentTeam = Team::create([
            'reference_code' => 'TEAM-OPP',
            'team_name' => 'Kumasi Stars',
            'team_size' => '25',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#000000',
            'registration_status' => 'approved',
            'password' => 'password',
            'home_stadium' => 'Kumasi Stadium'
        ]);

        // Add some players to opponent team, including a star player
        for ($i = 0; $i < 10; $i++) {
            Player::create([
                'team_id' => $this->opponentTeam->id,
                'name' => "Opponent Player $i",
                'position' => 'DEF',
                'goals' => 0,
                'rating' => 70,
                'nationality' => '🇬🇭'
            ]);
        }

        // Star player for opponent
        Player::create([
            'team_id' => $this->opponentTeam->id,
            'name' => "Star Forward",
            'position' => 'FWD',
            'goals' => 5,
            'rating' => 92,
            'nationality' => '🇬🇭'
        ]);
    }

    public function test_guests_cannot_access_ai_scouting()
    {
        $response = $this->get('/manager/scouting/ai');
        $response->assertRedirect('/login');
    }

    public function test_manager_can_view_ai_scouting_page_with_opponents()
    {
        $response = $this->actingAs($this->manager)->get('/manager/scouting/ai');

        $response->assertStatus(200);
        $response->assertSee('Tactical Scout Intelligence');
        $response->assertSee('Kumasi Stars');
        // It shouldn't list manager's own team as an opponent selection
        $response->assertDontSee('<option value="' . $this->myTeam->id . '">');
    }

    public function test_manager_can_generate_tactical_scouting_report()
    {
        $response = $this->actingAs($this->manager)->post('/manager/scouting/ai/generate', [
            'opponent_team_id' => $this->opponentTeam->id,
            'opponent_formation' => '4-3-3',
            'tactic_style' => 'Tiki-Taka',
            'intensity' => 'Balanced'
        ]);

        $response->assertStatus(200);
        $response->assertSee('Executive Scouting Summary');
        $response->assertSee('Star Opponent Threat');
        $response->assertSee('Star Forward'); // Highlighting the star player
        $response->assertSee('Counter Formation');
        $response->assertSee('4-2-3-1'); // Recommended counter formation against 4-3-3
        $response->assertSee('Win Confidence Engine Projections');
    }

    public function test_scouting_report_validation_errors()
    {
        $response = $this->actingAs($this->manager)->post('/manager/scouting/ai/generate', [
            'opponent_team_id' => 9999, // Invalid ID
            'opponent_formation' => '',
            'tactic_style' => '',
            'intensity' => ''
        ]);

        $response->assertSessionHasErrors([
            'opponent_team_id',
            'opponent_formation',
            'tactic_style',
            'intensity'
        ]);
    }
}
