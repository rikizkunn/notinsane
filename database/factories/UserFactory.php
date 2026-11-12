<?php

namespace Database\Factories;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => Role::Operator->value,
        ];
    }

    public function role(Role $role): static
    {
        return $this->state(fn () => ['role' => $role->value]);
    }
}
