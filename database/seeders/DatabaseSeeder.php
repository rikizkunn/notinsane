<?php

namespace Database\Seeders;

use App\Enums\ChecksheetStatus;
use App\Enums\Role;
use App\Enums\StatusPersetujuan;
use App\Models\Checksheet;
use App\Models\DataProduksi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'nama' => 'Admin Sistem',
            'username' => 'admin',
            'password' => 'password',
            'role' => Role::Administrator->value,
        ]);

        $operator = User::create([
            'nama' => 'Yehezkiel Evaldo',
            'username' => 'operator',
            'password' => 'password',
            'role' => Role::Operator->value,
        ]);

        $supervisor = User::create([
            'nama' => 'Rendi M.Y',
            'username' => 'supervisor',
            'password' => 'password',
            'role' => Role::Supervisor->value,
        ]);

        User::create([
            'nama' => 'Manajemen GAS',
            'username' => 'manajemen',
            'password' => 'password',
            'role' => Role::Manajemen->value,
        ]);

        $area1 = DataProduksi::create([
            'tanggal' => now()->toDateString(),
            'area' => 'Testing',
            'mesin_peralatan' => 'Tester Audio',
            'keterangan' => 'Pemeriksaan harian',
            'created_by' => $admin->id,
        ]);

        $area2 = DataProduksi::create([
            'tanggal' => now()->toDateString(),
            'area' => 'Assembly',
            'mesin_peralatan' => 'Conveyor Line 1',
            'keterangan' => null,
            'created_by' => $admin->id,
        ]);

        // Draft milik operator
        Checksheet::create([
            'data_produksi_id' => $area1->id,
            'user_id' => $operator->id,
            'tanggal_pemeriksaan' => now()->toDateString(),
            'hasil_pemeriksaan' => 'Semua titik solder normal.',
            'status' => ChecksheetStatus::Draft->value,
        ]);

        // Menunggu verifikasi supervisor
        Checksheet::create([
            'data_produksi_id' => $area2->id,
            'user_id' => $operator->id,
            'tanggal_pemeriksaan' => now()->toDateString(),
            'hasil_pemeriksaan' => 'Belt conveyor sedikit kendur, perlu dicek.',
            'status' => ChecksheetStatus::Dikirim->value,
        ]);

        // Sudah disetujui, masuk ke laporan bulan ini
        $disetujui = Checksheet::create([
            'data_produksi_id' => $area1->id,
            'user_id' => $operator->id,
            'tanggal_pemeriksaan' => now()->toDateString(),
            'hasil_pemeriksaan' => 'Output audio sesuai standar.',
            'status' => ChecksheetStatus::Disetujui->value,
        ]);

        $disetujui->verifikasi()->create([
            'supervisor_id' => $supervisor->id,
            'tanggal_verifikasi' => now()->toDateString(),
            'status_persetujuan' => StatusPersetujuan::Disetujui->value,
            'catatan' => 'Oke, lanjut.',
        ]);
    }
}
