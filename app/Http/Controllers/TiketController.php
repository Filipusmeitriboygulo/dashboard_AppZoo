<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Illuminate\Http\Request;


class TiketController extends Controller
{
    
    public function index()
    {
        return view('tiket.tiket');
    }


    public function DetailTiket($pesananId)
    {
        return view('tiket.detail_tiket', compact('pesananId'));
    }

    public function order($pembeliId, $pesananId)

    {
        $pembeli = Pembeli::findorFail($pembeliId);
        return view('tiket.order', compact('pesananId', 'pembeliId','pembeli'));
    }

}
