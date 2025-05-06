<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataController;
use App\Mail\PaymentStatusEmail;
use Illuminate\Support\Facades\Mail;



Route::get('/', [HomeController::class, 'index'])->name('index');

Auth::routes();

// Dashboard Route
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/home', [DataController::class, 'index'])->name('home');
    Route::post('/admin/data/upload', [DataController::class, 'upload'])->name('data.upload');
});
