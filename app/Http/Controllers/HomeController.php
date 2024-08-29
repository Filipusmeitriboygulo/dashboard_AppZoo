<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\Pesanan;
use Carbon\Carbon;
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

        // Tentukan rentang tanggal
        $startDate =
            Carbon::now()->startOfWeek(); // Ganti dengan tanggal mulai yang diinginkan
        $endDate =
            Carbon::now()->endOfWeek();   // Ganti dengan tanggal akhir yang diinginkan

        // Buat array untuk semua tanggal dalam rentang
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Ambil data dari database
        $pendapatanPerHari = Pesanan::with('pembeli')
            ->selectRaw('DATE(pesanans.created_at) as tanggal, SUM(pesanans.harga * pesanans.jumlah_tiket) as total_pemasukan')
            ->join('pembelis', 'pesanans.id', '=', 'pembelis.id_pesanan')
            ->groupBy('tanggal')
            ->get();

        // Convert data to associative array with date as key
        $dataMap = $pendapatanPerHari->mapWithKeys(function ($item) {
            return [$item->tanggal => $item->total_pemasukan];
        })->toArray();

        // Generate final data with all dates
        $dataEarnings = collect($dates)->map(function ($date) use ($dataMap) {
            return [
                'date' => Carbon::parse($date)->format('d/m/Y'),
                'earnings' => $dataMap[$date] ?? 0, // Use 0 if no data for this date
            ];
        });

        // Prepare categories and earnings for the chart
        $categories = $dataEarnings->pluck('date')->toArray();
        $earnings = $dataEarnings->pluck('earnings')->toArray();



        // Tanggal
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Array untuk bulan
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Array untuk tahun, contoh 5 tahun ke depan dan 5 tahun ke belakang
        $years = range($currentYear, $currentYear + 2);


        return view('auth.dashboard', compact(
            'totalPesanan',
            'totalPembeli',
            'totalPendapatanFormatted',
            'totalJumlahTiket',
            'recentTransactions',
            'categories',
            'earnings',
            'months',
            'years',
            'currentYear',
            'currentMonth'

        ));
    }
}
