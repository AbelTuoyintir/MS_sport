<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_tournament_hub_is_accessible_and_renders_bracket()
    {
        $response = $this->get(route('tournaments.index'));

        $response->assertStatus(200);
        $response->assertSee('Apex Champions Cup');
        $response->assertSee('Quarter-Finals');
        $response->assertSee('Semi-Finals');
        $response->assertSee('The Grand Final');
        $response->assertSee('Fan Bracket Prediction Challenge');
    }

    public function test_fan_can_submit_bracket_prediction()
    {
        $response = $this->post(route('tournaments.predict'), [
            'user_name' => 'John Fan',
            'predicted_champion' => 'Man City',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
