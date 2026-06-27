<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_registration_form()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_can_store_team_info_in_session()
    {
        $response = $this->postJson('/api/team-info', [
            'team_name' => 'Test Team',
            'team_size' => '11',
            'team_division' => 'premier',
            'primary_color' => '#ffffff',
            'secondary_color' => '#000000',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('Test Team', session('team_registration.team_name'));
    }
}
