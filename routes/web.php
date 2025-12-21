<?php

use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('bukutamu.kunjungan.index');
});

Route::prefix('bukutamu')->as('bukutamu.')->group(function () {
    // Resource (CRUD) untuk Kunjungan
    Route::resource('kunjungan', KunjunganController::class)->names('kunjungan');

    // Read-only (index) untuk master data
    Route::get('tamu', [TamuController::class, 'index'])->name('tamu.index');
    Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('unit', [UnitController::class, 'index'])->name('unit.index');
});
