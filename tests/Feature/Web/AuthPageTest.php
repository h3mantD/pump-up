<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
    }

    public function test_user_can_login_via_web(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    }

    public function test_user_can_register_via_web(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
    }

    public function test_guest_cannot_access_login_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect();
    }

    public function test_root_redirects_guests_to_products(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/products');
    }

    public function test_root_redirects_authenticated_users_to_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect('/dashboard');
    }

    public function test_404_returns_error_page(): void
    {
        $response = $this->get('/nonexistent-page');

        $response->assertStatus(404);
    }
}
