@php use App\Enums\Role; $role = auth()->user()?->role; @endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Checksheet Produksi') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">Checksheet Produksi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                @if ($role === Role::Administrator)
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Pengguna</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('data-produksi.index') }}">Data Produksi</a></li>
                @elseif ($role === Role::Operator)
                    <li class="nav-item"><a class="nav-link" href="{{ route('checksheets.index') }}">Checksheet</a></li>
                @elseif ($role === Role::Supervisor)
                    <li class="nav-item"><a class="nav-link" href="{{ route('verifikasi.index') }}">Verifikasi</a></li>
                @elseif ($role === Role::Manajemen)
                    <li class="nav-item"><a class="nav-link" href="{{ route('laporan.index') }}">Laporan</a></li>
                @endif
            </ul>
            <div class="d-flex align-items-center gap-3">
                <span class="navbar-text small">{{ auth()->user()->nama }} · {{ $role->label() }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
