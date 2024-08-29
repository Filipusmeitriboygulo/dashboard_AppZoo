<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Pesanan;
use Illuminate\Support\Str;

class PembeliController extends Controller
{

    // Untuk Backend
    public function index($id)
    {
        $pembeli = Pembeli::findorFail($id);
        return view('auth.pembeli', ['pembeli' => $pembeli]);
    }

    // public function DetailOrder($id)
    // {

    //     $pembeli = Pembeli::findorFail($id);
    //     return view('tiket.order', compact('pembeli'));
    // }


    // Untuk Front End
    public function input(Request $request)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-OntdCcvk7QyWMVYlJRhf4JpH');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        
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
        
        $pembeli = Pembeli::findOrFail($pembeliId);
        $pesanan = $pembeli->pesanan;
        $tanggal = $pesanan->tanggal;
        $jumlah = $pesanan->jumlah_tiket;
        $nama = $pembeli->nama;
        $email = $pembeli->email;
        $kontak = $pembeli->nohp;
        $harga = $pesanan->harga;
        $pesananId = $pesanan->id;

        // Siapkan parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => Str::uuid(),
                'gross_amount' => $harga * $jumlah,
            ],
            'customer_details' => [
                'first_name' => $nama,
                'email' => $email,
                'phone' => $kontak,
            ],
            'item_details' => [
                [
                    'id' => $pesanan->id,
                    'price' => $harga,
                    'quantity' => $jumlah,
                    'name' => 'Tiket Event - ' . $tanggal
                ]
            ],
            'callbacks' => [
                'finish' => route('midtrans.callback', ['pesananId' => $pesananId, 'pembeliId' => $pembeliId]),
                'error' => route('midtrans.callback')
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $paymentUrl = \Midtrans\Snap::createTransaction($params);

        $pembeli->paymentUrl = $paymentUrl;

        // Redirect ke halaman order dengan pesananId dan pembeliId sebagai parameter
        return view('tiket.order', compact('pembeli', 'pembeliId', 'pesananId', 'snapToken'));
    }
}
    