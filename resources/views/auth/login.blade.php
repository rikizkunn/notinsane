<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
<div class="container" style="max-width: 420px;">
    <div class="card shadow-sm mt-5">
        <div class="card-body p-4">
            <h1 class="h4 mb-1">Checksheet Produksi</h1>
            <p class="text-body-secondary small mb-4">Masuk pakai akun kamu.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input id="username" name="username" type="text"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}" autofocus>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
