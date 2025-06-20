<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToeflFile;
use App\Imports\ScoreImport;
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

        // Simpan file ke public/data
        $file = $request->file('fileExcel');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('data');
        $file->move($destinationPath, $fileName);

        // Simpan data file ke tabel toefl_files
        $toeflFile = ToeflFile::create([
            'file_name' => $fileName,
            'file_path' => 'data/' . $fileName,
            'uploaded_at' => now(),
        ]);

        // Import data ke tabel score dengan relasi toefl_file_id
        Excel::import(new ScoreImport($toeflFile->id), $destinationPath . '/' . $fileName);

        return back()->with('success', 'File dan data berhasil diupload dan diimport ke database!');
    }
}
