<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_operator_cannot_open_user_management(): void
    {
        $operator = User::factory()->role(Role::Operator)->create();

        $this->actingAs($operator)->get('/users')->assertForbidden();
    }

    public function test_administrator_can_open_user_management(): void
    {
        $admin = User::factory()->role(Role::Administrator)->create();

        $this->actingAs($admin)->get('/users')->assertOk();
    }

    public function test_operator_cannot_open_verifikasi(): void
    {
        $operator = User::factory()->role(Role::Operator)->create();

        $this->actingAs($operator)->get('/verifikasi')->assertForbidden();
    }
}
