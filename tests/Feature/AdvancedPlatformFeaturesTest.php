<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\Player;
use App\Models\Injury;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdvancedPlatformFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private function createTeam(array $attributes = [])
    {
        return Team::create(array_merge([
            'team_name' => 'Test FC',
            'team_size' => '11',
            'division' => 'Premier',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'accent_color' => '#f0c040',
            'password' => bcrypt('password'),
            'reference_code' => 'REF-' . Str::random(8),
        ], $attributes));
    }

    public function test_predictor_page_is_accessible_and_renders_xG_metrics()
    {
        $homeTeam = $this->createTeam(['team_name' => 'Apex City']);
        $awayTeam = $this->createTeam(['team_name' => 'Northern Rovers']);

        Player::create([
            'team_id' => $homeTeam->id,
            'name' => 'Home Forward',
            'position' => 'FW',
            'rating' => 88
        ]);

        Player::create([
            'team_id' => $awayTeam->id,
            'name' => 'Away Defender',
            'position' => 'DF',
            'rating' => 82
        ]);

        $user = User::factory()->create(['role' => 'manager', 'team_id' => $homeTeam->id]);

        $response = $this->actingAs($user)->get(route('predictor', [
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('AI Match Outcome');
        $response->assertSee('Apex City');
        $response->assertSee('Northern Rovers');
        $response->assertSee('Expected Goals (xG)');
    }

    public function test_lineup_builder_page_is_accessible_and_calculates_squad_metrics()
    {
        $team = $this->createTeam(['team_name' => 'Tactical FC']);
        $user = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);

        Player::create([
            'team_id' => $team->id,
            'name' => 'Tactical Player 1',
            'position' => 'FW',
            'rating' => 85
        ]);

        Player::create([
            'team_id' => $team->id,
            'name' => 'Tactical Player 2',
            'position' => 'MF',
            'rating' => 80
        ]);

        $response = $this->actingAs($user)->get(route('lineup-builder', ['team_id' => $team->id, 'formation' => '4-3-3']));

        $response->assertStatus(200);
        $response->assertSee('Custom Tactical Lineup Builder');
        $response->assertSee('Tactical FC');
        $response->assertSee('4-3-3');
    }

    public function test_injuries_and_discipline_hub_renders_fair_play_and_medical_records()
    {
        $team = $this->createTeam(['team_name' => 'Fair Play FC']);
        $user = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);

        $player = Player::create([
            'team_id' => $team->id,
            'name' => 'John Medical',
            'position' => 'MF',
            'yellow_cards' => 3,
            'red_cards' => 1
        ]);

        Injury::create([
            'player_id' => $player->id,
            'type' => 'Hamstring Strain',
            'started_at' => now(),
            'expected_return' => now()->addDays(14)->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('injuries-discipline'));

        $response->assertStatus(200);
        $response->assertSee('Injury');
        $response->assertSee('Discipline Hub');
        $response->assertSee('John Medical');
        $response->assertSee('Hamstring Strain');
        $response->assertSee('Fair Play Standings');
    }
}
