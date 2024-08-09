<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Pesanan;


class PembeliController extends Controller
{

    // Untuk Backend
    public function index($id)
    {
        $pembeli = Pembeli::findorFail($id);
        return view('auth.pembeli', ['pembeli' => $pembeli]);
    }

    // public function index($id)
    // {

    //     $pembeli = Pembeli::findorFail($id);
    //     return view('tiket.order', compact('pembeli'));
    // }


    // Untuk Froen End
    public function input(Request $request)
    {
        // Validasi data yang diterima dari request
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nohp' => 'required|string|max:15',
            'id_pesanan' => 'required|integer'
        ]);

        // Membuat objek Pembeli baru
        $pembeli = new Pembeli();
        $pembeli->nama = $validated['nama'];
        $pembeli->email = $validated['email'];
        $pembeli->nohp = $validated['nohp'];
        $pembeli->id_pesanan = $validated['id_pesanan'];
        $pembeli->save();

        // Mengambil ID pembeli yang baru saja disimpan
        $pembeliId = $pembeli->id;
        $pesananId = $pembeli->id_pesanan;

        // Redirect ke halaman order dengan pesananId dan pembeliId sebagai parameter
        return redirect()->route('order', ['pembeliId' => $pembeliId, 'pesananId' => $pesananId]);
    }
}
