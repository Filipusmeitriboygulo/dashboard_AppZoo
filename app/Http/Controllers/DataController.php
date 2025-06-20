<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FileUpload;


class DataController extends Controller
{
    //
    /**
     * Show the file upload form.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('home');
    }

    /**
     * Handle file upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function upload(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'files.*' => 'required|file|mimes:csv,txt|max:5120' // Max 5MB
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first()
    //         ]);
    //     }

    //     try {
    //         $uploadedFiles = [];

    //         $holla = $request->file('files');
    //         dd($holla);
    //         dd($holla->getClientOriginalName());

    //         foreach ($request->file('files') as $file) {
    //             // Simpan file
    //             $filename = time() . '_' . $file->getClientOriginalName();
    //             $path = $file->storeAs('csv_uploads', $filename);

    //             dd($filename);

    //             // Proses file CSV
    //             $csvData = array_map('str_getcsv', file($file->getRealPath()));
    //             $header = array_shift($csvData);

    //             // Simpan ke database
    //             $csvRecord = FileUpload::create([
    //                 'filename' => $filename,
    //                 'path' => $path,
    //                 'header' => $header,
    //                 'data' => $csvData
    //             ]);

    //             $uploadedFiles[] = $csvRecord;
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'File berhasil diupload!',
    //             'files' => $uploadedFiles
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //         ]);
    //     }
    // }

    public function upload(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt,xlsx|max:10240' // Hanya terima 1 file (max 5MB)
        ], [
            'file.required' => 'File harus diisi',
            'file.file' => 'Input harus berupa file',
            'file.mimes' => 'Hanya format CSV/TXT yang diperbolehkan',
            'file.max' => 'Ukuran file maksimal 10MB'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() // Pesan error spesifik
            ], 422); // HTTP 422: Unprocessable Entity
        }

        try {
            $file = $request->file('file'); // Ambil file

            // Format nama file: originalname_TIMESTAMP.extension
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = $originalName . '_' . time() . '.' . $extension;


            // Simpan file
            $path = $file->storeAs('csv_uploads', $filename);

            // Proses CSV
            $csvData = array_map('str_getcsv', file($file->getRealPath()));
            $header = array_shift($csvData);

            // Simpan ke database
            $csvRecord = FileUpload::create([
                'name' => $filename,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload!',
                'data' => $csvRecord
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500); // HTTP 500: Internal Server Error
        }
    }
}
