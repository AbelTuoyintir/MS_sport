<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_page_is_accessible()
    {
        Team::create([
            'reference_code' => 'TEST-123',
            'team_name' => 'Test Team',
            'team_size' => '11',
            'division' => 'premier',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'password' => 'password'
        ]);

        $response = $this->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('League Statistics');
    }
}
