@extends('layouts.app')
@section('title', 'Laporan ' . $laporan->periode)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Rekap produksi {{ $laporan->periode }}</h1>
        <a href="{{ route('laporan.pdf', ['laporan' => $laporan, 'area' => $area]) }}" class="btn btn-danger">Unduh PDF</a>
    </div>

    <form method="GET" action="{{ route('laporan.show', $laporan) }}" class="row g-2 align-items-end mb-3">
        <div class="col-auto">
            <label class="form-label" for="area">Filter area</label>
            <select id="area" name="area" class="form-select" onchange="this.form.submit()">
                <option value="">Semua area</option>
                @foreach ($areaOptions as $opt)
                    <option value="{{ $opt }}" @selected($area === $opt)>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        @if ($area)
            <div class="col-auto">
                <a href="{{ route('laporan.show', $laporan) }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        @endif
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr><th>Tanggal</th><th>Area</th><th>Mesin/Peralatan</th><th>Operator</th><th>Hasil</th></tr>
                </thead>
                <tbody>
                    @forelse ($checksheets as $cs)
                        <tr>
                            <td>{{ $cs->tanggal_pemeriksaan?->format('d M Y') }}</td>
                            <td>{{ $cs->dataProduksi?->area }}</td>
                            <td>{{ $cs->dataProduksi?->mesin_peralatan }}</td>
                            <td>{{ $cs->operator?->nama }}</td>
                            <td>{{ $cs->hasil_pemeriksaan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-body-secondary py-3">Nggak ada checksheet disetujui di sini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <span class="text-body-secondary small">Total {{ $checksheets->total() }} checksheet disetujui.</span>
        {{ $checksheets->links() }}
    </div>

    <a href="{{ route('laporan.index') }}" class="btn btn-link mt-2">Kembali</a>
@endsection
