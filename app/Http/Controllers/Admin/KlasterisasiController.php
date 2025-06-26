<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClusterResult;
use App\Models\ToeflScoreEntry;
use App\Models\UploadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KlasterisasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $file_uploads = UploadLog::orderBy('created_at', 'desc')->get();
        return view('admin.klasterisasi.index', compact('file_uploads'));
    }



    // public function analyze(Request $request)
    // {
    //     $request->validate([
    //         'file_upload_id' => 'required|exists:upload_log,id'
    //     ]);

    //     $file = UploadLog::findOrFail($request->file_upload_id);
    //     $filePath = storage_path('app/public/uploads/toefl/' . $file->file_name);

    //     try {
    //         // Validasi file exists
    //         if (!file_exists($filePath)) {
    //             throw new \Exception("File tidak ditemukan di: " . $filePath);
    //         }

    //         // Baca file sebagai binary
    //         $fileContent = file_get_contents($filePath);
    //         if ($fileContent === false) {
    //             throw new \Exception("Gagal membaca file");
    //         }

    //         // Kirim ke Flask API
    //         $response = Http::timeout(120)
    //             ->attach('file', $fileContent, $file->file_name)
    //             ->post('http://127.0.0.1:5000/cluster');

    //         // Handle response
    //         if (!$response->successful()) {
    //             throw new \Exception('API Error: ' . $response->body());
    //         }

    //         $apiresult = $response->json();

    //         // Validasi response structure
    //         if (!isset($result['status']) || $result['status'] !== 'success') {
    //             throw new \Exception($result['message'] ?? 'Invalid response from clustering API');
    //         }

    //         // Simpan hasil ke session
    //         // session([
    //         //     'analysis_data' => $result['data'],
    //         //     'file_info' => [
    //         //         'name' => $file->file_name,
    //         //         'uploaded_at' => $file->created_at->format('d M Y H:i')
    //         //     ]
    //         // ]);

    //         // return redirect()->route('klasterisasi.result')
    //         //     ->with('success', 'Analisis klasterisasi berhasil!');

    //         // Simpan hasil ke database
    //         $result = ClusterResult::create([
    //             'user_id' => Auth::id(),
    //             'student_results' => $apiresult['data']['student_results'],
    //             'cluster_counts' => $apiresult['data']['cluster_counts'],
    //             'centroids' => $apiresult['data']['centroids'],
    //             'recommendations' => $apiresult['data']['recommendations'],
    //             'visualization' => $apiresult['data']['visualization']
    //         ]);

    //         return redirect()->route('klasterisasi.result')
    //             ->with('succes', 'Analisis Klasterisasi berhasil');

    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->withErrors(['error' => 'Gagal melakukan analisis: ' . $e->getMessage()])
    //             ->withInput();
    //     }
    // }

    // public function analyze(Request $request)
    // {
    //     $request->validate([
    //         'file_upload_id' => 'required|exists:upload_log,id'
    //     ]);

    //     $file = UploadLog::findOrFail($request->file_upload_id);
    //     $filePath = storage_path('app/public/uploads/toefl/' . $file->file_name);

    //     try {
    //         // Validasi file exists
    //         if (!file_exists($filePath)) {
    //             throw new \Exception("File tidak ditemukan di: " . $filePath);
    //         }

    //         // Kirim ke Flask API
    //         $response = Http::timeout(120)
    //             ->attach('file', file_get_contents($filePath), $file->file_name)
    //             ->post('http://127.0.0.1:5000/cluster');

    //         if (!$response->successful()) {
    //             throw new \Exception('API Error: ' . $response->body());
    //         }

    //         $apiResult = $response->json();

    //         // Validasi response API
    //         if (!isset($apiResult['status']) || $apiResult['status'] !== 'success') {
    //             throw new \Exception($apiResult['message'] ?? 'Invalid response from clustering API');
    //         }

    //         // Simpan data per siswa
    //         $clusterData = [];
    //         foreach ($apiResult['data']['student_results'] as $index => $student) {
    //             $clusterNumber = $student['Cluster'];
    //             $clusterInfo = $apiResult['data']['recommendations']["cluster_{$clusterNumber}"] ?? null;

    //             $clusterData[] = [
    //                 'upload_id' => $file->id,
    //                 'no' => $index + 1,
    //                 'nama' => $student['Nama'],
    //                 'nim' => $student['Nim Mahasiswa'] ?? 'NIM tidak tersedia',
    //                 'prodi' => $this->extractProdi($student['Jur / Prodi / Kls'] ?? ''),
    //                 'kelas' => $this->extractKelas($student['Jur / Prodi / Kls'] ?? ''),
    //                 'listening' => (int) $student['Listening'],
    //                 'structure' => (int) $student['Structure'],
    //                 'reading' => (int) $student['Reading'],
    //                 'total' => (int) $student['Total'],
    //                 'cluster' => $clusterNumber,
    //                 'membership_cluster1' => $student['Membership_Cluster_1'] ?? 0,
    //                 'membership_cluster2' => $student['Membership_Cluster_2'] ?? 0,
    //                 'membership_cluster3' => $student['Membership_Cluster_3'] ?? 0,
    //                 'insight' => $clusterInfo ? implode('; ', $clusterInfo['recommendations']) : 'Tidak ada rekomendasi',
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ];
    //         }

    //         // Gunakan transaction untuk memastikan konsistensi data
    //         DB::transaction(function () use ($clusterData, $file) {
    //             // Hapus hasil sebelumnya jika ada
    //             ClusterResult::where('upload_id', $file->id)->delete();

    //             // Bulk insert untuk performa
    //             ClusterResult::insert($clusterData);
    //         });

    //         return redirect()->route('klasterisasi.result', ['upload_id' => $file->id])
    //             ->with('success', 'Analisis klasterisasi berhasil! Data tersimpan.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->withErrors(['error' => 'Gagal melakukan analisis: ' . $e->getMessage()])
    //             ->withInput();
    //     }
    // }


    // private function extractProdi($data)
    // {
    //     $parts = explode('/', $data);
    //     return trim($parts[1] ?? 'Tidak diketahui');
    // }

    // private function extractKelas($data)
    // {
    //     $parts = explode('/', $data);
    //     return trim($parts[2] ?? 'Tidak diketahui');
    // }

    // public function analyze(Request $request)
    // {
    //     $request->validate([
    //         'file_upload_id' => 'required|exists:upload_log,id'
    //     ]);

    //     $file = UploadLog::findOrFail($request->file_upload_id);
    //     $filePath = storage_path('app/public/uploads/toefl/' . $file->file_name);

    //     try {
    //         // Validasi file exists
    //         if (!file_exists($filePath)) {
    //             throw new \Exception("File tidak ditemukan di: " . $filePath);
    //         }

    //         // Kirim ke Flask API
    //         $response = Http::timeout(120)
    //             ->attach('file', file_get_contents($filePath), $file->file_name)
    //             ->post('http://127.0.0.1:5000/cluster');

    //         // Debugging: Log response
    //         Log::info('API Response:', [
    //             'status' => $response->status(),
    //             'body' => $response->body()
    //         ]);

    //         if (!$response->successful()) {
    //             throw new \Exception('API Error: ' . $response->body());
    //         }

    //         $apiResult = $response->json();
    //         dd($apiResult);

    //         // Debugging: Log API result
    //         Log::info('API Result:', $apiResult);

    //         // Validasi response API
    //         if (!isset($apiResult['status']) || $apiResult['status'] !== 'success') {
    //             throw new \Exception($apiResult['message'] ?? 'Invalid response from clustering API');
    //         }

    //         // Simpan data ke database
    //         $clusterData = [];
    //         foreach ($apiResult['data']['student_results'] as $index => $student) {
    //             $clusterNumber = $student['Cluster'];
    //             $clusterInfo = $apiResult['data']['recommendations']["cluster_{$clusterNumber}"] ?? null;

    //             $clusterData[] = [
    //                 'upload_id' => $file->id,
    //                 'no' => $index + 1,
    //                 'nama' => $student['Nama'],
    //                 'nim' => $student['Nim Mahasiswa'] ?? 'NIM tidak tersedia',
    //                 'prodi' => $this->extractProdi($student['Jur / Prodi / Kls'] ?? ''),
    //                 'kelas' => $this->extractKelas($student['Jur / Prodi / Kls'] ?? ''),
    //                 'listening' => (int) $student['Listening'],
    //                 'structure' => (int) $student['Structure'],
    //                 'reading' => (int) $student['Reading'],
    //                 'total' => (int) $student['Total'],
    //                 'cluster' => $clusterNumber,
    //                 'membership_cluster1' => $student['Membership_Cluster_1'] ?? 0,
    //                 'membership_cluster2' => $student['Membership_Cluster_2'] ?? 0,
    //                 'membership_cluster3' => $student['Membership_Cluster_3'] ?? 0,
    //                 'insight' => $clusterInfo ? implode('; ', $clusterInfo['recommendations']) : 'Tidak ada rekomendasi',
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ];
    //         }

    //         // Simpan ke database
    //         DB::transaction(function () use ($clusterData, $file) {
    //             ClusterResult::where('upload_id', $file->id)->delete();
    //             ClusterResult::insert($clusterData);

    //             // Update upload log dengan data tambahan dari API
    //             $file->update([
    //                 'visualization' => $apiResult['data']['visualization'] ?? null,
    //                 'centroids' => json_encode($apiResult['data']['centroids'] ?? []),
    //                 'recommendations' => json_encode($apiResult['data']['recommendations'] ?? [])
    //             ]);
    //         });

    //         // Redirect ke hasil dengan session data
    //         return redirect()->route('klasterisasi.result', ['upload_id' => $file->id])
    //             ->with('success', 'Analisis klasterisasi berhasil! Data tersimpan.')
    //             ->with('api_result', $apiResult); // Tambahkan ini untuk debugging

    //     } catch (\Exception $e) {
    //         Log::error('Clustering Error: ' . $e->getMessage());
    //         return redirect()->back()
    //             ->withErrors(['error' => 'Gagal melakukan analisis: ' . $e->getMessage()])
    //             ->withInput();
    //     }
    // }

    public function analyze(Request $request)
    {
        $request->validate([
            'file_upload_id' => 'required|exists:upload_log,id'
        ]);

        $file = UploadLog::findOrFail($request->file_upload_id);
        $filePath = storage_path('app/public/uploads/toefl/' . $file->file_name);

        // Validasi file exists
        if (!file_exists($filePath)) {
            return redirect()->back()
                ->withErrors(['error' => 'File tidak ditemukan di lokasi yang ditentukan'])
                ->withInput();
        }

        try {
            // Panggil API clustering
            $response = Http::timeout(120)
                ->attach('file', file_get_contents($filePath), $file->file_name)
                ->post('http://127.0.0.1:5000/cluster');

            if (!$response->successful()) {
                throw new \Exception('API Error: ' . $response->body());
            }

            $apiResult = $response->json();
            // dd($apiResult);

            if ($apiResult['status'] !== 'success') {
                throw new \Exception($apiResult['message'] ?? 'Invalid API response');
            }

            DB::beginTransaction();

            try {
                foreach ($apiResult['data']['student_results'] as $studentData) {
                    $toeflEntry = ToeflScoreEntry::where([
                        'upload_id' => $file->id,
                        'nim' => $studentData['Nim Mahasiswa']
                    ])->first();

                    if (!$toeflEntry) {
                        throw new \Exception("Data TOEFL untuk NIM {$studentData['Nim Mahasiswa']} tidak ditemukan");
                    }

                    ClusterResult::updateOrCreate(
                        [
                            'upload_id' => $file->id,
                            'toefl_score_entry_id' => $toeflEntry->id
                        ],
                        [
                            'cluster' => (int) $studentData['Cluster'],
                            'membership_cluster1' => (float) $studentData['Membership_Cluster_1'],
                            'membership_cluster2' => (float) $studentData['Membership_Cluster_2'],
                            'membership_cluster3' => (float) $studentData['Membership_Cluster_3'],
                            'insight' => $studentData['Insight']
                        ]
                    );
                }

                // Update file status dan simpan data cluster
                $file->update([
                    'status_klasterisasi' => 'sudah',
                    'cluster_data' => json_encode($apiResult['data']['cluster_info'])
                ]);

                DB::commit();

                return redirect()->route('klasterisasi.result', ['upload_id' => $file->id])
                    ->with('success', 'Analisis klasterisasi berhasil dilakukan');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['error' => 'Gagal menyimpan hasil klasterisasi: ' . $e->getMessage()])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menganalisis: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function reanalyze($id)
    {
        $upload = UploadLog::findOrFail($id);
        $filePath = storage_path('app/public/uploads/toefl/' . $upload->file_name);

        if (!file_exists($filePath)) {
            return redirect()->back()->withErrors('File tidak ditemukan untuk dianalisis ulang.');
        }

        try {
            // Hapus hasil klasterisasi sebelumnya
            ClusterResult::where('upload_id', $upload->id)->delete();

            // Kirim ulang ke API Flask
            $response = Http::timeout(120)
                ->attach('file', file_get_contents($filePath), $upload->file_name)
                ->post('http://127.0.0.1:5000/cluster');

            if (!$response->successful()) {
                throw new \Exception('API error: ' . $response->body());
            }

            $apiResult = $response->json();

            if ($apiResult['status'] !== 'success') {
                throw new \Exception($apiResult['message'] ?? 'Gagal dari API');
            }

            DB::beginTransaction();

            foreach ($apiResult['data']['student_results'] as $studentData) {
                $entry = ToeflScoreEntry::where([
                    'upload_id' => $upload->id,
                    'nim' => $studentData['Nim Mahasiswa']
                ])->first();

                if (!$entry) {
                    throw new \Exception("Data TOEFL tidak ditemukan untuk NIM: {$studentData['Nim Mahasiswa']}");
                }

                ClusterResult::create([
                    'upload_id' => $upload->id,
                    'toefl_score_entry_id' => $entry->id,
                    'cluster' => $studentData['Cluster'],
                    'membership_cluster1' => $studentData['Membership_Cluster_1'],
                    'membership_cluster2' => $studentData['Membership_Cluster_2'],
                    'membership_cluster3' => $studentData['Membership_Cluster_3'],
                    'insight' => $studentData['Insight'],
                ]);
            }

            $upload->update([
                'status_klasterisasi' => 'sudah',
                'cluster_data' => json_encode($apiResult['data']['cluster_info']),
            ]);

            DB::commit();

            return redirect()->route('klasterisasi.result', $upload->id)
                ->with('success', 'Analisis ulang berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal menganalisis ulang: ' . $e->getMessage());
        }
    }
    // Extract Prodi
    // private function extractProdi(?string $dataString): string
    // {
    //     if (empty($dataString) || !is_string($dataString)) {
    //         return '';
    //     }

    //     // Bersihkan whitespace dan split berdasarkan delimiter
    //     $parts = array_map('trim', explode('/', $dataString));
    //     $parts = array_filter($parts); // Hapus elemen kosong

    //     // Jika format lengkap (Jurusan/Prodi/Kelas)
    //     if (count($parts) >= 2) {
    //         return $parts[1]; // Prodi ada di posisi kedua
    //     }

    //     // Fallback: jika format tidak standar
    //     return $parts[0] ?? '';
    // }

    // // Extract kelas 
    // private function extractKelas(?string $dataString): string
    // {
    //     if (empty($dataString) || !is_string($dataString)) {
    //         return '';
    //     }

    //     // Bersihkan whitespace dan split berdasarkan delimiter
    //     $parts = array_map('trim', explode('/', $dataString));
    //     $parts = array_filter($parts); // Hapus elemen kosong

    //     // Jika format lengkap (Jurusan/Prodi/Kelas) dan memiliki 3 bagian
    //     if (count($parts) >= 3) {
    //         return $parts[2]cluster_info; // Kelas ada di posisi ketiga
    //     }

    //     // Fallback: cari bagian yang paling mungkin menjadi kelas (1 huruf/angka)
    //     foreach ($parts as $part) {
    //         if (preg_match('/^[A-Za-z0-9]{1,3}$/', $part)) { // Maksimal 3 karakter untuk kelas
    //             return $part;
    //         }
    //     }

    //     return '';
    // }

    // public function result($upload_id)
    // {
    //     $upload = UploadLog::findOrFail($upload_id);
    //     $results = ClusterResult::where('upload_id', $upload_id)
    //         ->orderBy('cluster')
    //         ->orderBy('total', 'desc')
    //         ->get();

    //     $clusterCounts = ClusterResult::where('upload_id', $upload_id)
    //         ->selectRaw('cluster, count(*) as count')
    //         ->groupBy('cluster')
    //         ->pluck('count', 'cluster')
    //         ->toArray();

    //     return view('klasterisasi.result', [
    //         'upload' => $upload,
    //         'results' => $results,
    //         'clusterCounts' => $clusterCounts
    //     ]);
    // }

    // public function result($upload_id)
    // {
    //     $upload = UploadLog::findOrFail($upload_id);
    //     $results = ClusterResult::where('upload_id', $upload_id)->get();

    //     // Jika data sudah ada di database
    //     if ($upload->cluster_data) {
    //         $clusterInfo = json_decode($upload->cluster_data, true);
    //         $resultData = [
    //             'cluster_info' => $clusterInfo,
    //             'student_results' => $results->toArray(),
    //             'visualization' => $this->generateVisualization($results),
    //         ];

    //         return view('klasterisasi.result', [
    //             'result' => $resultData,
    //             'upload' => $upload
    //         ]);
    //     }

    //     // Fallback jika data tidak ada di database
    //     abort(404, 'Hasil analisis tidak ditemukan');
    // }

    // public function result($upload_id)
    // {
    //     $upload = UploadLog::with('clusterResults')->findOrFail($upload_id);

    //     if ($upload->clusterResults->isEmpty()) {
    //         return back()->withErrors('Data hasil klasterisasi belum tersedia');
    //     }

    //     return view('/klasterisasi/result/{upload_id}', [
    //         'results' => $upload->clusterResults,
    //         'cluster_info' => json_decode($upload->cluster_data, true),
    //         'upload' => $upload
    //     ]);
    // }

    // public function result($upload_id)
    // {
    //     $upload = UploadLog::with(['clusterResults', 'clusterResults.toeflScoreEntry'])->findOrFail($upload_id);

    //     if ($upload->status_klasterisasi !== 'sudah') {
    //         return back()->withErrors('Data belum diproses melalui analisis klasterisasi');
    //     }

    //     if ($upload->clusterResults->isEmpty()) {
    //         return back()->withErrors('Data hasil klasterisasi belum tersedia');
    //     }

    //     // Decode cluster_data dengan error handling
    //     $cluster_info = [];
    //     if ($upload->cluster_data) {
    //         $cluster_info = json_decode($upload->cluster_data, true);
    //         if (json_last_error() !== JSON_ERROR_NONE) {
    //             Log::error('Invalid cluster_data JSON', [
    //                 'upload_id' => $upload_id,
    //                 'error' => json_last_error_msg()
    //             ]);
    //             // Tetap lanjutkan dengan array kosong
    //             $cluster_info = [];
    //         }
    //     }

    //     return view('admin.klasterisasi.result', [
    //         'results' => $upload->clusterResults,
    //         'cluster_info' => $cluster_info,
    //         'upload' => $upload
    //     ]);
    // }


    public function result($upload_id)
    {
        $upload = UploadLog::with(['clusterResults', 'clusterResults.toeflScoreEntry'])->findOrFail($upload_id);

        // if ($upload->status_klasterisasi !== 'sudah') {
        //     return back()->withErrors('Data belum diproses melalui analisis klasterisasi');
        // }

        // if ($upload->clusterResults->isEmpty()) {
        //     return back()->withErrors('Data hasil klasterisasi belum tersedia');
        // }

        // Decode cluster_data dengan error handling
        $cluster_info = [];
        if ($upload->cluster_data) {
            $cluster_info = json_decode($upload->cluster_data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid cluster_data JSON', [
                    'upload_id' => $upload_id,
                    'error' => json_last_error_msg()
                ]);
                // Tetap lanjutkan dengan array kosong
                $cluster_info = [];
            }
        }

        return view('admin.klasterisasi.result', [
            'results' => $upload->clusterResults,
            'cluster_info' => $cluster_info,
            'upload' => $upload,
            'visualization' => $apiResult['data']['visualization'] ?? null,
        ]);
    }

    private function generateVisualization($results)
    {
        // Implementasi generate gambar jika diperlukan
        return null;
    }
}
