<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeritaController extends Controller
{
    //
    public function index() {
        return view('berita.berita');
    }

    public function detailBerita() {
        return view('berita.detail_berita');
    }
}
