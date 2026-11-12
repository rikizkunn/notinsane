<?php

namespace Tests\Feature;

use App\Enums\ChecksheetStatus;
use App\Enums\Role;
use App\Models\Checksheet;
use App\Models\DataProduksi;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_manajemen_can_generate_laporan(): void
    {
        $manajemen = User::factory()->role(Role::Manajemen)->create();

        $response = $this->actingAs($manajemen)->post('/laporan', [
            'bulan' => now()->format('Y-m'),
        ]);

        $laporan = Laporan::first();
        $this->assertNotNull($laporan);
        $response->assertRedirect(route('laporan.show', $laporan));
        $this->assertSame($manajemen->id, $laporan->id_pembuat);
    }

    public function test_laporan_show_renders(): void
    {
        $manajemen = User::factory()->role(Role::Manajemen)->create();
        $laporan = $this->laporanBulanIni($manajemen);

        $this->actingAs($manajemen)->get(route('laporan.show', $laporan))->assertOk();
    }

    public function test_show_can_be_filtered_by_area(): void
    {
        $manajemen = User::factory()->role(Role::Manajemen)->create();
        $laporan = $this->laporanBulanIni($manajemen);

        $this->approvedChecksheet('Testing', 'Hasil area testing');
        $this->approvedChecksheet('Assembly', 'Hasil area assembly');

        $this->actingAs($manajemen)
            ->get(route('laporan.show', ['laporan' => $laporan, 'area' => 'Testing']))
            ->assertOk()
            ->assertSee('Hasil area testing')
            ->assertDontSee('Hasil area assembly');
    }

    public function test_manajemen_can_download_laporan_pdf(): void
    {
        $manajemen = User::factory()->role(Role::Manajemen)->create();
        $laporan = $this->laporanBulanIni($manajemen);
        $this->approvedChecksheet('Testing', 'Hasil area testing');

        $response = $this->actingAs($manajemen)->get(route('laporan.pdf', $laporan));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));
    }

    public function test_operator_cannot_access_laporan(): void
    {
        $operator = User::factory()->role(Role::Operator)->create();

        $this->actingAs($operator)->get('/laporan')->assertForbidden();
    }

    private function laporanBulanIni(User $pembuat): Laporan
    {
        return Laporan::create([
            'periode' => now()->format('Y-m'),
            'tanggal_dibuat' => now()->toDateString(),
            'id_pembuat' => $pembuat->id,
        ]);
    }

    private function approvedChecksheet(string $area, string $hasil): Checksheet
    {
        $dp = DataProduksi::factory()->create(['area' => $area]);

        return Checksheet::factory()->status(ChecksheetStatus::Disetujui)->create([
            'data_produksi_id' => $dp->id,
            'tanggal_pemeriksaan' => now()->toDateString(),
            'hasil_pemeriksaan' => $hasil,
        ]);
    }
}
