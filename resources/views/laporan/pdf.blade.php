<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 12px; color: #222; }
        h1 { font-size: 16px; margin: 0 0 2px; }
        .meta { color: #666; font-size: 11px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px 7px; text-align: left; vertical-align: top; }
        th { background: #eee; font-size: 11px; }
        .foot { margin-top: 12px; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <h1>Rekap Produksi {{ $laporan->periode }}</h1>
    <div class="meta">
        PT Global Anugerah Setia (GAS Electronics)<br>
        @if ($area) Area: {{ $area }} · @endif
        Dibuat: {{ $laporan->tanggal_dibuat?->format('d M Y') }}
    </div>

    <table>
        <thead>
            <tr><th>Tanggal</th><th>Area</th><th>Mesin/Peralatan</th><th>Operator</th><th>Hasil</th></tr>
        </thead>
        <tbody>
            @forelse ($checksheets as $cs)
                <tr>
                    <td>{{ $cs->tanggal_pemeriksaan?->format('d M Y') }}</td>
                    <td>{{ $cs->dataProduksi?->area }}</td>
                    <td>{{ $cs->dataProduksi?->mesin_peralatan }}</td>
                    <td>{{ $cs->operator?->nama }}</td>
                    <td>{{ $cs->hasil_pemeriksaan }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;">Nggak ada checksheet disetujui.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="foot">Total {{ $checksheets->count() }} checksheet disetujui.</p>
</body>
</html>
