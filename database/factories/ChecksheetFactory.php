<?php

namespace Database\Factories;

use App\Enums\ChecksheetStatus;
use App\Enums\Role;
use App\Models\DataProduksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checksheet>
 */
class ChecksheetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'data_produksi_id' => DataProduksi::factory(),
            'user_id' => User::factory()->role(Role::Operator),
            'tanggal_pemeriksaan' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'hasil_pemeriksaan' => fake()->sentence(),
            'catatan' => fake()->optional()->sentence(),
            'status' => ChecksheetStatus::Draft->value,
        ];
    }

    public function status(ChecksheetStatus $status): static
    {
        return $this->state(fn () => ['status' => $status->value]);
    }
}
