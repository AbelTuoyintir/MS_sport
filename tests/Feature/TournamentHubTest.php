<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_tournament_hub_is_accessible_and_renders_bracket()
    {
        $response = $this->get('/tournaments');

        $response->assertStatus(200);
        $response->assertSee('The Apex Champions Cup');
        $response->assertSee('Knockout Bracket Progression');
        $response->assertSee('Quarterfinals');
        $response->assertSee('Semifinals');
        $response->assertSee('Grand Final');
    }

    public function test_can_submit_bracket_prediction()
    {
        $response = $this->post('/tournaments/predict', [
            'user_name' => 'TacticalFanatic',
            'predicted_champion' => 'Apex Champions FC',
            'final_score_home' => 3,
            'final_score_away' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('predictions', [
            'user_name' => 'TacticalFanatic [Cup Predictor]',
            'home_score_prediction' => 3,
            'away_score_prediction' => 1,
        ]);
    }
}
