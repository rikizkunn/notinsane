<?php

namespace Tests\Feature;

use App\Enums\ChecksheetStatus;
use App\Enums\Role;
use App\Models\Checksheet;
use App\Models\DataProduksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChecksheetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_operator_can_create_and_submit_checksheet(): void
    {
        $operator = User::factory()->role(Role::Operator)->create();
        $data = DataProduksi::factory()->create();

        $this->actingAs($operator)->post('/checksheets', [
            'data_produksi_id' => $data->id,
            'tanggal_pemeriksaan' => now()->toDateString(),
            'hasil_pemeriksaan' => 'Normal semua.',
        ])->assertRedirect(route('checksheets.index'));

        $checksheet = Checksheet::first();
        $this->assertNotNull($checksheet);
        $this->assertSame(ChecksheetStatus::Draft, $checksheet->status);
        $this->assertSame($operator->id, $checksheet->user_id);

        $this->actingAs($operator)->post("/checksheets/{$checksheet->id}/submit")
            ->assertRedirect(route('checksheets.index'));

        $this->assertSame(ChecksheetStatus::Dikirim, $checksheet->fresh()->status);
    }

    public function test_operator_cannot_submit_someone_elses_checksheet(): void
    {
        $owner = User::factory()->role(Role::Operator)->create();
        $other = User::factory()->role(Role::Operator)->create();
        $checksheet = Checksheet::factory()->for($owner, 'operator')->create();

        $this->actingAs($other)->post("/checksheets/{$checksheet->id}/submit")->assertForbidden();
    }

    public function test_supervisor_can_approve_checksheet(): void
    {
        $supervisor = User::factory()->role(Role::Supervisor)->create();
        $checksheet = Checksheet::factory()->status(ChecksheetStatus::Dikirim)->create();

        $this->actingAs($supervisor)->post("/verifikasi/{$checksheet->id}/approve", [
            'catatan' => 'Oke.',
        ])->assertRedirect(route('verifikasi.index'));

        $checksheet->refresh();
        $this->assertSame(ChecksheetStatus::Disetujui, $checksheet->status);
        $this->assertNotNull($checksheet->verifikasi);
        $this->assertSame($supervisor->id, $checksheet->verifikasi->supervisor_id);
    }

    public function test_supervisor_reject_requires_reason_and_sets_status(): void
    {
        $supervisor = User::factory()->role(Role::Supervisor)->create();
        $checksheet = Checksheet::factory()->status(ChecksheetStatus::Dikirim)->create();

        // Missing reason -> validation error, status unchanged
        $this->actingAs($supervisor)->post("/verifikasi/{$checksheet->id}/reject", [])
            ->assertSessionHasErrors('catatan');
        $this->assertSame(ChecksheetStatus::Dikirim, $checksheet->fresh()->status);

        // With reason -> rejected
        $this->actingAs($supervisor)->post("/verifikasi/{$checksheet->id}/reject", [
            'catatan' => 'Data kurang lengkap.',
        ])->assertRedirect(route('verifikasi.index'));

        $this->assertSame(ChecksheetStatus::Ditolak, $checksheet->fresh()->status);
    }
}
