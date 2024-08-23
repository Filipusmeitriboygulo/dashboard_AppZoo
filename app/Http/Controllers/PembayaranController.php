<?php

namespace App\Http\Controllers;

use App\Mail\ContinuePayment;
use App\Mail\PaymentStatusEmail;
use App\Models\Pembayaran;
use App\Models\Pembeli;
use App\Models\Pesanan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
                "finish" => route('midtrans.callback'),
                "error" => route('midtrans.callback')
            )
        );
        try {
            // Get payment Url
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

            if (!$paymentUrl) {
                Log::error('Payment URL is empty', ['params' => $params]);
                return response()->json(['error' => 'Payment URL is empty'], 500);
            }
            // Lakukan validasi dan update status transaksi di database
    
            // Kirim email setelah menerima notifikasi dari Midtrans
            Mail::to($pembeli->email)->send(new ContinuePayment($pembeli, $paymentUrl));

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error("Error during checkout", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Method Callback
    public function callback(Request $request)
{
    $order_id = $request->input('order_id');

    $pembeli = Pembeli::where('id_pesanan', $order_id)->first();

    if ($pembeli) {
        $status = '';
        if ($request->transaction_status === 'expire') {
            $pembeli->status = 'expire';
            $status = 'expire';
        } elseif ($request->transaction_status === 'settlement') {
            $pembeli->status = 'paid';
            $status = 'paid';
        }
        $pembeli->save();

        // Kirim email status pembayaran
        // Mail::to($pembeli->email)->send(new PaymentStatusEmail($pembeli, $status));
    }

    return redirect()->route('index');
}

}
