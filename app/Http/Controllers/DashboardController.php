<?php

namespace App\Http\Controllers;

use App\Enums\ChecksheetStatus;
use App\Models\Checksheet;
use App\Models\DataProduksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'data_produksi' => DataProduksi::count(),
            'total' => Checksheet::count(),
            'menunggu' => Checksheet::where('status', ChecksheetStatus::Dikirim->value)->count(),
            'disetujui' => Checksheet::where('status', ChecksheetStatus::Disetujui->value)->count(),
            'ditolak' => Checksheet::where('status', ChecksheetStatus::Ditolak->value)->count(),
        ];

        $terbaru = Checksheet::with(['dataProduksi', 'operator'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', [
            'user' => $request->user(),
            'stats' => $stats,
            'terbaru' => $terbaru,
        ]);
    }
}
