<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\KlasterisasiController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\KepalaUPA\KepalaUPAController;
use App\Http\Controllers\KetuaJurusan\KetuaJurusanController;
use App\Http\Controllers\KetuaProdi\KetuaProdiController;
use App\Http\Controllers\WakilDirektur\WakilDirekturController;





Route::get('/', [HomeController::class, 'index'])->name('index');

Auth::routes();

// Dashboard Route
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/home', [DataController::class, 'index'])->name('home');
    Route::post('/data/upload', [DataController::class, 'upload'])->name('data.upload');
    Route::get('/klasterisasi', [KlasterisasiController::class, 'index'])->name('klasterisasi.index');
    Route::post('/klasterisasi/analyze', [KlasterisasiController::class, 'analyze'])->name('klasterisasi.analyze');
    Route::get('/klasterisasi/result', [KlasterisasiController::class, 'result'])->name('klasterisasi.result');


    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Additional admin routes will be added here
    });

    // Kepala UPA routes
    Route::prefix('kepala-upa')->middleware('role:kepala_upa')->group(function () {
        Route::get('/dashboard', [KepalaUPAController::class, 'dashboard'])->name('kepala-upa.dashboard');
    });

    // Ketua Jurusan routes
    Route::prefix('ketua-jurusan')->middleware('role:ketua_jurusan')->group(function () {
        Route::get('/dashboard', [KetuaJurusanController::class, 'dashboard'])->name('ketua-jurusan.dashboard');
    });

    // Ketua Prodi routes
    Route::prefix('ketua-prodi')->middleware('role:ketua_prodi')->group(function () {
        Route::get('/dashboard', [KetuaProdiController::class, 'dashboard'])->name('ketua-prodi.dashboard');
    });

    // Wakil Direktur routes
    Route::prefix('wakil-direktur')->middleware('role:wakil_direktur')->group(function () {
        Route::get('/dashboard', [WakilDirekturController::class, 'dashboard'])->name('wakil-direktur.dashboard');
    });
});

// Route::get('/session-test', function () {
//     session(['foo' => 'bar']);
//     return redirect('/session-check');
// });

// Route::get('/session-check', function () {
//     return session('foo') ?? 'session kosong';
// });
