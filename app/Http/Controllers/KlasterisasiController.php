<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;


class KlasterisasiController extends Controller
{
    public function __construct()
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
        // session()->forget(['analysis_data', 'file_info', 'success']);
        $request->validate([
            'file_upload_id' => 'required|exists:file_uploads,id'
        ]);

        $file = FileUpload::findOrFail($request->file_upload_id);
        $filePath = storage_path('app/csv_uploads/' . $file->name);

        try {
            if (!file_exists($filePath)) {
                throw new \Exception("File tidak ditemukan di server.");
            }

            $fileContent = file_get_contents($filePath);
            $lines = explode("\n", $fileContent);
            if (count($lines) < 5) {
                throw new \Exception("Format file tidak valid. Pastikan file memiliki header dan minimal 4 baris data.");
            }

            $response = Http::timeout(60)->attach('file', $fileContent, $file->name)
                ->post('http://127.0.0.1:5000/cluster');

            if ($response->successful()) {
                $result = $response->json();

                if (!isset($result['status'])) {
                    throw new \Exception('Respon tidak valid dari server klasterisasi');
                }

                if ($result['status'] === 'error') {
                    throw new \Exception($result['message'] ?? 'Error pada proses klasterisasi');
                }

                $requiredKeys = ['student_results', 'cluster_counts', 'centroids', 'recommendations'];
                foreach ($requiredKeys as $key) {
                    if (!isset($result['data'][$key])) {
                        throw new \Exception("Data hasil tidak lengkap. Key '$key' tidak ditemukan");
                    }
                }

                session([
                    'analysis_data' => $result['data'],
                    'file_info' => [
                        'name' => $file->name,
                        'uploaded_at' => $file->created_at->format('d M Y H:i')
                    ],
                    'success' => 'Analisis klasterisasi berhasil'
                ]);
                dd(session()->all());

                return redirect()->route('klasterisasi.result');
            }

            throw new \Exception('Gagal menghubungi server klasterisasi. Status: ' . $response->status());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal melakukan analisis: ' . $e->getMessage()])->withInput();
        }
    }


    public function result()
    {

        if (!session()->has('analysis_data')) {
            return redirect()->route('klasterisasi.index')
                ->withErrors(['error' => 'Data analisis tidak tersedia. Silakan unggah file terlebih dahulu.']);
        }

        $analysisData = session('analysis_data');
        $fileInfo = session('file_info');

        // Validasi struktur data
        if (
            !isset($analysisData['data']['students']) ||
            !isset($analysisData['data']['centroids'])
        ) {
            return redirect()->route('klasterisasi.index')
                ->withErrors(['error' => 'Struktur data hasil klasterisasi tidak valid.']);
        }

        // Label klaster
        $clusterLabels = $analysisData['data']['cluster_labels'] ?? [
            1 => 'Pemula',
            2 => 'Menengah',
            3 => 'Mahir'
        ];

        // Hitung jumlah anggota per klaster
        $clusterCounts = [];
        foreach ($analysisData['data']['students'] as $student) {
            $cluster = $student['Cluster'] ?? 1;
            $clusterCounts[$cluster] = ($clusterCounts[$cluster] ?? 0) + 1;
        }
        ksort($clusterCounts);

        $clusterData = [
            'labels' => array_values($clusterLabels),
            'counts' => array_values($clusterCounts),
            'colors' => ['#FFCE56', '#36A2EB', '#4BC0C0', '#FF6384', '#9966FF']
        ];

        // Format data mahasiswa
        $studentsRaw = [];
        foreach ($analysisData['data']['students'] as $student) {
            $membership = [];
            foreach ($student as $key => $value) {
                if (str_starts_with($key, 'Membership_Cluster_')) {
                    $clusterNum = str_replace('Membership_Cluster_', '', $key);
                    $membership['cluster_' . $clusterNum] = number_format($value, 3);
                }
            }

            $studentsRaw[] = [
                'no' => $student['No'] ?? $student['no'] ?? '',
                'nama' => $student['Nama'] ?? $student['nama'] ?? 'Unknown',
                'nim' => $student['NIM'] ?? $student['nim'] ?? '',
                'listening' => $student['Listening'] ?? 0,
                'structure' => $student['Structure'] ?? 0,
                'reading' => $student['Reading'] ?? 0,
                'cluster' => $student['Cluster'] ?? 1,
                'cluster_label' => $clusterLabels[$student['Cluster'] ?? 1] ?? 'Unknown',
                'membership' => $membership
            ];
        }

        // Pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $studentsPaginated = new LengthAwarePaginator(
            array_slice($studentsRaw, ($currentPage - 1) * $perPage, $perPage),
            count($studentsRaw),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        // Data centroid untuk chart radar
        $centroids = [];
        $radarData = [];
        $radarLabels = ['Listening', 'Structure', 'Reading'];

        foreach ($analysisData['data']['centroids'] as $centroid) {
            $centroids[] = [
                number_format($centroid['Listening'] ?? $centroid[0] ?? 0, 2),
                number_format($centroid['Structure'] ?? $centroid[1] ?? 0, 2),
                number_format($centroid['Reading'] ?? $centroid[2] ?? 0, 2)
            ];

            $radarData[] = [
                $centroid['Listening'] ?? $centroid[0] ?? 0,
                $centroid['Structure'] ?? $centroid[1] ?? 0,
                $centroid['Reading'] ?? $centroid[2] ?? 0
            ];
        }

        // Rekomendasi per klaster
        $recommendations = [];
        foreach ($clusterLabels as $cluster => $label) {
            if (stripos($label, 'Pemula') !== false) {
                $recommendations[$cluster] = "Ikuti kelas intensif untuk Listening dan Structure dasar.";
            } elseif (stripos($label, 'Menengah') !== false) {
                $recommendations[$cluster] = "Tingkatkan latihan soal Reading dan Structure menengah.";
            } elseif (stripos($label, 'Mahir') !== false) {
                $recommendations[$cluster] = "Siap mengikuti TOEFL resmi dengan simulasi tes lengkap.";
            } else {
                $recommendations[$cluster] = "Pelajari sesuai tingkat kemampuan yang teridentifikasi.";
            }
        }

        return view('klasterisasi.result', [
            'analysis_data' => $analysisData,
            'file_info' => $fileInfo,
            'success' => session('success'),
            'chart_data' => $clusterData,
            'students' => $studentsPaginated,
            'centroids' => $centroids,
            'radar_labels' => $radarLabels,
            'radar_data' => $radarData,
            'recommendations' => $recommendations
        ]);
    }
}
