<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index() { {
            // Menghitung Total Pendapatan
            $totalPendapatan = Pesanan::sum(DB::raw('jumlah_tiket * harga'));

            $totalPendapatanFormatted = 'Rp. ' . number_format($totalPendapatan, 0, ',', '.');
            $laporanPenjualan = Pesanan::selectRaw('DATE(tanggal_pesanan) as tanggal, COUNT(*) as jumlah_transaksi, $totalPendapatan')
            ->groupBy('tanggal')
                ->get();

            return view('auth.laporan', compact('laporanPenjualan'));
        }
        
    }


}
