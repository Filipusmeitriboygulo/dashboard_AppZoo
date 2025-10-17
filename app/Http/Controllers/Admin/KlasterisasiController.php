<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClusterResult;
use App\Models\Department;
use App\Models\StudyProgram;
use App\Models\ToeflScoreEntry;
use App\Models\UploadLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KlasterisasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();


        if (in_array($user->role, ['admin', 'kepala_upa', 'wakil_direktur'])) {
            $file_uploads = UploadLog::with('toeflScores')->get();
        } elseif ($user->role === 'ketua_jurusan') {
            // Ambil semua prodi ID di bawah jurusan tersebut
            $prodiIds = $user->department->studyPrograms->pluck('id')->toArray();
            $departmentId = $user->department->id;

            // Ambil UploadLog yang memiliki skor dengan department_id sesuai, atau prodi di bawah jurusan tersebut
            $file_uploads = UploadLog::whereHas('toeflScores', function ($query) use ($departmentId, $prodiIds) {
                $query->where('department_id', $departmentId)
                    ->orWhereIn('study_program_id', $prodiIds);
            })->with(['toeflScores' => function ($query) use ($departmentId, $prodiIds) {
                $query->where('department_id', $departmentId)
                    ->orWhereIn('study_program_id', $prodiIds);
            }])->get();
        } else {
            abort(403, 'Unauthorized');
        }

        $departments = Department::all();
        $studyPrograms = StudyProgram::all();


        return view('admin.klasterisasi.index', compact('file_uploads', 'departments', 'studyPrograms'));
    }

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

            // dd($response->body());

            if (!$response->successful()) {
                throw new \Exception('API Error: ' . $response->body());
            }

            $apiResult = $response->json();
            // dd($apiResult);
            // dd(
            //     'Status HTTP:',
            //     $response->status(),
            //     'Isi Respon (Body):',
            //     $response->body()
            // );
            if ($apiResult['status'] !== 'success') {
                throw new \Exception($apiResult['message'] ?? 'Invalid API response');
            }


            // $student_results = $apiResult['data']['student_results'];
            $student_results = $apiResult['data']['student_results'];

            $clusterInsights = [];

            foreach ($student_results as $result) {
                $clusterKey = 'Cluster ' . $result['Cluster'];

                if (!isset($clusterInsights[$clusterKey])) {
                    $clusterInsights[$clusterKey] = [];
                }

                $insight = trim($result['Insight']);
                if ($insight && !in_array($insight, $clusterInsights[$clusterKey])) {
                    $clusterInsights[$clusterKey][] = $insight;
                }
            }


            DB::beginTransaction();

            try {
                foreach ($apiResult['data']['student_results'] as $studentData) {
                    $toeflEntry = ToeflScoreEntry::where([
                        'upload_id' => $file->id,
                        'nim' => $studentData['Nim Mahasiswa']
                    ])->first();

                    // TAMBAHKAN DD DI SINI UNTUK MELIHAT HASIL PENCOCOKAN
                    // dd($studentData, $toeflEntry);

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
                            // Saat menyimpan:
                            'membership' => collect($studentData['membership'])->mapWithKeys(function ($v, $k) {
                                return [strval($k) => (float) $v];
                            })->toArray(),

                            'insight' => $studentData['Insight'],
                            'status_lulus' => $studentData['Status_Lulus']
                        ]
                    );
                }

                // Update file status dan simpan data cluster
                $file->update([
                    'status_klasterisasi' => 'sudah',
                    'cluster_data' => json_encode($apiResult['data']['cluster_info']),
                    'cluster_insights' => json_encode($clusterInsights)
                ]);

                DB::commit();

                return redirect()->route('klasterisasi.result', ['upload_id' => $file->id])
                    ->with('success', 'Analisis klasterisasi berhasil dilakukan');
            } catch (\Exception $e) {
                // TAMBAHKAN DD DI SINI UNTUK MELIHAT PESAN ERROR ASLI
                dd($e);
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
            // dd($apiResult);

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
                    'cluster' => (int) $studentData['Cluster'],
                    // Saat menyimpan:
                    'membership' => collect($studentData['membership'])->mapWithKeys(function ($v, $k) {
                        return [strval($k) => (float) $v];
                    })->toArray(),

                    'insight' => $studentData['Insight'],
                    'status_lulus' => $studentData['Status_Lulus']
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


    public function result($upload_id)
    {
        $upload = UploadLog::with(['clusterResults', 'clusterResults.toeflScoreEntry'])->findOrFail($upload_id);

        // dd($upload->clusterResults);

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
