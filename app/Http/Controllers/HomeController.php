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

        $pendapatanPerHari = Pesanan::with('pembeli')
        ->selectRaw('DATE(pesanans.created_at) as tanggal, SUM(pesanans.harga * pesanans.jumlah_tiket) as total_pemasukan, SUM(pesanans.jumlah_tiket) as jumlah_tiket')
        ->join('pembelis', 'pesanans.id', '=', 'pembelis.id_pesanan')
        ->groupBy('tanggal')
        ->get();

        $dataEarnings = $pendapatanPerHari->map(function ($item) {
            return [
                'date' => \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y'),
                'earnings' => $item->total_pemasukan,
            ];
        });

        $categories = $dataEarnings->pluck('date')->toArray();
        $earnings = $dataEarnings->pluck('earnings')->toArray();
        // dd($categories,$earnings);

        return view('auth.dashboard', compact(
            'totalPesanan',
            'totalPembeli',
            'totalPendapatanFormatted',
            'totalJumlahTiket',
            'recentTransactions',
            'categories',
            'earnings'
        ));

    }
}
