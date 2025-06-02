<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\KlasterisasiController;




Route::get('/', [HomeController::class, 'index'])->name('index');

Auth::routes();

// Dashboard Route
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/home', [DataController::class, 'index'])->name('home');
    Route::post('/data/upload', [DataController::class, 'upload'])->name('data.upload');
    Route::get('/klasterisasi', [KlasterisasiController::class, 'index'])->name('klasterisasi.index');
    Route::post('/klasterisasi/analyze', [KlasterisasiController::class, 'analyze'])->name('klasterisasi.analyze');
    Route::get('/klasterisasi/hasil', [KlasterisasiController::class, 'result'])->name('klasterisasi.result');
});
