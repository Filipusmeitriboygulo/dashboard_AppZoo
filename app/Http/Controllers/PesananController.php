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

    // public function store(){
    //     $ticket = Ticket::findOrFail($ticketId);
    //     $concert = $ticket->concert;
    //     $order = null;

    //     DB::transaction(function () use ($request, $ticket, &$order) {
    //         // Create Order
    //         $order = Order::create([
    //             'email' => $request->input('email'),
    //             'firstname' => $request->input('first_name'),
    //             'lastname' => $request->input('last_name'),
    //             'phone' => $request->input('phone'),
    //             'total_price' => $ticket->price,
    //             'status' => 'pending',
    //         ]);

    //         // Create Order Item
    //         OrderItem::create([
    //             'order_id' => $order->id,
    //             'ticket_id' => $ticket->id,
    //             'quantity' => 1,
    //             'price' => $ticket->price,
    //         ]);
    //     });

    //     if ($order) {
    //         return $this->checkout($order, $request);
    //     } else {
    //         return response()->json(['error' => 'Order creation failed'], 500);
    //     }
    // }
}
