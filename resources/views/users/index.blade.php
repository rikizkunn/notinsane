@extends('layouts.app')
@section('title', 'Pengguna')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Pengguna</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah pengguna</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr><th>Nama</th><th>Username</th><th>Role</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <td>{{ $u->nama }}</td>
                            <td>{{ $u->username }}</td>
                            <td>{{ $u->role->label() }}</td>
                            <td class="text-end">
                                <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                                <form method="POST" action="{{ route('users.destroy', $u) }}" class="d-inline"
                                      onsubmit="return confirm('Yakin mau hapus {{ $u->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-3">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $users->links() }}</div>
@endsection
