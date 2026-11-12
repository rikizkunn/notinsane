@extends('layouts.app')
@section('title', 'Ubah pengguna')

@section('content')
    <h1 class="h3 mb-4">Ubah pengguna</h1>

    <div class="card"><div class="card-body" style="max-width: 560px;">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label" for="nama">Nama</label>
                <input id="nama" name="nama" type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}">
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input id="username" name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}">
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password baru</label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan kalau nggak diganti">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="role">Role</label>
                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
                    @foreach ($roles as $r)
                        <option value="{{ $r->value }}" @selected(old('role', $user->role->value) === $r->value)>{{ $r->label() }}</option>
                    @endforeach
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-link">Batal</a>
        </form>
    </div></div>
@endsection
