<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class KlasterisasiController extends Controller
{

    public function  __construct()
    {
        $this->middleware('auth');
    }



    public function index()
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

        $file = FileUpload::findOrFail($request->file_upload_id);
        $filePath = storage_path('app/csv_uploads/' . $file->name);

        try {
            $response = Http::timeout(60)->attach(
                'file',
                file_get_contents($filePath),
                $file->name
            )->post('http://127.0.0.1:5000/cluster');

            if ($response->successful()) {
                $result = $response->json();

                // Validasi response dari Flask API
                if (!isset($result['status']) || $result['status'] !== 'success') {
                    throw new \Exception($result['message'] ?? 'Invalid response from Flask API');
                }

                return redirect()->route('klasterisasi.result')->with([
                    'success' => 'Analisis klasterisasi berhasil',
                    'analysis_data' => $result['data'],
                    'file_info' => [
                        'name' => $file->name,
                        'uploaded_at' => $file->created_at->format('d M Y H:i')
                    ]
                ]);
            }

            throw new \Exception('Flask API returned status: ' . $response->status());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Gagal melakukan analisis: ' . $e->getMessage()
            ]);
        }
    }


    public function result()
    {
        if (!session()->has('analysis_data')) {
            return redirect()->route('klasterisasi.index')
                ->withErrors(['error' => 'Tidak ada data analisis yang ditemukan']);
        }

        $analysisData = session('analysis_data');

        // Validate and prepare chart data
        $chartData = [
            'labels' => ['Pemula', 'Menengah', 'Mahir'], // Fixed order
            'counts' => [
                $analysisData['cluster_counts']['Pemula'] ?? 0,
                $analysisData['cluster_counts']['Menengah'] ?? 0,
                $analysisData['cluster_counts']['Mahir'] ?? 0
            ],
            'centroids' => [
                'Pemula' => array_map(function ($val) {
                    return number_format($val, 2);
                }, $analysisData['centroids']['Pemula']),
                'Menengah' => array_map(function ($val) {
                    return number_format($val, 2);
                }, $analysisData['centroids']['Menengah']),
                'Mahir' => array_map(function ($val) {
                    return number_format($val, 2);
                }, $analysisData['centroids']['Mahir'])
            ],
            'colors' => [
                'Pemula' => '#FFCE56',
                'Menengah' => '#36A2EB',
                'Mahir' => '#4BC0C0'
            ]
        ];

        return view('klasterisasi.result', [
            'analysis_data' => $analysisData,
            'file_info' => session('file_info'),
            'success' => session('success'),
            'chart_data' => $chartData
        ]);
    }
}
