<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataProduksi>
 */
class DataProduksiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'area' => fake()->randomElement(['LCM', 'Assembly', 'Testing', 'Packing']),
            'mesin_peralatan' => fake()->randomElement(['Mesin Solder', 'Conveyor', 'Tester Audio', 'Mesin Press']),
            'keterangan' => fake()->optional()->sentence(),
            'created_by' => User::factory()->role(Role::Administrator),
        ];
    }
}
