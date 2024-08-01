<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ZooController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SatwaController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\KontakController; 
use App\Http\Controllers\TiketController; 


// Route::get('/', function () {
//     return view('auth.login');
// });


// Frontend Route 
// Zoo Controller

Route::get('/', [ZooController::class, 'index'])->name('index');
Route::get('/berita',[BeritaController::class, 'index'])->name('berita');
Route::get('/profil',[ProfilController::class, 'index'])->name('profil');
Route::get('/satwa', [SatwaController::class,'index'])->name('satwa');
Route::get('/peta', [PetaController::class, 'index'])->name('peta');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::get('/tiket', [TiketController::class, 'index'])->name('tiket');


Auth::routes();

// Dashboard Route
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/pesanan',[PesananController::class, 'index'])->name('pesanan');


