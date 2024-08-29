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


        // Tanggal Berdasarkan, Minggu, Bulan, dan Tahun
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentWeek = Carbon::now()->week;

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

        // Array untuk minggu dalam setahun
        $weeks = [];
        for ($i = 1; $i <= Carbon::now()->weeksInYear; $i++) {
            $weeks[$i] = "Minggu ke-$i";
        }

        // Dapatkan pilihan dari request atau gunakan nilai default
        $selectedMonth = request()->input('month', $currentMonth);
        $selectedWeek = request()->input('week', $currentWeek);
        $selectedYear = request()->input('year', $currentYear);
        $selectedPeriod = request()->input('period',
            'week'
        ); // Default ke 'week'

        // Tentukan rentang tanggal berdasarkan pilihan periode waktu
        switch ($selectedPeriod) {
            case 'month':
                $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                break;

            case 'year':
                $startDate = Carbon::create($selectedYear, 1, 1)->startOfYear();
                $endDate = $startDate->copy()->endOfYear();
                break;

            case 'week':
            default:
                $startDate = Carbon::now()->setISODate($selectedYear, $selectedWeek)->startOfWeek();
                $endDate = $startDate->copy()->endOfWeek();
                break;
        }

        // Buat array untuk semua tanggal dalam rentang
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Dapatkan data pendapatan per hari untuk rentang tanggal yang dipilih
        $pendapatanPerHari = Pesanan::with('pembeli')
        ->whereBetween('pesanans.created_at', [$startDate, $endDate])
        ->selectRaw('DATE(pesanans.created_at) as tanggal, SUM(pesanans.harga * pesanans.jumlah_tiket) as total_pemasukan, SUM(pesanans.jumlah_tiket) as jumlah_tiket')
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
                'earnings' => $dataMap[$date] ?? 0, // Gunakan 0 jika tidak ada data untuk tanggal ini
            ];
        });

        // Siapkan categories dan earnings untuk chart
        $categories = $dataEarnings->pluck('date')->toArray();
        $earnings = $dataEarnings->pluck('earnings')->toArray();

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
            'weeks',
            'currentYear',
            'currentMonth',
            'currentWeek',
            'selectedPeriod',
            'selectedMonth',
            'selectedWeek',
            'selectedYear'
        ));
    }
}
