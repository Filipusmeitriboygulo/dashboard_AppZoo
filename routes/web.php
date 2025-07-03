<?php

use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DataUploadController;
use App\Http\Controllers\Admin\KlasterisasiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ExportController;
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

// Route DiLuar Prefix
Route::get('/klasterisasi/result/{upload_id}', function ($upload_id) {
    $user = Auth::user();
    if (!$user) {
        abort(403, 'Unauthorized');
    }

    if ($user->role === 'admin') {
        return app(KlasterisasiController::class)->result($upload_id);
    } elseif ($user->role === 'kepala_upa') {
        return app(KepalaUPAController::class)->result($upload_id);
    }

    abort(403, 'Access denied');
})->middleware('auth')->name('klasterisasi.result');



Route::prefix('admin')->middleware('role:admin')->group(function () {
    
    Route::get('/home', [DataUploadController::class, 'index'])->name('home');
    // File Upload
    Route::get('/data-upload', [UploadLogController::class, 'index'])->name('admin.data-upload.index');
    Route::post('/data/upload', [DataUploadController::class, 'upload'])->name('data.upload');
    Route::get('/data-upload/scores/{uploadId}', [DataUploadController::class, 'viewScores'])->name('data.scores');
    Route::post('/data-upload/scores-delete/{uploadId}', [DataUploadController::class, 'deleteScores'])->name('data.score-delete');

    Route::get('/klasterisasi', [KlasterisasiController::class, 'index'])->name('klasterisasi.index');
    Route::post('/klasterisasi/analyze', [KlasterisasiController::class, 'analyze'])->name('klasterisasi.analyze');
    Route::post('/klasterisasi/reanalyze/{id}', [KlasterisasiController::class, 'reanalyze'])->name('klasterisasi.reanalyze');
    // Route::get('/klasterisasi/result/{upload_id}', [KlasterisasiController::class, 'result'])->name('klasterisasi.result');

    // Export Controller
    Route::get('/export-excel/{upload_id}', [ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export-pdf/{upload_id}', [ExportController::class, 'exportPdf'])->name('export.pdf');

    // Tambah User
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});


// Kepala UPA routes
Route::prefix('kepala-upa')->middleware('role:kepala_upa')->group(function () {
    Route::get('/dashboard', [KepalaUPAController::class, 'index'])->name('kepala-upa.dashboard');
    // Route::get('/klasterisasi/result/{upload_id}', [KepalaUPAController::class, 'result'])->name('kepala-upa.result');
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
