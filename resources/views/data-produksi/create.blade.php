@extends('layouts.app')
@section('title', 'Tambah data produksi')

@section('content')
    <h1 class="h3 mb-4">Tambah data produksi</h1>

    <div class="card"><div class="card-body" style="max-width: 560px;">
        <form method="POST" action="{{ route('data-produksi.store') }}">
            @csrf
            @include('data-produksi._form', ['item' => null])
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('data-produksi.index') }}" class="btn btn-link">Batal</a>
        </form>
    </div></div>
@endsection
