<?php

namespace App\Http\Controllers\KepalaUPA;

use App\Http\Controllers\Controller;
use App\Models\UploadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class KepalaUPAController extends Controller
{
    public function index()
    {
        $file_uploads = UploadLog::orderBy('created_at', 'desc')->get();
        return view('admin.klasterisasi.index', compact('file_uploads'));
    }
    public function result($upload_id)
    {
        // Ambil upload yang sudah diklasterisasi saja
        $upload = UploadLog::with(['clusterResults', 'clusterResults.toeflScoreEntry'])
            ->where('id', $upload_id)
            ->where('status_klasterisasi', 'sudah') // pastikan hanya yang "sudah"
            ->firstOrFail();

        // Hitung jumlah per cluster untuk chart
        $clusterCounts = [
            'cluster_1' => $upload->clusterResults->where('cluster', 1)->count(),
            'cluster_2' => $upload->clusterResults->where('cluster', 2)->count(),
            'cluster_3' => $upload->clusterResults->where('cluster', 3)->count()
        ];

        // Persiapan variabel default
        $cluster_info = [];
        $visualization = null;

        if ($upload->cluster_data) {
            try {
                // Decode cluster_data dari kolom database
                $cluster_info = json_decode($upload->cluster_data, true, 512, JSON_THROW_ON_ERROR);

                // Ambil path file untuk dikirim ke API Python
                $filePath = storage_path('app/public/uploads/toefl/' . $upload->file_name);

                // Panggil ulang API visualisasi (jika dibutuhkan)
                $response = Http::timeout(120)
                    ->attach('file', file_get_contents($filePath), $upload->file_name)
                    ->post('http://127.0.0.1:5000/cluster');

                if (!$response->successful()) {
                    throw new \Exception('API Error: ' . $response->body());
                }

                $apiResult = $response->json();
                $gambar = $apiResult["data"]["visualization"] ?? null;

                // Cek dan atur ulang key-key cluster_info
                $cluster_info['centroids'] = $cluster_info['centroids'] ?? [];
                $cluster_info['recommendations'] = $cluster_info['recommendations'] ?? [];

                // Validasi dan ubah visualisasi jadi data URI jika belum
                if ($gambar && !str_starts_with($gambar, 'data:image')) {
                    $gambar = 'data:image/png;base64,' . trim($gambar);
                }

                if ($gambar && !base64_decode(explode(',', $gambar)[1] ?? '', true)) {
                    $gambar = null; // Invalid base64 → tidak ditampilkan
                }
            } catch (\JsonException $e) {
                Log::error('Failed to decode cluster_data', [
                    'upload_id' => $upload_id,
                    'error' => $e->getMessage()
                ]);
                $cluster_info = [];
            } catch (\Exception $e) {
                Log::error('Failed API Request or base64 handling', [
                    'upload_id' => $upload_id,
                    'error' => $e->getMessage()
                ]);
                $gambar = null;
            }
        }

        return view('admin.klasterisasi.result', [
            'results' => $upload->clusterResults,
            'cluster_info' => $cluster_info,
            'upload' => $upload,
            'visualization' => $gambar,
            'cluster_counts' => $clusterCounts
        ]);
    }
}
