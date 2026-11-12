<?php

namespace App\Http\Controllers;

use App\Enums\ChecksheetStatus;
use App\Enums\StatusPersetujuan;
use App\Models\Checksheet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VerifikasiController extends Controller
{
    public function index(): View
    {
        $menunggu = Checksheet::with(['dataProduksi', 'operator'])
            ->where('status', ChecksheetStatus::Dikirim->value)
            ->latest()
            ->paginate(15);

        return view('verifikasi.index', compact('menunggu'));
    }

    public function show(Checksheet $checksheet): View
    {
        $checksheet->load(['dataProduksi', 'operator', 'verifikasi']);

        return view('verifikasi.show', compact('checksheet'));
    }

    public function approve(Request $request, Checksheet $checksheet): RedirectResponse
    {
        $request->validate(['catatan' => ['nullable', 'string']]);

        $this->putVerdict($request, $checksheet, StatusPersetujuan::Disetujui, ChecksheetStatus::Disetujui);

        return redirect()->route('verifikasi.index')->with('status', 'Checksheet disetujui.');
    }

    public function reject(Request $request, Checksheet $checksheet): RedirectResponse
    {
        $request->validate([
            'catatan' => ['required', 'string'],
        ], [
            'catatan.required' => 'Tulis alasan kenapa ditolak.',
        ]);

        $this->putVerdict($request, $checksheet, StatusPersetujuan::Ditolak, ChecksheetStatus::Ditolak);

        return redirect()->route('verifikasi.index')->with('status', 'Checksheet dikembalikan untuk revisi.');
    }

    private function putVerdict(
        Request $request,
        Checksheet $checksheet,
        StatusPersetujuan $persetujuan,
        ChecksheetStatus $status,
    ): void {
        abort_unless($checksheet->status === ChecksheetStatus::Dikirim, 403, 'Checksheet ini belum dikirim.');

        DB::transaction(function () use ($request, $checksheet, $persetujuan, $status) {
            $checksheet->verifikasi()->updateOrCreate([], [
                'supervisor_id' => $request->user()->id,
                'tanggal_verifikasi' => now()->toDateString(),
                'status_persetujuan' => $persetujuan->value,
                'catatan' => $request->input('catatan'),
            ]);

            $checksheet->update(['status' => $status->value]);
        });
    }
}
