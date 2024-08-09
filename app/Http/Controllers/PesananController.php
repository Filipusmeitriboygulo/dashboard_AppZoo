<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\Pesanan;
use Illuminate\Http\Request;



class PesananController extends Controller
{
    //
    public function index()
    {
        $pesanan = Pembeli::all();
        return view('auth.pesanan', compact('pesanan'));
    }

    public function input(Request $request)
    {
        // Validasi data yang diterima dari request
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0'
        ]);

        // Membuat objek Pesanan baru
        $pesanan = new Pesanan();
        $pesanan->tanggal_pesanan = $validated['tanggal'];
        $pesanan->jumlah_tiket = $validated['jumlah'];
        $pesanan->harga = $validated['harga'];
        $pesanan->save();

        // Mengambil ID pesanan yang baru saja disimpan
        $pesananId = $pesanan->id;

        // Redirect ke halaman detail_tiket dengan pesananId sebagai parameter
        return redirect()->route('detail_tiket', ['pesananId' => $pesananId]);
    }
}
