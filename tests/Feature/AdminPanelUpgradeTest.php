<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Game;
use App\Models\Article;

class AdminPanelUpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_dashboard_is_accessible_and_renders_stats()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Administrator Control Dashboard');
        $response->assertSee('Season 2024/25 Overview');
    }

    public function test_admin_can_view_teams_list()
    {
        $team = Team::create([
            'reference_code' => 'REF-' . rand(1000, 9999),
            'team_name' => 'Cape Coast Gladiators',
            'team_size' => '22 Players',
            'division' => 'premier',
            'home_stadium' => 'Cape Coast Stadium',
            'primary_color' => '#f0c040',
            'secondary_color' => '#000000',
            'password' => bcrypt('password'),
            'registration_status' => 'approved',
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.teams.index'));

        $response->assertStatus(200);
        $response->assertSee('Cape Coast Gladiators');
        $response->assertSee('Cape Coast Stadium');
    }

    public function test_admin_can_view_games_list()
    {
        $teamA = Team::create([
            'reference_code' => 'REF-A',
            'team_name' => 'Team Alpha',
            'team_size' => '22 Players',
            'division' => 'premier',
            'primary_color' => '#111111',
            'secondary_color' => '#ffffff',
            'password' => bcrypt('password'),
        ]);
        $teamB = Team::create([
            'reference_code' => 'REF-B',
            'team_name' => 'Team Beta',
            'team_size' => '22 Players',
            'division' => 'premier',
            'primary_color' => '#222222',
            'secondary_color' => '#ffffff',
            'password' => bcrypt('password'),
        ]);

        $game = Game::create([
            'home_team_id' => $teamA->id,
            'away_team_id' => $teamB->id,
            'matchweek' => 12,
            'status' => 'upcoming',
            'kickoff' => now()->addDays(2),
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.games.index'));

        $response->assertStatus(200);
        $response->assertSee('Team Alpha');
        $response->assertSee('Team Beta');
        $response->assertSee('MW 12');
    }

    public function test_admin_can_view_articles_list()
    {
        $article = Article::create([
            'title' => 'Title Race Heats Up in Matchweek 32',
            'content' => 'Full breakdown of the title race drama and key tactical moments.',
            'tag' => 'Title Race',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.articles.index'));

        $response->assertStatus(200);
        $response->assertSee('Title Race Heats Up in Matchweek 32');
        $response->assertSee('Published');
    }
}
