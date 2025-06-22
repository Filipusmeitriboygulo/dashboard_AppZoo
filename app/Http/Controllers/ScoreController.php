<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ScoreImport;
use App\Models\UploadLog;
use Maatwebsite\Excel\Facades\Excel;

class ScoreController extends Controller
{
    public function upload(Request $request)
    {
        dd($request->file("file"));
        // Validasi file
        $request->validate([
            'fileExcel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);
        // dd($request->file(''));

        // Simpan file ke public/uploads/toefl
        $file = $request->file('fileExcel');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/toefl');
        $file->move($destinationPath, $fileName);

        // Simpan data file ke tabel uploadLOg
        $toeflFile = UploadLog::create([
            'file_name' => $fileName,
            // 'file_path' => 'data/' . $fileName,
            // 'uploaded_at' => now(),
            'cakupan' =>$cakupan,
            'unit_nama'=>$unit_nama,
            'waktu_upload'=>$waktu_upload
        ]);

        // Import data ke tabel score dengan relasi toefl_file_id
        Excel::import(new ScoreImport($toeflFile->id), $destinationPath . '/' . $fileName);

        return back()->with('success', 'File dan data berhasil diupload dan diimport ke database!');
    }
}
