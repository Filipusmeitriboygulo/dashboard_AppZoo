<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-OntdCcvk7QyWMVYlJRhf4JpH');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to truefalse
        \Midtrans\Config::$is3ds = true;
        Log::info('Server Key: ' . env('MIDTRANS_SERVER_KEY'));


        // Ambil data pembeli dan pesanan
        $pembeli = Pembeli::findorFail($request->pembeli_id);
        $pesanan = $pembeli->pesanan;
        $tanggal = $pembeli->pesanan->tanggal;
        $jumlah = $pembeli->pesanan->jumlah_tiket;
        $nama = $pembeli->nama;
        $email = $pembeli->email;
        $kontak = $pembeli->nohp;
        $harga = 60000;

        // Siapkan parameter untuk Midtrans
        $params = array(
            'transaction_details' => array(
                'order_id' => $pesanan->id,
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
                    'name' => 'Tiket Event - ' . $tanggal
                )
            ),
            "callbacks" => array(
                "finish" => route('tiket')
            )
        );
        try {
            // Get payment Url
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

            if (!$paymentUrl) {
                Log::error('Payment URL is empty', ['params' => $params]);
                return response()->json(['error' => 'Payment URL is empty'], 500);
            }

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error("Error during checkout", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function callback(Request $request) {
        // Validasi data masuk 
        $request->validate([
            'order_id' =>'required|string',
            'status_code' =>'required|string',
            'grosss_amount' =>'required|numeric',
            'signature_key' =>'required|string',
            'transaction_status' =>'required|string',
        ]);

        // A
        $serverKey = 
    }   
}
