<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class FantasyLeagueTest extends TestCase
{
    use RefreshDatabase;

    private function createTeam(array $attributes = [])
    {
        return Team::create(array_merge([
            'team_name' => 'Apex Titans',
            'team_size' => '11',
            'division' => 'Premier',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'accent_color' => '#f0c040',
            'password' => bcrypt('password'),
            'reference_code' => 'REF-' . Str::random(8),
        ], $attributes));
    }

    public function test_fantasy_hub_is_accessible()
    {
        $team = $this->createTeam(['team_name' => 'Apex Titans']);
        Player::create([
            'team_id' => $team->id,
            'name' => 'Erling Haaland',
            'position' => 'FWD',
            'goals' => 15,
            'assists' => 4,
            'rating' => 90,
        ]);

        $response = $this->get('/fantasy');

        $response->assertStatus(200);
        $response->assertSee('Build Your');
        $response->assertSee('Erling Haaland');
    }

    public function test_fantasy_leaderboard_is_accessible()
    {
        $response = $this->get('/fantasy/leaderboard');

        $response->assertStatus(200);
        $response->assertSee('Global Fantasy Standings');
        $response->assertSee('Alex Ferguson');
    }

    public function test_can_save_fantasy_squad_within_budget()
    {
        $team = $this->createTeam(['team_name' => 'Apex Titans']);
        $player1 = Player::create([
            'team_id' => $team->id,
            'name' => 'Kevin De Bruyne',
            'position' => 'MID',
            'goals' => 5,
            'assists' => 10,
            'rating' => 88,
        ]);
        $player2 = Player::create([
            'team_id' => $team->id,
            'name' => 'Virgil van Dijk',
            'position' => 'DEF',
            'goals' => 2,
            'assists' => 1,
            'rating' => 87,
        ]);

        $response = $this->post('/fantasy/squad', [
            'squad_name' => 'Super League XI',
            'manager_name' => 'Tactical Genius',
            'player_ids' => [$player1->id, $player2->id],
            'captain_id' => $player1->id,
        ]);

        $response->assertRedirect('/fantasy');
        $response->assertSessionHas('fantasy_squad');

        $squad = session('fantasy_squad');
        $this->assertEquals('Super League XI', $squad['squad_name']);
        $this->assertEquals('Tactical Genius', $squad['manager_name']);
        $this->assertEquals($player1->id, $squad['captain_id']);
    }

    public function test_captain_must_be_in_squad()
    {
        $team = $this->createTeam(['team_name' => 'Apex Titans']);
        $player1 = Player::create([
            'team_id' => $team->id,
            'name' => 'Player One',
            'position' => 'MID',
        ]);
        $player2 = Player::create([
            'team_id' => $team->id,
            'name' => 'Player Two',
            'position' => 'FWD',
        ]);

        $response = $this->post('/fantasy/squad', [
            'squad_name' => 'Invalid Captain XI',
            'manager_name' => 'Tactical Genius',
            'player_ids' => [$player1->id],
            'captain_id' => $player2->id,
        ]);

        $response->assertSessionHasErrors(['captain']);
    }
}
