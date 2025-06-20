<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\UploadLog;
use App\Models\ToeflScoreEntry;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str; // Tambahkan ini di bagian atas controller
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class UploadLogController extends Controller
{
    //
    public function index()
    {
        $files = UploadLog::latest()->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'file_name' => $log->file_name,
                'file_path' => Storage::url($log->file_name),
                'uploaded_at' => Carbon::parse($log->waktu_upload)->format('Y-m-d H:i'),
            ];
        });

        $departments = \App\Models\Department::with('studyPrograms.classes')->get();

        return view('admin.data-upload.index', compact('files', 'departments'));
    }

    // public function upload(Request $request)
    // {
    //     $request->validate([
    //         'fileExcel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    //     ]);

    //     $user = Auth::user();
    //     $file = $request->file('fileExcel');
    //     $filename = 'toefl_' . time() . '.' . $file->getClientOriginalExtension();
    //     $path = $file->storeAs('uploads/toefl', $filename, 'public');

    //     $log = UploadLog::create([
    //         'user_id' => $user->id,
    //         'file_name' => $filename,
    //         'cakupan' => $request->scope ?? 'campus',
    //         'unit_nama' => $request->scope_id ?? 'ALL',
    //         'waktu_upload' => now(),
    //         'status_klasterisasi' => 'pending',
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'File uploaded successfully',
    //         'batch_id' => $log->id,
    //         'file_path' => $path,
    //         'file_type' => $file->getClientOriginalExtension(),
    //     ]);
    // }

    public function upload(Request $request)
    {
        // Validasi request
        $validatedData = $request->validate([
            'fileExcel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'scope' => 'required|string',
            'scope_id' => 'nullable|string'
        ]);

        try {
            $user = Auth::user();
            $file = $request->file('fileExcel');

            // Generate unique filename dengan user ID untuk menghindari konflik
            $filename = 'toefl_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

            // Store file
            $path = $file->storeAs('uploads/toefl', $filename, 'public');

            // Create upload log
            $log = UploadLog::create([
                'user_id' => $user->id,
                'file_name' => $filename,
                'file_path' => $path,
                'cakupan' => $request->scope,
                'unit_nama' => $request->scope_id ?? 'ALL',
                'waktu_upload' => now(),
                'status_klasterisasi' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'batch_id' => $log->id,
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
    // Tambahkan method formatBytes dalam controller
    // protected function formatBytes($bytes, $precision = 2)
    // {
    //     $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    //     $bytes = max($bytes, 0);
    //     $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    //     $pow = min($pow, count($units) - 1);
    //     $bytes /= pow(1024, $pow);
    //     return round($bytes, $precision) . ' ' . $units[$pow];
    // }

    public function process(Request $request)
    {
        // Simulasi: Parsing file dan menyimpan entri TOEFL
        // (biasanya dipanggil Flask untuk analisis FCM)

        $uploadId = $request->batch_id;
        $scope = $request->scope;
        $data = [
            // data dummy jika preview/parse ingin ditampilkan dulu
        ];

        return response()->json([
            'result' => [
                'total_rows' => 30,
                'success_count' => 30,
                'error_count' => 0,
                'errors' => [],
            ]
        ]);
    }

    public function showScores($uploadId)
    {
        $entries = ToeflScoreEntry::where('upload_id', $uploadId)->get();
        return view('admin.score', compact('entries'));
    }
}
