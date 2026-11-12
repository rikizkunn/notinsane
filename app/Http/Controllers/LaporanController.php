<?php

namespace App\Http\Controllers;

use App\Enums\ChecksheetStatus;
use App\Models\Checksheet;
use App\Models\DataProduksi;
use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        $laporan = Laporan::with('pembuat')->latest()->paginate(15);

        return view('laporan.index', compact('laporan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bulan' => ['required', 'date_format:Y-m'],
        ], [
            'bulan.required' => 'Pilih bulan dulu.',
            'bulan.date_format' => 'Format bulan nggak valid.',
        ]);

        $laporan = Laporan::create([
            'periode' => $data['bulan'],
            'tanggal_dibuat' => now()->toDateString(),
            'id_pembuat' => $request->user()->id,
        ]);

        return redirect()->route('laporan.show', $laporan)->with('status', 'Laporan dibuat.');
    }

    public function show(Request $request, Laporan $laporan): View
    {
        $area = $this->area($request);

        $checksheets = $this->recapQuery($laporan, $area)
            ->orderBy('tanggal_pemeriksaan')
            ->paginate(10)
            ->withQueryString();

        return view('laporan.show', [
            'laporan' => $laporan,
            'checksheets' => $checksheets,
            'areaOptions' => $this->areaOptions($laporan),
            'area' => $area,
        ]);
    }

    public function pdf(Request $request, Laporan $laporan): Response
    {
        $area = $this->area($request);

        $checksheets = $this->recapQuery($laporan, $area)
            ->orderBy('tanggal_pemeriksaan')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('laporan', 'checksheets', 'area'));

        return $pdf->download('laporan-'.$laporan->periode.($area ? '-'.$area : '').'.pdf');
    }

    /** Approved checksheets for the laporan's month, optionally narrowed to one area. */
    private function recapQuery(Laporan $laporan, ?string $area): Builder
    {
        [$tahun, $bulan] = explode('-', $laporan->periode);

        return Checksheet::with(['dataProduksi', 'operator'])
            ->where('status', ChecksheetStatus::Disetujui->value)
            ->whereYear('tanggal_pemeriksaan', $tahun)
            ->whereMonth('tanggal_pemeriksaan', $bulan)
            ->when($area, fn (Builder $q) => $q->whereHas(
                'dataProduksi',
                fn (Builder $dp) => $dp->where('area', $area),
            ));
    }

    /** Distinct areas that actually appear in this month's approved checksheets. */
    private function areaOptions(Laporan $laporan)
    {
        [$tahun, $bulan] = explode('-', $laporan->periode);

        return DataProduksi::whereHas('checksheets', function (Builder $q) use ($tahun, $bulan) {
            $q->where('status', ChecksheetStatus::Disetujui->value)
                ->whereYear('tanggal_pemeriksaan', $tahun)
                ->whereMonth('tanggal_pemeriksaan', $bulan);
        })->orderBy('area')->distinct()->pluck('area');
    }

    private function area(Request $request): ?string
    {
        $area = trim((string) $request->query('area'));

        return $area === '' ? null : $area;
    }
}
