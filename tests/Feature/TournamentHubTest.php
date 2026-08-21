<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_tournaments_page_is_accessible_and_renders_bracket(): void
    {
        $response = $this->get('/tournaments');

        $response->assertStatus(200);
        $response->assertSee('The Apex Champions Cup');
        $response->assertSee('Fan Bracket Challenge');
        $response->assertSee('Quarter-Finals');
        $response->assertSee('Semi-Finals');
    }

    public function test_fan_can_submit_bracket_prediction(): void
    {
        $response = $this->post('/tournaments/predict', [
            'fan_name' => 'John Fan',
            'predicted_winner_id' => 'Man City',
            'predicted_final_score' => '2 - 1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('tournament_prediction');
        $response->assertSessionHas('success', 'Your Apex Champions Cup bracket prediction has been locked in!');
    }
}
