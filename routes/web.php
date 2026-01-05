<?php

use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('bukutamu.kunjungan.index');
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Basic rate limit untuk mencegah brute-force (5 request/menit per IP).
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('bukutamu')->as('bukutamu.')->middleware('auth')->group(function () {
    // Aksi khusus: akhiri kunjungan (status -> Selesai)
    Route::put('kunjungan/{ID_KUNJUNGAN}/end', [KunjunganController::class, 'end'])->name('kunjungan.end');

    // Cetak bukti kunjungan (halaman siap print)
    Route::get('kunjungan/{ID_KUNJUNGAN}/print', [KunjunganController::class, 'print'])->name('kunjungan.print');

    // Export (harus didefinisikan sebelum resource agar tidak ketangkap sebagai {ID_KUNJUNGAN})
    Route::get('kunjungan/export/csv', [KunjunganController::class, 'exportCsv'])->name('kunjungan.export.csv');
    Route::get('kunjungan/export/pdf', [KunjunganController::class, 'exportPdf'])->name('kunjungan.export.pdf');

    // Async data untuk dashboard
    Route::get('kunjungan/per-unit', [KunjunganController::class, 'perUnit'])->name('kunjungan.perUnit');

    // Resource (CRUD) untuk Kunjungan
    Route::resource('kunjungan', KunjunganController::class)->names('kunjungan');

    // Read-only (index) untuk master data
    Route::get('tamu', [TamuController::class, 'index'])->name('tamu.index');
    Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('unit', [UnitController::class, 'index'])->name('unit.index');
});
