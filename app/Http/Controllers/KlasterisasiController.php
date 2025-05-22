<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class KlasterisasiController extends Controller
{

    public function index()
    {
        return view('auth.klasterisasi');
    }

    public function proses()
    {
        $file_uploads = FileUpload::all();
        // dd($file_uploads);
        return view('klasterisasi.index')->with('file_uploads', $file_uploads);
    }
    public function analyze(Request $request)
    {
        $request->validate([
            'file_upload_id' => 'required|exists:file_uploads,id'
        ]);

        // For debugging purposes, just return the file info
        $file = FileUpload::find($request->file_upload_id);
        $detailFile = $file->toArray();
        // dd($detailFile);
        $user = Auth::user();
        $nameFile = $detailFile['name'];
        // You can add your actual clustering logic here later
        $debugInfo = [
            'name' => $user->name,
            'name_file' => $nameFile,
            'date' => date(now()->toDateTimeString()),
        ];
        // dd($debugInfo);

        return redirect()->route('klasterisasi')
            ->with('debug', $debugInfo);
    }   
}
