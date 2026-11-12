@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4">Halo, {{ $user->nama }}</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md">
            <div class="card text-bg-light"><div class="card-body">
                <div class="small text-body-secondary">Data produksi</div>
                <div class="fs-3 fw-semibold">{{ $stats['data_produksi'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card text-bg-light"><div class="card-body">
                <div class="small text-body-secondary">Total checksheet</div>
                <div class="fs-3 fw-semibold">{{ $stats['total'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card text-bg-warning-subtle"><div class="card-body">
                <div class="small text-body-secondary">Menunggu verifikasi</div>
                <div class="fs-3 fw-semibold">{{ $stats['menunggu'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card text-bg-success-subtle"><div class="card-body">
                <div class="small text-body-secondary">Disetujui</div>
                <div class="fs-3 fw-semibold">{{ $stats['disetujui'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card text-bg-danger-subtle"><div class="card-body">
                <div class="small text-body-secondary">Ditolak</div>
                <div class="fs-3 fw-semibold">{{ $stats['ditolak'] }}</div>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Checksheet terbaru</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th><th>Area / Mesin</th><th>Operator</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $cs)
                        <tr>
                            <td>{{ $cs->tanggal_pemeriksaan?->format('d M Y') }}</td>
                            <td>{{ $cs->dataProduksi?->area }} / {{ $cs->dataProduksi?->mesin_peralatan }}</td>
                            <td>{{ $cs->operator?->nama }}</td>
                            <td><span class="badge text-bg-{{ $cs->status->badge() }}">{{ $cs->status->label() }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-3">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
