<?php

use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DataUploadController;
use App\Http\Controllers\Admin\KlasterisasiController;
use App\Http\Controllers\KepalaUPA\KepalaUPAController;
use App\Http\Controllers\KetuaJurusan\KetuaJurusanController;
use App\Http\Controllers\KetuaProdi\KetuaProdiController;
use App\Http\Controllers\WakilDirektur\WakilDirekturController;
use App\Http\Controllers\UploadLogController;






Route::get('/', [HomeController::class, 'index'])->name('index');

Auth::routes();

// Dashboard Route
// Route::middleware(['auth'])->group(function () {
// Admin routes
Route::prefix('admin')->middleware('role:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/home', [DataUploadController::class, 'index'])->name('home');
    // Route::get('/data-upload', [DataUploadController::class, 'index'])->name('admin.data-upload.index');
    // Route::get('/data-upload/{scope}/{scopeId?}', [DataUploadController::class, 'showUploadForm'])->name('admin.data-upload.form');
    // Route::post('/data-upload/upload', [DataUploadController::class, 'upload'])->name('admin.data-upload.upload');
    // Route::post('/data-upload/process', [DataUploadController::class, 'processFile'])->name('admin.data-upload.process');
    // Route::post('/data-upload/preview', [DataUploadController::class, 'getPreview'])->name('admin.data-upload.preview');
    // Route::get('/data-upload/batch/{batchId}', [DataUploadController::class, 'viewBatch'])->name('admin.data-upload.batch');
    // Additional admin routes will be added here
    // Route::get('data-upload/scores/{file}', [DataUploadController::class, 'viewScores'])->name('admin.data-upload.scores');
    // Route::post('/data-upload/upload', [UploadLogController::class, 'upload'])->name('toefl.upload');
    // Route::post('/data-upload/process', [UploadLogController::class, 'process'])->name('admin.data-upload.process');
    
    // File Upload
    Route::get('/data-upload', [UploadLogController::class, 'index'])->name('admin.data-upload.index');
    Route::post('/data/upload', [DataUploadController::class, 'upload'])->name('data.upload');
    Route::get('/data-upload/scores/{uploadId}', [DataUploadController::class, 'viewScores'])->name('data.scores');
    Route::post('/data-upload/scores-delete/{uploadId}', [DataUploadController::class, 'deleteScores'])->name('data.score-delete');


    // Klasterisasi 
    // Route::get('/klasterisasi', [KlasterisasiController::class, 'index'])->name('klasterisasi.index');
    // Route::post('/klasterisasi/analyze', [KlasterisasiController::class, 'analyze'])->name('klasterisasi.analyze');
    // Route::get('/klasterisasi/result/{upload_id}', [KlasterisasiController::class, 'result'])->name('klasterisasi.result');

    Route::get('/klasterisasi', [KlasterisasiController::class, 'index'])->name('klasterisasi.index');
    Route::post('/klasterisasi/analyze', [KlasterisasiController::class, 'analyze'])->name('klasterisasi.analyze');
    Route::post('/klasterisasi/reanalyze/{id}', [KlasterisasiController::class, 'reanalyze'])->name('klasterisasi.reanalyze');
    Route::get('/klasterisasi/result/{upload_id}', [KlasterisasiController::class, 'result'])->name('klasterisasi.result');
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






Route::post('/toefl/upload', [ScoreController::class, 'upload'])->name('toefl.upload');
