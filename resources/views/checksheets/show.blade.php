@extends('layouts.app')
@section('title', 'Detail checksheet')

@section('content')
    <h1 class="h3 mb-4">Detail checksheet</h1>

    <div class="card mb-3"><div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><span class="badge text-bg-{{ $checksheet->status->badge() }}">{{ $checksheet->status->label() }}</span></dd>
            <dt class="col-sm-3">Tanggal pemeriksaan</dt>
            <dd class="col-sm-9">{{ $checksheet->tanggal_pemeriksaan?->format('d M Y') }}</dd>
            <dt class="col-sm-3">Area / Mesin</dt>
            <dd class="col-sm-9">{{ $checksheet->dataProduksi?->area }} / {{ $checksheet->dataProduksi?->mesin_peralatan }}</dd>
            <dt class="col-sm-3">Operator</dt>
            <dd class="col-sm-9">{{ $checksheet->operator?->nama }}</dd>
            <dt class="col-sm-3">Hasil pemeriksaan</dt>
            <dd class="col-sm-9">{{ $checksheet->hasil_pemeriksaan }}</dd>
            <dt class="col-sm-3">Catatan</dt>
            <dd class="col-sm-9">{{ $checksheet->catatan ?: '—' }}</dd>
        </dl>
    </div></div>

    @if ($checksheet->verifikasi)
        <div class="card mb-3"><div class="card-body">
            <h2 class="h6">Hasil verifikasi</h2>
            <dl class="row mb-0">
                <dt class="col-sm-3">Keputusan</dt>
                <dd class="col-sm-9">{{ ucfirst($checksheet->verifikasi->status_persetujuan->value) }}</dd>
                <dt class="col-sm-3">Tanggal</dt>
                <dd class="col-sm-9">{{ $checksheet->verifikasi->tanggal_verifikasi?->format('d M Y') }}</dd>
                <dt class="col-sm-3">Supervisor</dt>
                <dd class="col-sm-9">{{ $checksheet->verifikasi->supervisor?->nama }}</dd>
                <dt class="col-sm-3">Catatan</dt>
                <dd class="col-sm-9">{{ $checksheet->verifikasi->catatan ?: '—' }}</dd>
            </dl>
        </div></div>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-link">Kembali</a>
@endsection
