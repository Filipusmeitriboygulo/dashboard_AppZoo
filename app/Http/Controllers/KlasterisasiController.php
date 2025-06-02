<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $request->validate([
            'file_upload_id' => 'required|exists:file_uploads,id'
        ]);

        $file = FileUpload::findOrFail($request->file_upload_id);
        $filePath = storage_path('app/csv_uploads/' . $file->name);

        try {
            // Validasi file sebelum dikirim
            if (!file_exists($filePath)) {
                throw new \Exception("File tidak ditemukan di server.");
            }

            // Baca file untuk memastikan format benar
            $fileContent = file_get_contents($filePath);
            $lines = explode("\n", $fileContent);
            if (count($lines) < 5) {
                throw new \Exception("Format file tidak valid. Pastikan file memiliki header dan minimal 4 baris data.");
            }

            $response = Http::timeout(120)->attach(
                'file',
                $fileContent,
                $file->name
            )->post('http://127.0.0.1:5000/cluster');

            if ($response->successful()) {
                $result = $response->json();

                // Validasi response dari Flask API
                if (!isset($result['status'])) {
                    throw new \Exception('Respon tidak valid dari server klasterisasi');
                }

                if ($result['status'] === 'error') {
                    throw new \Exception($result['message'] ?? 'Error pada proses klasterisasi');
                }

                // Validasi struktur data hasil
                $requiredKeys = ['student_results', 'cluster_counts', 'centroids', 'recommendations'];
                foreach ($requiredKeys as $key) {
                    if (!isset($result['data'][$key])) {
                        throw new \Exception("Data hasil tidak lengkap. Key '$key' tidak ditemukan");
                    }
                }

                return redirect()->route('klasterisasi.result')->with([
                    'success' => 'Analisis klasterisasi berhasil',
                    'analysis_data' => $result['data'],
                    'file_info' => [
                        'name' => $file->name,
                        'uploaded_at' => $file->created_at->format('d M Y H:i'),
                        'total_students' => $result['data']['total_students'] ?? count($result['data']['student_results'])
                    ]
                ]);
            }

            throw new \Exception('Gagal menghubungi server klasterisasi. Status: ' . $response->status());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal melakukan analisis: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function result()
    {
        if (!session()->has('analysis_data')) {
            return redirect()->route('klasterisasi.index')
                ->withErrors(['error' => 'No analysis data available. Please upload and analyze a file first.']);
        }

        $analysisData = session('analysis_data');
        $fileInfo = session('file_info');

        // Validasi struktur data
        if (
            !isset($analysisData['data']['students']) ||
            !isset($analysisData['data']['cluster_counts']) ||
            !isset($analysisData['data']['centroids'])
        ) {
            return redirect()->route('klasterisasi.index')
                ->withErrors(['error' => 'Invalid result data structure']);
        }

        // Ambil label cluster (gunakan default jika tidak tersedia)
        $clusterLabels = $analysisData['data']['cluster_labels'] ?? [];

        if (empty($clusterLabels)) {
            $clusterLabels = [
                1 => 'Pemula',
                2 => 'Menengah',
                3 => 'Mahir'
            ];
        }

        // Siapkan data chart cluster
        $clusterData = [
            'labels' => array_values($clusterLabels),
            'counts' => array_values($analysisData['data']['cluster_counts']),
            'colors' => ['#FFCE56', '#36A2EB', '#4BC0C0', '#FF6384', '#9966FF'] // Tambah warna jika cluster > 3
        ];

        // Siapkan data hasil mahasiswa
        $studentResults = array_map(function ($student) use ($clusterLabels) {
            $clusterNumber = $student['Cluster'] ?? 0;

            // Ambil membership semua cluster
            $membership = [];
            foreach ($student as $key => $value) {
                if (Str::startsWith($key, 'Membership_Cluster_')) {
                    $membershipKey = strtolower(str_replace('Membership_Cluster_', 'cluster_', $key));
                    $membership[$membershipKey] = number_format($value ?? 0, 3);
                }
            }


            return [
                'no' => $student['No'] ?? '',
                'nama' => $student['Nama'] ?? '-',
                'nim' => $student['NIM'] ?? '-',
                'listening' => $student['Listening'] ?? 0,
                'structure' => $student['Structure'] ?? 0,
                'reading' => $student['Reading'] ?? 0,
                'cluster' => $clusterNumber,
                'cluster_label' => $clusterLabels[$clusterNumber] ?? 'Unknown',
                'membership' => $membership
            ];
        }, $analysisData['data']['students']);

        // Format centroid
        $centroids = array_map(function ($centroid) {
            return array_map(function ($val) {
                return is_numeric($val) ? number_format($val, 2) : $val;
            }, $centroid);
        }, $analysisData['data']['centroids']);

        // Rekomendasi berdasarkan label (opsional bisa dikembangkan)
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
            'cluster_data' => $clusterData,
            'student_results' => $studentResults,
            'centroids' => $centroids,
            'file_info' => $fileInfo,
            'success' => session('success'),
            'recommendations' => $recommendations,
            'total_students' => count($analysisData['data']['students'])
        ]);
    }
}
