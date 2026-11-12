@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
    <h1 class="h3 mb-3">Laporan rekapitulasi</h1>

    <div class="card mb-4"><div class="card-body">
        <form method="POST" action="{{ route('laporan.store') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-auto">
                <label class="form-label" for="bulan">Pilih bulan</label>
                <input id="bulan" name="bulan" type="month" class="form-control @error('bulan') is-invalid @enderror" value="{{ old('bulan') }}">
                @error('bulan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit">Buat laporan</button>
            </div>
        </form>
    </div></div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr><th>Periode</th><th>Dibuat</th><th>Oleh</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $l)
                        <tr>
                            <td>{{ $l->periode }}</td>
                            <td>{{ $l->tanggal_dibuat?->format('d M Y') }}</td>
                            <td>{{ $l->pembuat?->nama }}</td>
                            <td class="text-end"><a href="{{ route('laporan.show', $l) }}" class="btn btn-sm btn-outline-secondary">Lihat</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-3">Belum ada laporan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $laporan->links() }}</div>
@endsection
