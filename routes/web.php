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
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('bukutamu')->as('bukutamu.')->middleware('auth')->group(function () {
    // Resource (CRUD) untuk Kunjungan
    Route::resource('kunjungan', KunjunganController::class)->names('kunjungan');

    // Read-only (index) untuk master data
    Route::get('tamu', [TamuController::class, 'index'])->name('tamu.index');
    Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('unit', [UnitController::class, 'index'])->name('unit.index');
});
