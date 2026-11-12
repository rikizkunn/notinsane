<?php

namespace App\Http\Controllers;

use App\Models\DataProduksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataProduksiController extends Controller
{
    public function index(): View
    {
        $items = DataProduksi::with('pembuat')->latest()->paginate(15);

        return view('data-produksi.index', compact('items'));
    }

    public function create(): View
    {
        return view('data-produksi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['created_by'] = $request->user()->id;

        DataProduksi::create($data);

        return redirect()->route('data-produksi.index')->with('status', 'Data produksi ditambahkan.');
    }

    public function edit(DataProduksi $data_produksi): View
    {
        return view('data-produksi.edit', ['item' => $data_produksi]);
    }

    public function update(Request $request, DataProduksi $data_produksi): RedirectResponse
    {
        $data_produksi->update($this->validateData($request));

        return redirect()->route('data-produksi.index')->with('status', 'Data produksi diperbarui.');
    }

    public function destroy(DataProduksi $data_produksi): RedirectResponse
    {
        $data_produksi->delete();

        return redirect()->route('data-produksi.index')->with('status', 'Data produksi dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'area' => ['required', 'string', 'max:255'],
            'mesin_peralatan' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'area.required' => 'Area wajib diisi.',
            'mesin_peralatan.required' => 'Mesin/peralatan wajib diisi.',
        ]);
    }
}
