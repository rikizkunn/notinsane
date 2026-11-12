@extends('layouts.app')
@section('title', 'Verifikasi')

@section('content')
    <h1 class="h3 mb-3">Menunggu verifikasi</h1>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr><th>Tanggal</th><th>Area / Mesin</th><th>Operator</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($menunggu as $cs)
                        <tr>
                            <td>{{ $cs->tanggal_pemeriksaan?->format('d M Y') }}</td>
                            <td>{{ $cs->dataProduksi?->area }} / {{ $cs->dataProduksi?->mesin_peralatan }}</td>
                            <td>{{ $cs->operator?->nama }}</td>
                            <td class="text-end">
                                <a href="{{ route('verifikasi.show', $cs) }}" class="btn btn-sm btn-primary">Periksa</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-3">Nggak ada yang menunggu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $menunggu->links() }}</div>
@endsection
