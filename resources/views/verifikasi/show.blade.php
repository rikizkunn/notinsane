@extends('layouts.app')
@section('title', 'Periksa checksheet')

@section('content')
    <h1 class="h3 mb-4">Periksa checksheet</h1>

    <div class="card mb-3"><div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Tanggal pemeriksaan</dt>
            <dd class="col-sm-9">{{ $checksheet->tanggal_pemeriksaan?->format('d M Y') }}</dd>
            <dt class="col-sm-3">Area / Mesin</dt>
            <dd class="col-sm-9">{{ $checksheet->dataProduksi?->area }} / {{ $checksheet->dataProduksi?->mesin_peralatan }}</dd>
            <dt class="col-sm-3">Operator</dt>
            <dd class="col-sm-9">{{ $checksheet->operator?->nama }}</dd>
            <dt class="col-sm-3">Hasil pemeriksaan</dt>
            <dd class="col-sm-9">{{ $checksheet->hasil_pemeriksaan }}</dd>
            <dt class="col-sm-3">Catatan operator</dt>
            <dd class="col-sm-9">{{ $checksheet->catatan ?: '—' }}</dd>
        </dl>
    </div></div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100"><div class="card-body">
                <h2 class="h6">Setujui</h2>
                <form method="POST" action="{{ route('verifikasi.approve', $checksheet) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="catatan_approve">Catatan (opsional)</label>
                        <textarea id="catatan_approve" name="catatan" rows="2" class="form-control"></textarea>
                    </div>
                    <button class="btn btn-success" type="submit">Setujui</button>
                </form>
            </div></div>
        </div>
        <div class="col-md-6">
            <div class="card h-100"><div class="card-body">
                <h2 class="h6">Tolak</h2>
                <form method="POST" action="{{ route('verifikasi.reject', $checksheet) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="catatan_reject">Alasan ditolak</label>
                        <textarea id="catatan_reject" name="catatan" rows="2" class="form-control @error('catatan') is-invalid @enderror"></textarea>
                        @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn btn-danger" type="submit">Tolak</button>
                </form>
            </div></div>
        </div>
    </div>

    <a href="{{ route('verifikasi.index') }}" class="btn btn-link mt-3">Kembali</a>
@endsection
