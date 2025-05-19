<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;

use Illuminate\Http\Request;

class KlasterisasiController extends Controller
{

    public function index()
    {
        return view('auth.klasterisasi');
    }

    public function proses() 
    {
        $file_uploads = FileUpload::all();
        return view('auth.klasterisasi')->with('file_uploads', $file_uploads);
    }
}
