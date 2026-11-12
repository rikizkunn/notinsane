<?php

namespace App\Http\Controllers;

use App\Enums\ChecksheetStatus;
use App\Models\Checksheet;
use App\Models\DataProduksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChecksheetController extends Controller
{
    public function index(Request $request): View
    {
        $checksheets = Checksheet::with('dataProduksi')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('checksheets.index', compact('checksheets'));
    }

    public function create(): View
    {
        return view('checksheets.create', ['dataProduksi' => DataProduksi::latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['user_id'] = $request->user()->id;
        $data['status'] = ChecksheetStatus::Draft->value;

        Checksheet::create($data);

        return redirect()->route('checksheets.index')->with('status', 'Checksheet disimpan sebagai draft.');
    }

    public function edit(Request $request, Checksheet $checksheet): View
    {
        $this->authorizeOwner($request, $checksheet);
        abort_unless($this->isEditable($checksheet), 403, 'Checksheet ini nggak bisa diubah lagi.');

        return view('checksheets.edit', [
            'checksheet' => $checksheet,
            'dataProduksi' => DataProduksi::latest()->get(),
        ]);
    }

    public function update(Request $request, Checksheet $checksheet): RedirectResponse
    {
        $this->authorizeOwner($request, $checksheet);
        abort_unless($this->isEditable($checksheet), 403, 'Checksheet ini nggak bisa diubah lagi.');

        $checksheet->update($this->validateData($request));

        return redirect()->route('checksheets.index')->with('status', 'Checksheet diperbarui.');
    }

    public function submit(Request $request, Checksheet $checksheet): RedirectResponse
    {
        $this->authorizeOwner($request, $checksheet);
        abort_unless($this->isEditable($checksheet), 403, 'Checksheet ini sudah dikirim.');

        $checksheet->update(['status' => ChecksheetStatus::Dikirim->value]);

        return redirect()->route('checksheets.index')->with('status', 'Checksheet dikirim ke supervisor.');
    }

    public function show(Checksheet $checksheet): View
    {
        $checksheet->load(['dataProduksi', 'operator', 'verifikasi.supervisor']);

        return view('checksheets.show', compact('checksheet'));
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'data_produksi_id' => ['required', 'exists:data_produksi,id'],
            'tanggal_pemeriksaan' => ['required', 'date'],
            'hasil_pemeriksaan' => ['required', 'string'],
            'catatan' => ['nullable', 'string'],
        ], [
            'data_produksi_id.required' => 'Pilih data produksi dulu.',
            'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan wajib diisi.',
            'hasil_pemeriksaan.required' => 'Hasil pemeriksaan wajib diisi.',
        ]);
    }

    private function authorizeOwner(Request $request, Checksheet $checksheet): void
    {
        abort_unless($checksheet->user_id === $request->user()->id, 403, 'Ini bukan checksheet kamu.');
    }

    // Only drafts and rejected sheets can still be changed
    private function isEditable(Checksheet $checksheet): bool
    {
        return in_array($checksheet->status, [ChecksheetStatus::Draft, ChecksheetStatus::Ditolak], true);
    }
}
