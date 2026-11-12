@extends('layouts.app')
@section('title', 'Data Produksi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Data Produksi</h1>
        <a href="{{ route('data-produksi.create') }}" class="btn btn-primary">Tambah data</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr><th>Tanggal</th><th>Area</th><th>Mesin/Peralatan</th><th>Keterangan</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->tanggal?->format('d M Y') }}</td>
                            <td>{{ $item->area }}</td>
                            <td>{{ $item->mesin_peralatan }}</td>
                            <td>{{ $item->keterangan ?: '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('data-produksi.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                                <form method="POST" action="{{ route('data-produksi.destroy', $item) }}" class="d-inline"
                                      onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-body-secondary py-3">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $items->links() }}</div>
@endsection
