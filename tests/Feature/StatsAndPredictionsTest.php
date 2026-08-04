<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatsAndPredictionsTest extends TestCase
{
    use RefreshDatabase;

    protected $homeTeam;
    protected $awayTeam;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard teams
        $this->homeTeam = Team::create([
            'reference_code' => 'TEAM-H1',
            'team_name' => 'Home Team',
            'team_size' => '15',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $this->awayTeam = Team::create([
            'reference_code' => 'TEAM-A1',
            'team_name' => 'Away Team',
            'team_size' => '15',
            'division' => 'premier',
            'primary_color' => '#0000ff',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);
    }

    public function test_prediction_points_calculation_and_leaderboard()
    {
        // 1. Create a finished game (Score: Home 2 - 1 Away)
        $game = Game::create([
            'home_team_id' => $this->homeTeam->id,
            'away_team_id' => $this->awayTeam->id,
            'home_score' => 2,
            'away_score' => 1,
            'kickoff' => now(),
            'matchweek' => 1,
            'status' => 'finished',
            'venue' => 'Main Arena',
        ]);

        // 2. Create exact score prediction (should get 3 points)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'ExactPredictor',
            'home_score_prediction' => 2,
            'away_score_prediction' => 1,
        ]);

        // 3. Create correct outcome prediction (should get 1 point)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'OutcomePredictor',
            'home_score_prediction' => 3,
            'away_score_prediction' => 0,
        ]);

        // 4. Create incorrect prediction (should get 0 points)
        Prediction::create([
            'game_id' => $game->id,
            'user_name' => 'WrongPredictor',
            'home_score_prediction' => 1,
            'away_score_prediction' => 3,
        ]);

        // 5. Query stats page and inspect leaderboard collections
        $response = $this->get('/stats');
        $response->assertStatus(200);

        $leaderboard = $response->viewData('predictionsLeaderboard');

        $this->assertNotNull($leaderboard);

        // ExactPredictor should have 3 points
        $exact = $leaderboard->firstWhere('user_name', 'ExactPredictor');
        $this->assertNotNull($exact);
        $this->assertEquals(3, $exact['points']);
        $this->assertEquals(1, $exact['predictions_count']);
        $this->assertEquals(1, $exact['exact_scores']);
        $this->assertEquals(0, $exact['correct_outcomes']);

        // OutcomePredictor should have 1 point
        $outcome = $leaderboard->firstWhere('user_name', 'OutcomePredictor');
        $this->assertNotNull($outcome);
        $this->assertEquals(1, $outcome['points']);
        $this->assertEquals(1, $outcome['predictions_count']);
        $this->assertEquals(0, $outcome['exact_scores']);
        $this->assertEquals(1, $outcome['correct_outcomes']);

        // WrongPredictor should have 0 points
        $wrong = $leaderboard->firstWhere('user_name', 'WrongPredictor');
        $this->assertNotNull($wrong);
        $this->assertEquals(0, $wrong['points']);
    }

    public function test_advanced_statistics_leaders_display()
    {
        // Create players with advanced stats
        $player1 = Player::create([
            'team_id' => $this->homeTeam->id,
            'name' => 'Elite Scorer',
            'position' => 'FWD',
            'goals' => 15,
            'assists' => 5,
            'rating' => 95, // 9.5 rating
            'appearances' => 10,
            'number' => 9,
            'clean_sheets' => 0,
            'motm_awards' => 4,
        ]);

        $player2 = Player::create([
            'team_id' => $this->awayTeam->id,
            'name' => 'Super Goalkeeper',
            'position' => 'GK',
            'goals' => 0,
            'assists' => 2,
            'rating' => 88, // 8.8 rating
            'appearances' => 12,
            'number' => 1,
            'clean_sheets' => 8,
            'motm_awards' => 2,
        ]);

        $response = $this->get('/stats');
        $response->assertStatus(200);

        // Verify top scorers list contains Elite Scorer
        $topScorers = $response->viewData('topScorers');
        $this->assertTrue($topScorers->contains('id', $player1->id));

        // Verify highest rated list contains Elite Scorer and Super Goalkeeper
        $topRated = $response->viewData('topRated');
        $this->assertTrue($topRated->contains('id', $player1->id));
        $this->assertTrue($topRated->contains('id', $player2->id));

        // Verify clean sheets list contains Super Goalkeeper
        $mostCleanSheets = $response->viewData('mostCleanSheets');
        $this->assertTrue($mostCleanSheets->contains('id', $player2->id));

        // Verify MOTM awards list contains both
        $mostMotmAwards = $response->viewData('mostMotmAwards');
        $this->assertTrue($mostMotmAwards->contains('id', $player1->id));
        $this->assertTrue($mostMotmAwards->contains('id', $player2->id));

        // Assert rendered HTML contains critical sections
        $response->assertSee('League Hub');
        $response->assertSee('Player Statistics');
        $response->assertSee('Prediction Leaderboard');
        $response->assertSee('Elite Scorer');
        $response->assertSee('Super Goalkeeper');
    }
}
