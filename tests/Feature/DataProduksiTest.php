<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataProduksiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_data_produksi(): void
    {
        $admin = User::factory()->role(Role::Administrator)->create();

        $this->actingAs($admin)->post('/data-produksi', [
            'tanggal' => now()->toDateString(),
            'area' => 'Testing',
            'mesin_peralatan' => 'Tester Audio',
            'keterangan' => 'Cek harian',
        ])->assertRedirect(route('data-produksi.index'));

        $this->assertDatabaseHas('data_produksi', [
            'area' => 'Testing',
            'mesin_peralatan' => 'Tester Audio',
            'created_by' => $admin->id,
        ]);
    }

    public function test_operator_cannot_create_data_produksi(): void
    {
        $operator = User::factory()->role(Role::Operator)->create();

        $this->actingAs($operator)->get('/data-produksi/create')->assertForbidden();
    }
}
