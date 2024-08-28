<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Menghitung total Pesanan
        $totalPesanan = Pesanan::count();

        // Menghitung total Pembeli
        $totalPembeli = Pembeli::count();

        // Menghitung Total Pendapatan
        $totalPendapatan = Pesanan::sum(DB::raw('jumlah_tiket * harga'));

        $totalPendapatanFormatted = 'Rp. ' . number_format($totalPendapatan, 0, ',', '.');

        // Mengitung Jumlah tiket Terjual
        $totalJumlahTiket = Pesanan::sum('jumlah_tiket');

        // Mendapatkan data pesanan terbaru dengan relasi ke pembeli
        $recentTransactions = Pembeli::with('pesanan')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();


        return view('auth.dashboard', compact('totalPesanan', 'totalPembeli', 'totalPendapatanFormatted','totalJumlahTiket', 'recentTransactions'));

    }

}
