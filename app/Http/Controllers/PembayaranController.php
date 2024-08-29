<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use App\Mail\ContinuePayment;
use App\Mail\PaymentStatusEmail;
use App\Mail\TicketMail;
use App\Models\Pembayaran;
use App\Models\Pembeli;
use App\Models\Pesanan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Midtrans\Config;

class PembayaranController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Konfigurasi Midtrans
    //     \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-OntdCcvk7QyWMVYlJRhf4JpH');
    //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    //     \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    //     // Set sanitization on (default)
    //     \Midtrans\Config::$isSanitized = true;
    //     // Set 3DS transaction for credit card to truefalse
    //     \Midtrans\Config::$is3ds = true;
    //     Log::info('Server Key: ' . env('MIDTRANS_SERVER_KEY'));


    //     // Ambil data pembeli dan pesanan
    //     $pembeli = Pembeli::findorFail($request->pembeli_id);

    //     $pesanan = $pembeli->pesanan;
    //     $tanggal = $pembeli->pesanan->tanggal;
    //     $jumlah = $pembeli->pesanan->jumlah_tiket;
    //     $nama = $pembeli->nama;
    //     $email = $pembeli->email;
    //     $kontak = $pembeli->nohp;
    //     $harga = 60000;
    //     $pesananId = $pesanan->id;

    //     // Siapkan parameter untuk Midtrans
    //     $params = array(
    //         'transaction_details' => array(
    //             'order_id' => mt_rand(100000, 999999),
    //             'gross_amount' => $harga * $jumlah,
    //         ),
    //         'customer_details' => array(
    //             'first_name' => $nama,
    //             'email' => $email,
    //             'phone' => $kontak,
    //         ),
    //         'item_details' => array(
    //             array(
    //                 'id' => $pesanan->id,
    //                 'price' => $harga,
    //                 'quantity' => $jumlah,
    //                 'name' => 'Tiket Event - ' . $tanggal
    //             )
    //         ),
    //         "callbacks" => array(
    //             "finish" => route('midtrans.callback', ['pesananId' => $pesananId]),
    //             "error" => route('midtrans.callback')
    //         )
    //     );
    //     try {
    //         // Get payment Url
    //         $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

    //         if (!$paymentUrl) {
    //             Log::error('Payment URL is empty', ['params' => $params]);
    //             return response()->json(['error' => 'Payment URL is empty'], 500);
    //         }

    //         // Kirim email setelah menerima notifikasi dari Midtrans
    //         Mail::to($pembeli->email)->send(new ContinuePayment($pembeli, $paymentUrl));

    //         // return redirect($paymentUrl);
    //     } catch (\Exception $e) {
    //         Log::error("Error during checkout", ['error' => $e->getMessage()]);
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function callback(Request $request)
    {
        // Merubah json menjadi array
        $json = json_decode($request->get('json'));
        
        // Ekstrak finish_redirect_url
        $finishRedirectUrl = $json->finish_redirect_url;

        // Parse URL to get query parameters
        $queryParams = [];
        parse_str(parse_url($finishRedirectUrl, PHP_URL_QUERY), $queryParams);

        // Get pesananId
        $pesananId = isset($queryParams['pesananId']) ? $queryParams['pesananId'] : null;
        $pembeliId = isset($queryParams['pembeliId']) ? $queryParams['pembeliId'] : null;

        // Menyimpan data pembayaran ke database
        $pembayaran = new Pembayaran();
        $pembayaran->status_pembayaran = $json->transaction_status;
        $pembayaran->total_harga = $json->gross_amount;
        $pembayaran->metode_pembayaran = $json->payment_type;
        $pembayaran->transaction_id = $json->transaction_id;
        $pembayaran->id_pesanan = $pesananId;
        $pembayaran->save;

        
        // Generate tiket after payment
        $pembeli = Pembeli::findOrFail($pembeliId);
        $this->generateAndSendTicket($pembeli);
        
        // Mengubah statu menjadi paid
        $pembeli->status = 'Paid';
        $pembeli->save();
        
        dd($pembeli);

        return redirect(route('index'));
    }

    public function sendEmail(Request $request)
    {
        // Logika untuk mengirim email
        $pembeli = Pembeli::find($request->pembeli_id);
        $paymentUrl = $request->paymentUrl;

        Mail::to($pembeli->email)->send(new ContinuePayment($pembeli, $paymentUrl));

        return response()->json(['success' => true]);
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
