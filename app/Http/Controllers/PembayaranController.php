<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Exception;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    //
    public function index (Request $request) {
        $pembeli = Pembeli::create($request->all());
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $tanggal = $pembeli->pesanan->tanggal;
        $jumlah = $pembeli->pesanan->jumlah_tiket;
        $nama = $pembeli->nama;
        $email =$pembeli->email;
        $kontak =$pembeli->nohp;
        $harga = 60000;

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
                "finish" => "http://127.0.0.1:8000/tiket"
            )
        );

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            echo $snapToken;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
}
