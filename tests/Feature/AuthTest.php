<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk');
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        User::factory()->role(Role::Operator)->create([
            'username' => 'operator',
            'password' => 'secret123',
        ]);

        $response = $this->post('/login', [
            'username' => 'operator',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'username' => 'operator',
            'password' => 'secret123',
        ]);

        $this->post('/login', [
            'username' => 'operator',
            'password' => 'salah',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
