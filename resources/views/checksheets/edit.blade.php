@extends('layouts.app')
@section('title', 'Ubah checksheet')

@section('content')
    <h1 class="h3 mb-4">Ubah checksheet</h1>

    <div class="card"><div class="card-body" style="max-width: 640px;">
        <form method="POST" action="{{ route('checksheets.update', $checksheet) }}">
            @csrf @method('PUT')
            @include('checksheets._form')
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('checksheets.index') }}" class="btn btn-link">Batal</a>
        </form>
    </div></div>
@endsection
