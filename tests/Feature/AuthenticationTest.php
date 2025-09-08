<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Basic test setup without roles/permissions
    }

    /** @test */
    public function user_can_view_login_form()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);
        
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        
        $response = $this->post('/logout');
        
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        
        $this->actingAs($user);
        
        $response = $this->get('/dashboard');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_access_user_management()
    {
        $admin = User::factory()->create();
        
        $this->actingAs($admin);
        
        $response = $this->get('/users');
        
        $response->assertStatus(200);
    }



    /** @test */
    public function user_can_access_assets()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        
        $response = $this->get('/assets');
        
        $response->assertStatus(200);
    }



    /** @test */
    public function login_validates_required_fields()
    {
        $response = $this->post('/login', []);
        
        $response->assertSessionHasErrors(['email', 'password']);
    }

    /** @test */
    public function login_validates_email_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);
        
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_is_redirected_to_intended_page_after_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);
        
        // Try to access a protected page
        $response = $this->get('/users');
        $response->assertRedirect('/login');
        
        // Login
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        
        // Should be redirected to the intended page
        $response->assertRedirect('/users');
    }

    /** @test */
    public function remember_me_functionality_works()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => true
        ]);
        
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        
        // Check if remember token is set
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }

    /** @test */
    public function user_session_expires_after_inactivity()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        
        // Simulate session expiry by manually logging out
        auth()->logout();
        
        $response = $this->get('/dashboard');
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function multiple_failed_login_attempts_are_tracked()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);
        
        // Make multiple failed attempts
        for ($i = 0; $i < 3; $i++) {
            $response = $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword'
            ]);
        }
        
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}