<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecksheetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataProduksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Administrator
    Route::middleware('role:administrator')->group(function () {
        Route::resource('users', UserController::class)->except('show');
        Route::resource('data-produksi', DataProduksiController::class)->except('show');
    });

    // Operator / Engineer
    Route::middleware('role:operator')->group(function () {
        Route::get('checksheets', [ChecksheetController::class, 'index'])->name('checksheets.index');
        Route::get('checksheets/create', [ChecksheetController::class, 'create'])->name('checksheets.create');
        Route::post('checksheets', [ChecksheetController::class, 'store'])->name('checksheets.store');
        Route::get('checksheets/{checksheet}/edit', [ChecksheetController::class, 'edit'])->name('checksheets.edit');
        Route::put('checksheets/{checksheet}', [ChecksheetController::class, 'update'])->name('checksheets.update');
        Route::post('checksheets/{checksheet}/submit', [ChecksheetController::class, 'submit'])->name('checksheets.submit');
    });

    // Checksheet detail is viewable by operator, supervisor, and administrator
    Route::get('checksheets/{checksheet}', [ChecksheetController::class, 'show'])
        ->name('checksheets.show')
        ->middleware('role:operator,supervisor,administrator');

    // Supervisor
    Route::middleware('role:supervisor')->group(function () {
        Route::get('verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('verifikasi/{checksheet}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::post('verifikasi/{checksheet}/approve', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');
        Route::post('verifikasi/{checksheet}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');
    });

    // Manajemen
    Route::middleware('role:manajemen')->group(function () {
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('laporan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('laporan/{laporan}/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
        Route::get('laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
    });
});
