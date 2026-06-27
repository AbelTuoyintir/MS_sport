<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'manager'
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/manager/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
