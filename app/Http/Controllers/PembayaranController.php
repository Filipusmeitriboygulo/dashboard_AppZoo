<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Exception;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        // Ambil data pembeli dan pesanan
        $pembeli = Pembeli::findorFail($request->pembeli_id);
        $tanggal = $pembeli->pesanan->tanggal;
        $jumlah = $pembeli->pesanan->jumlah_tiket;
        $nama = $pembeli->nama;
        $email = $pembeli->email;
        $kontak = $pembeli->nohp;
        $harga = 60000;

        // Siapkan parameter untuk Midtrans
        $params = array(
            'transaction_details' => array(
                'order_id' => $pembeli->pesanan->id,
                'gross_amount' => $harga * $jumlah,
            ),
            'customer_details' => array(
                'first_name' => $nama,
                'email' => $email,
                'phone' => $kontak,
            ),
            'item_details' => array(
                array(
                    'id' => 'a1',
                    'price' => $harga,
                    'quantity' => $jumlah,
                    'name' => $tanggal
                )
            ),
            "callbacks" => array(
                "finish" => route('tiket')
            )
        );

        try {
            // Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            if ($snapToken) {
                return response()->json(['token' => $snapToken]);
            } else {
                throw new \Exception('Failed to generate Snap Token');
            }
            // Kirim data ke view termasuk token
            return redirect()->route('order')
                ->with('tanggal', $tanggal)
                ->with('jumlah', $jumlah)
                ->with('nama', $nama)
                ->with('email', $email)
                ->with('kontak', $kontak)
                ->with('snapToken', $snapToken);

        } catch (Exception $e) {
            // Tangani error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    

}
