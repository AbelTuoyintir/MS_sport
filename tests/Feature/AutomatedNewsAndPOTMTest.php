<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Game;
use App\Models\Player;
use App\Models\Article;
use App\Models\TransferOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AutomatedNewsAndPOTMTest extends TestCase
{
    use RefreshDatabase;

    public function test_automated_news_on_goal_fest()
    {
        $team1 = Team::create([
            'reference_code' => 'T1',
            'team_name' => 'Home Lions',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#000000',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $team2 = Team::create([
            'reference_code' => 'T2',
            'team_name' => 'Away Eagles',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#0000ff',
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
            'venue' => 'Stadium',
            'home_score' => 0,
            'away_score' => 0,
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Put score that equals 5 total goals and set status to finished
        $response = $this->actingAs($admin)->put(route('admin.games.update', $game->id), [
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'kickoff' => now()->format('Y-m-d H:i'),
            'matchweek' => 1,
            'status' => 'finished',
            'home_score' => 3,
            'away_score' => 2,
            'venue' => 'Stadium',
            'live_minute' => '90'
        ]);

        $response->assertRedirect();

        // Assert that a news article has been generated
        $article = Article::where('title', 'like', '%GOAL FEST%')->first();
        $this->assertNotNull($article);
        $this->assertTrue(str_contains(strtolower($article->content), 'spectators were treated to an absolute classic'));
    }

    public function test_potm_increment_on_game_finished()
    {
        $team1 = Team::create([
            'reference_code' => 'T1',
            'team_name' => 'Home Lions',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#000000',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $team2 = Team::create([
            'reference_code' => 'T2',
            'team_name' => 'Away Eagles',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#0000ff',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $player = Player::create([
            'team_id' => $team1->id,
            'name' => 'John Doe',
            'position' => 'FWD',
            'motm_awards' => 0
        ]);

        $game = Game::create([
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'kickoff' => now(),
            'matchweek' => 1,
            'status' => 'upcoming',
            'venue' => 'Stadium',
            'home_score' => 0,
            'away_score' => 0,
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $response = $this->actingAs($admin)->put(route('admin.games.update', $game->id), [
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'kickoff' => now()->format('Y-m-d H:i'),
            'matchweek' => 1,
            'status' => 'finished',
            'home_score' => 1,
            'away_score' => 0,
            'venue' => 'Stadium',
            'live_minute' => '90',
            'potm_id' => $player->id
        ]);

        $response->assertRedirect();

        $player->refresh();
        $this->assertEquals(1, $player->motm_awards);
    }
}
