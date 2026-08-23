<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ClubRankingsTest extends TestCase
{
    use RefreshDatabase;

    private function createTeam(array $attributes = [])
    {
        return Team::create(array_merge([
            'team_name' => 'Rankings FC',
            'team_size' => '11',
            'division' => 'Premier',
            'primary_color' => '#1a3cff',
            'secondary_color' => '#ffffff',
            'accent_color' => '#f0c040',
            'password' => bcrypt('password'),
            'reference_code' => 'REF-' . Str::random(8),
        ], $attributes));
    }

    public function test_rankings_page_is_accessible_and_renders_cpi_leaderboard()
    {
        $team1 = $this->createTeam(['team_name' => 'Royal Champions']);
        $team2 = $this->createTeam(['team_name' => 'Titan Athletic']);

        Player::create([
            'team_id' => $team1->id,
            'name' => 'Star Striker',
            'position' => 'FW',
            'rating' => 88,
            'goals' => 12
        ]);

        Player::create([
            'team_id' => $team2->id,
            'name' => 'Top Defender',
            'position' => 'DF',
            'rating' => 82,
            'clean_sheets' => 5
        ]);

        $response = $this->get(route('rankings'));

        $response->assertStatus(200);
        $response->assertSee('Club Power Rankings');
        $response->assertSee('Royal Champions');
        $response->assertSee('Titan Athletic');
        $response->assertSee('Full League Power Index Leaderboard');
    }

    public function test_side_by_side_club_comparison()
    {
        $team1 = $this->createTeam(['team_name' => 'Alpha United']);
        $team2 = $this->createTeam(['team_name' => 'Beta City']);

        Game::create([
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'home_score' => 3,
            'away_score' => 1,
            'status' => 'finished',
            'matchweek' => 1,
            'kickoff' => now(),
        ]);

        $response = $this->get(route('rankings', [
            'team1_id' => $team1->id,
            'team2_id' => $team2->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Side-by-Side Club Power Battle');
        $response->assertSee('Alpha United');
        $response->assertSee('Beta City');
        $response->assertSee('Club Power Index');
    }
}
