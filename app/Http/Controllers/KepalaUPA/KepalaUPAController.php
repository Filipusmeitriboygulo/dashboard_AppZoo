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
        $upload = UploadLog::with(['clusterResults', 'clusterResults.toeflScoreEntry'])->findOrFail($upload_id);

        // Hitung jumlah per cluster untuk chart
        $clusterCounts = $upload->clusterResults
            ->groupBy('cluster')
            ->mapWithKeys(function ($items, $cluster) {
                return ["cluster_$cluster" => count($items)];
            })
            ->toArray();



        // Handle cluster data
        $cluster_info = [];
        $visualization = $cluster_info['visualization'] ?? null;
        // dd($upload);
        if ($upload->cluster_data) {
            try {
                $cluster_info = json_decode($upload->cluster_data, true, 512, JSON_THROW_ON_ERROR);
                $file = UploadLog::findOrFail($upload_id);
                $filePath = storage_path('app/public/uploads/toefl/' . $file->file_name);

                $response = Http::timeout(120)
                    ->attach('file', file_get_contents($filePath), $file->file_name)
                    ->post('http://127.0.0.1:5000/cluster');

                if (!$response->successful()) {
                    throw new \Exception('API Error: ' . $response->body());
                }

                $apiResult = $response->json();
                // dd($apiResult);
                $gambar = $apiResult["data"]["visualization"];
                // dd($gambar);


                // Validasi struktur data
                if (!isset($cluster_info['centroids'])) {
                    $cluster_info['centroids'] = [];
                }
                if (!isset($cluster_info['recommendations'])) {
                    $cluster_info['recommendations'] = [];
                }
                if ($visualization) {
                    // Bersihkan whitespace
                    $visualization = trim($visualization);

                    // Pastikan format data URI benar
                    if (!str_starts_with($visualization, 'data:image')) {
                        // Hanya tambahkan prefix jika string tidak kosong
                        $visualization = !empty($visualization) ? 'data:image/png;base64,' . $visualization : null;
                    }

                    // Validasi base64
                    if ($visualization && !base64_decode(explode(',', $visualization)[1] ?? '', true)) {
                        $visualization = null; // Invalid base64
                    }
                }
                // dd($visualization);
            } catch (\JsonException $e) {
                Log::error('Failed to decode cluster data', [
                    'upload_id' => $upload_id,
                    'error' => $e->getMessage()
                ]);
                $cluster_info = [];
            }
        }

        return view('admin.klasterisasi.result', [
            'results' => $upload->clusterResults,
            'cluster_info' => $cluster_info,
            'upload' => $upload,
            'visualization' => $gambar,
            'cluster_counts' => $clusterCounts // Kirim data counts ke view
        ]);
    }
}
