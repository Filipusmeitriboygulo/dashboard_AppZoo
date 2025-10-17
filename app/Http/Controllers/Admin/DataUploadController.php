<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\StudyProgram;
use App\Models\ToeflScoreEntry;
use App\Models\UploadLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;


class DataUploadController extends Controller
{
    // protected $excelService;

    public function index()
    {
        $departments = Department::with('studyPrograms.classes')->get();
        // $recentUploads = $this->excelService->getRecentUploads();
        $files = UploadLog::all();

        return view('admin.data-upload.index', compact('departments', 'files'));
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileExcel' => 'required|file|mimes:csv,xlsx,xls|max:10240',
            'cakupan' => 'required|in:kampus,jurusan,prodi,kelas',
            'unit_nama' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('fileExcel');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = 'toefl_' . time() . '_' . str_replace(' ', '_', $originalName) . '.' . $extension;

            // Store file
            $path = $file->storeAs('uploads/toefl', $filename, 'public');

            // Create upload log
            $uploadLog = UploadLog::create([
                'user_id' => $request->user_id,
                'file_name' => $filename,
                'cakupan' => $request->cakupan,
                'unit_nama' => $request->unit_nama,
                'waktu_upload' => now(),
                'status_klasterisasi' => 'belum',
            ]);

            // Here you would typically process the Excel file and save the scores
            // $this->processExcelFile($path, $uploadLog->id);
            $this->process(storage_path('app/public/' . $path), $uploadLog->id);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload!',
                'data' => $uploadLog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    private function process($filePath, $uploadId)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Mulai dari baris 6 (indeks 5) karena header di baris 5
        for ($i = 5; $i < count($rows); $i++) {
            $row = $rows[$i];

            try {
                // Skip hanya jika benar-benar baris kosong (tidak ada data sama sekali)
                if (empty(array_filter($row, function ($value) {
                    return $value !== null && $value !== '';
                }))) {
                    Log::info("Skipping completely empty row at index $i");
                    continue;
                }

                // Mapping kolom dengan default value
                $nama = $row[1] ?? 'TANPA NAMA';
                $nim = isset($row[2]) ? (is_float($row[2]) ? sprintf('%.0f', $row[2]) : (string)$row[2]) : '';
                $jurProdiKls = $row[3] ?? '';

                // Handle pemisahan Jur/Prodi/Kelas dengan default value
                $jurusan = $prodi = $kelas = null;
                if (!empty($jurProdiKls)) {
                    $parts = array_map('trim', preg_split('/\s*\/\s*/', trim($jurProdiKls)));

                    $jurusan = $parts[0] ?? 'UMUM';
                    $prodi = $parts[1] ?? 'UMUM';
                    $kelas = $parts[2] ?? null;
                }

                // Pastikan tidak ada yang null untuk kolom required
                $jurusan = $jurusan ?? 'UMUM';
                $prodi = $prodi ?? 'UMUM';

                $department = Department::where(function ($query) use ($jurusan) {
                    $query->where('code', strtoupper(trim($jurusan)))
                        ->orWhere('name', 'LIKE', '%' . trim($jurusan) . '%');
                })->first();

                $studyProgram = StudyProgram::where(function ($query) use ($prodi) {
                    $query->where('code', strtoupper(trim($prodi)))
                        ->orWhere('name', 'LIKE', '%' . trim($prodi) . '%');
                })->first();

                if (!$department) {
                    Log::warning("Jurusan tidak ditemukan: $jurusan (baris $i)");
                }
                if (!$studyProgram) {
                    Log::warning("Prodi tidak ditemukan: $prodi (baris $i)");
                }

                // Simpan data (tidak ada validasi yang melewatkan baris)
                ToeflScoreEntry::create([
                    'upload_id' => $uploadId,
                    'nama' => $nama,
                    'nim' => $nim,
                    'jurusan' => $jurusan,
                    'prodi' => $prodi,
                    'kelas' => $kelas,
                    'listening' => is_numeric($row[7] ?? null) ? (int)$row[7] : null,
                    'structure' => is_numeric($row[8] ?? null) ? (int)$row[8] : null,
                    'reading' => is_numeric($row[9] ?? null) ? (int)$row[9] : null,
                    'total_score' => is_numeric($row[10] ?? null) ? (int)$row[10] : null,
                    'department_id' => optional($department)->id,
                    'study_program_id' => optional($studyProgram)->id,
                ]);
            } catch (\Exception $e) {
                Log::error("Gagal memproses baris $i: " . $e->getMessage());
                continue;
            }
        }
    }


    public function viewScores($fileId)
    {
        // dd($fileId);
        $file = UploadLog::findOrFail($fileId);
        $scores = ToeflScoreEntry::where('upload_id', $fileId)->get();

        return view('admin.score', compact('file', 'scores'));
    }

    public function deleteScores($fileId)
    {
        $file = UploadLog::findOrFail($fileId);
        ToeflScoreEntry::where('upload_id', $fileId)->delete();

        $filePath = 'uploads/toefl/' . $file->file_name;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $file->delete();
        return redirect()->route('home')
            ->with('success', 'Data score dan file berhasil dihapus.');
    }
}
