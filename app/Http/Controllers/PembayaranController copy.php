<?php

namespace App\Http\Controllers;

use App\Mail\ContinuePayment;
use App\Mail\PaymentStatusEmail;
use App\Mail\TicketMail;
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
        $pesananId = $pesanan->id;



        // Siapkan parameter untuk Midtrans
        $params = array(
            'transaction_details' => array(
                'order_id' => mt_rand(100000, 999999),
                'gross_amount' => $harga * $jumlah,
            ),
            'customer_details' => array(
                'first_name' => $nama,
                'email' => $email,
                'phone' => $kontak,
            ),
            'item_details' => array(
                array(
                    'id' => $pesanan->id,
                    'price' => $harga,
                    'quantity' => $jumlah,
                    'name' => 'Tiket Event - ' . $tanggal
                )
            ),
            "callbacks" => array(
                "finish" => route('midtrans.callback', ['pesananId' => $pesananId]),
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
        // Validasi data masuk
        $request->validate([
            'order_id' => 'required|string',
            'status_code' => 'required|string',
            'gross_amount' => 'required|numeric',
            'signature_key' => 'required|string',
            'transaction_status' => 'required|string',
        ]);

        // Ambil server key dari konfigurasi
        $serverKey = config('midtrans.server_key');

        // Buat hash yang benar
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // Periksa apakah signature key yang diterima sama dengan hash yang dihasilkan
        if ($hashed === $request->signature_key) {
            // Periksa status transaksi
            if ($request->transaction_status === 'capture' || $request->transaction_status === 'settlement') {
                // Cari order berdasarkan order_id
                $order = Order::find($request->order_id);

                if ($order) {
                    // Perbarui status order menjadi 'paid'
                    $order->update(['status' => 'paid']);

                    // Simpan informasi pembayaran ke tabel payments
                    $payment = new Payment();
                    $payment->order_id = $order->id;
                    $payment->amount = $request->gross_amount;
                    $payment->payment_method = $request->payment_type ?? 'unknown'; // Menyimpan metode pembayaran
                    $payment->payment_status = $request->transaction_status;
                    $payment->transaction_id = $request->transaction_id ?? null; // Menyimpan ID transaksi jika ada
                    $payment->save();
                    
                    // Generate and send ticket
                    $this->generateAndSendTicket($order);
                } else {
                    // Log jika order tidak ditemukan
                    Log::error('Order not found for order_id: ' . $request->order_id);
                }
            } elseif ($request->transaction_status === 'cancel' || $request->transaction_status === 'expire') {
                // Jika transaksi dibatalkan atau kedaluwarsa
                $order = Order::find($request->order_id);
                if ($order) {
                    $order->update(['status' => 'canceled']);
                }
            }
        } else {
            // Log jika hash tidak cocok
            Log::error('Signature key mismatch for order_id: ' . $request->order_id);
        }
    }


    protected function generateAndSendTicket(Pembeli $pembeli)
    {
        // Load view for the ticket
        // $pdf = PDF::loadView('tickets.ticket', compact('order'));

        // // Define file name and path
        // $fileName = 'ticket_' . $pembeli->id . '.pdf';
        // $filePath = storage_path('app/public/' . $fileName);

        // // Save the PDF to the storage
        // $pdf->save($filePath);

        // Send the ticket via email
        Mail::to($pembeli->email)->send(new TicketMail($pembeli));
    }
}
