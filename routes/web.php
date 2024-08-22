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
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\PembeliController;


// Route::get('/', function () {
//     return view('auth.login');
// });


// Frontend Route 


Route::get('/', [ZooController::class, 'index'])->name('index');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::get('/satwa', [SatwaController::class, 'index'])->name('satwa');
Route::get('/peta', [PetaController::class, 'index'])->name('peta');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');

// Tiket

Route::get('/tiket', [TiketController::class, 'index'])->name('tiket');
Route::get('/midtrans/callback', [PembayaranController::class, 'callback'])->name('midtrans.callback');
// Route::post('/detail-tiket/{pesananId}', [TiketController::class, 'detail_tiket'])->name('detail_tiket');
// Route::post('/tiket', [TiketController::class,'index'])->name('tiket');
Route::get('/detail-tiket/{pesananId}', [TiketController::class, 'DetailTiket'])->name('detail_tiket');



Route::get('/order/{pembeliId}/{pesananId}', [TiketController::class, 'order'])->name('order');
// Route::get('/order/{pembeliId}', [TiketController::class, 'showDetailOrder'])->name('show_detail_order');


// Pesanan  
Route::post('/pesanan/input', [PesananController::class, 'input'])->name('pesanan.input');
Route::post('/pembeli/input', [PembeliController::class, 'input'])->name('pembeli.input');

// Pembayaran
// Route::post('/payment-form', [PembayaranController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');



Auth::routes();

// Dashboard Route
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'index'])->name('home');
    Route::get('/admin/pesanan', [PesananController::class, 'index'])->name('pesanan');
    Route::get('/admin/pembeli/{id}', [PembeliController::class, 'index'])->name('pembeli');
});
