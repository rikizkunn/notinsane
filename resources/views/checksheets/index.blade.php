@extends('layouts.app')
@section('title', 'Checksheet')
@php use App\Enums\ChecksheetStatus; @endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Checksheet saya</h1>
        <a href="{{ route('checksheets.create') }}" class="btn btn-primary">Isi checksheet</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr><th>Tanggal</th><th>Area / Mesin</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($checksheets as $cs)
                        @php $editable = in_array($cs->status, [ChecksheetStatus::Draft, ChecksheetStatus::Ditolak], true); @endphp
                        <tr>
                            <td>{{ $cs->tanggal_pemeriksaan?->format('d M Y') }}</td>
                            <td>{{ $cs->dataProduksi?->area }} / {{ $cs->dataProduksi?->mesin_peralatan }}</td>
                            <td><span class="badge text-bg-{{ $cs->status->badge() }}">{{ $cs->status->label() }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('checksheets.show', $cs) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>
                                @if ($editable)
                                    <a href="{{ route('checksheets.edit', $cs) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                                    <form method="POST" action="{{ route('checksheets.submit', $cs) }}" class="d-inline"
                                          onsubmit="return confirm('Kirim checksheet ini ke supervisor?')">
                                        @csrf
                                        <button class="btn btn-sm btn-primary" type="submit">Kirim</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-3">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $checksheets->links() }}</div>
@endsection
