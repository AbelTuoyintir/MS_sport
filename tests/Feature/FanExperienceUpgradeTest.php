<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use App\Models\PlayerRating;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FanExperienceUpgradeTest extends TestCase
{
    use RefreshDatabase;

    private function createTeam($name, $code)
    {
        return Team::create([
            'reference_code' => $code,
            'team_name' => $name,
            'team_size' => '25',
            'division' => 'premier',
            'primary_color' => '#ffffff',
            'secondary_color' => '#000000',
            'password' => bcrypt('password'),
            'registration_status' => 'approved',
        ]);
    }

    public function test_fans_can_submit_player_ratings_for_finished_games()
    {
        $homeTeam = $this->createTeam('Home Team FC', 'TEST-001');
        $awayTeam = $this->createTeam('Away Team FC', 'TEST-002');

        $player = Player::create([
            'team_id' => $homeTeam->id,
            'name' => 'John Doe',
            'position' => 'FWD',
            'rating' => 80,
        ]);

        $game = Game::create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'home_score' => 2,
            'away_score' => 1,
            'status' => 'finished',
            'kickoff' => now(),
            'matchweek' => 1,
            'venue' => 'Test Stadium',
        ]);

        $response = $this->post(route('player-ratings.store'), [
            'player_id' => $player->id,
            'game_id' => $game->id,
            'rating' => 8,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('player_ratings', [
            'player_id' => $player->id,
            'game_id' => $game->id,
            'rating' => 8,
        ]);

        // Check average rating is calculated correctly
        $this->assertEquals(8, $player->averageRatingForMatch($game->id));
    }

    public function test_player_ratings_validation_bounds()
    {
        $homeTeam = $this->createTeam('Home Team FC', 'TEST-001');
        $awayTeam = $this->createTeam('Away Team FC', 'TEST-002');

        $player = Player::create([
            'team_id' => $homeTeam->id,
            'name' => 'John Doe',
            'position' => 'FWD',
            'rating' => 80,
        ]);

        $game = Game::create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'home_score' => 2,
            'away_score' => 1,
            'status' => 'finished',
            'kickoff' => now(),
            'matchweek' => 1,
            'venue' => 'Test Stadium',
        ]);

        // Invalid too high rating (11)
        $response = $this->post(route('player-ratings.store'), [
            'player_id' => $player->id,
            'game_id' => $game->id,
            'rating' => 11,
        ]);

        $response->assertSessionHasErrors('rating');

        // Invalid too low rating (0)
        $response2 = $this->post(route('player-ratings.store'), [
            'player_id' => $player->id,
            'game_id' => $game->id,
            'rating' => 0,
        ]);

        $response2->assertSessionHasErrors('rating');
    }

    public function test_cannot_rate_players_in_upcoming_games()
    {
        $homeTeam = $this->createTeam('Home Team FC', 'TEST-001');
        $awayTeam = $this->createTeam('Away Team FC', 'TEST-002');

        $player = Player::create([
            'team_id' => $homeTeam->id,
            'name' => 'John Doe',
            'position' => 'FWD',
            'rating' => 80,
        ]);

        $game = Game::create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'status' => 'upcoming',
            'kickoff' => now()->addDays(2),
            'matchweek' => 1,
            'venue' => 'Test Stadium',
        ]);

        $response = $this->post(route('player-ratings.store'), [
            'player_id' => $player->id,
            'game_id' => $game->id,
            'rating' => 7,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('player_ratings', [
            'player_id' => $player->id,
            'game_id' => $game->id,
        ]);
    }

    public function test_homepage_loads_with_transfer_rumours()
    {
        $homeTeam = $this->createTeam('Home Team FC', 'TEST-001');

        $player = Player::create([
            'team_id' => $homeTeam->id,
            'name' => 'John Doe',
            'position' => 'FWD',
            'rating' => 95,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Transfer Rumour Mill');
    }
}
