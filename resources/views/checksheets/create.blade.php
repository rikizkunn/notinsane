@extends('layouts.app')
@section('title', 'Isi checksheet')

@section('content')
    <h1 class="h3 mb-4">Isi checksheet</h1>

    <div class="card"><div class="card-body" style="max-width: 640px;">
        <form method="POST" action="{{ route('checksheets.store') }}">
            @csrf
            @include('checksheets._form', ['checksheet' => null])
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('checksheets.index') }}" class="btn btn-link">Batal</a>
        </form>
    </div></div>
@endsection
