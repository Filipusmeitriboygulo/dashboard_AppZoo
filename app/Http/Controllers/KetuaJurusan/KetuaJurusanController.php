<?php

namespace App\Http\Controllers\KetuaJurusan;

use App\Http\Controllers\Controller;
use App\Models\UploadLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KetuaJurusanController extends Controller
{
    // public function index()
    // {
    //     $file_uploads = UploadLog::orderBy('created_at', 'desc')->get();
    //     return view('admin.klasterisasi.index', compact('file_uploads'));
    // }

    public function index()
    {
        $user = Auth::user();

        // Ambil semua id prodi yang berada di bawah jurusan dia
        $prodiIds = $user->department->studyPrograms->pluck('id')->toArray();

        // Ambil nama jurusan (untuk cocok dengan kolom unit_nama jika cakupan = jurusan)
        $namaJurusan = $user->department->name;

        $file_uploads = UploadLog::where(function ($query) use ($prodiIds, $namaJurusan) {
            $query->where(function ($q) use ($prodiIds) {
                // Jika cakupan adalah 'prodi' dan prodi-nya milik jurusan dia
                $q->where('cakupan', 'prodi')
                    ->whereHas('toeflScores', function ($subQuery) use ($prodiIds) {
                        $subQuery->whereIn('study_program_id', $prodiIds);
                    });
            })->orWhere(function ($q) use ($namaJurusan) {
                // Jika cakupan adalah 'jurusan' dan sesuai dengan jurusan dia
                $q->where('cakupan', 'jurusan')
                    ->where('unit_nama', $namaJurusan);
            });
        })
            ->with(['toeflScores' => function ($query) use ($prodiIds) {
                $query->whereIn('study_program_id', $prodiIds);
            }])->get();

        return view('admin.klasterisasi.index', compact('file_uploads'));
    }


    public function result($upload_id)
    {
        $upload = UploadLog::with(['clusterResults', 'clusterResults.toeflScoreEntry'])->findOrFail($upload_id);



        // Ambil semua clusterResults lalu paginasi manual (jika bukan relasi langsung paginateable)
        $clusterResults = $upload->clusterResults;

        // Manual pagination
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $pagedResults = new LengthAwarePaginator(
            $clusterResults->forPage($currentPage, $perPage),
            $clusterResults->count(),
            $perPage,
            $currentPage,
            ['path' => route('klasterisasi.result', ['upload_id' => $upload_id])]
        );


        // Hitung jumlah per cluster untuk chart
        $clusterCounts = $upload->clusterResults
            ->groupBy('cluster')
            ->mapWithKeys(function ($items, $cluster) {
                return ["cluster_$cluster" => count($items)];
            })
            ->toArray();

        // Ambil insight per klaster dari hasil cluster yang disimpan
        $clusterInsights = $upload->clusterResults
            ->groupBy('cluster')
            ->map(function ($items, $cluster) {
                // Ambil insight unik untuk tiap klaster
                $uniqueInsights = $items->pluck('insight')->filter()->unique()->values();
                return [
                    'cluster' => $cluster,
                    'insights' => $uniqueInsights
                ];
            });


        // Hitung total lulus dan tidak lulus
        $totalLulus = $upload->clusterResults->where('status_lulus', 'Lulus')->count();
        $totalTidakLulus = $upload->clusterResults->where('status_lulus', 'Tidak Lulus')->count();
        $totalStudents = $upload->clusterResults->count();

        $cluster_info = [];
        $visualization = null;

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
                $gambar = $apiResult["data"]["visualization"];

                if (!isset($cluster_info['centroids'])) {
                    $cluster_info['centroids'] = [];
                }
                if (!isset($cluster_info['recommendations'])) {
                    $cluster_info['recommendations'] = [];
                }

                if ($gambar) {
                    $gambar = trim($gambar);
                    if (!str_starts_with($gambar, 'data:image')) {
                        $gambar = !empty($gambar) ? 'data:image/png;base64,' . $gambar : null;
                    }
                    if ($gambar && !base64_decode(explode(',', $gambar)[1] ?? '', true)) {
                        $gambar = null;
                    }
                }
            } catch (\JsonException $e) {
                Log::error('Failed to decode cluster data', [
                    'upload_id' => $upload_id,
                    'error' => $e->getMessage()
                ]);
                $cluster_info = [];
            }
        }

        return view('admin.klasterisasi.result', [
            'results' => $pagedResults,
            'cluster_info' => $cluster_info,
            'upload' => $upload,
            'visualization' => $gambar ?? null,
            'cluster_counts' => $clusterCounts,
            'totalLulus' => $totalLulus,
            'totalTidakLulus' => $totalTidakLulus,
            'totalStudents' => $totalStudents,
            'clusterInsights' => $clusterInsights
        ]);
    }
}
