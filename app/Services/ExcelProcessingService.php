<?php

namespace App\Services;

use App\Models\Student;
use App\Models\ToeflScore;
use App\Models\ClassModel;
use App\Models\StudyProgram;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class ExcelProcessingService
{
    public function previewFile($filePath, $scope, $scopeId = null)
    {
        $fullPath = Storage::path($filePath);

        if (!file_exists($fullPath)) {
            throw new \Exception('File not found');
        }

        // Detect file type
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if (in_array($extension, ['xlsx', 'xls'])) {
            return $this->previewExcel($fullPath, $scope, $scopeId);
        } else {
            return $this->previewExcel($fullPath, $scope, $scopeId);
        }
    }

    public function processFile($filePath, $fileType, $scope, $scopeId, $batchId, $uploadedBy)
    {
        $fullPath = Storage::path($filePath);

        DB::beginTransaction();

        try {
            if (in_array($fileType, ['xlsx', 'xls'])) {
                $result = $this->processExcel($fullPath, $scope, $scopeId, $batchId, $uploadedBy);
            } else {
                $result = $this->processCSV($fullPath, $scope, $scopeId, $batchId, $uploadedBy);
            }

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    protected function filterEmptyRows(array $data): array
    {
        return array_filter($data, function ($row) {
            // Pastikan $row adalah array
            if (!is_array($row)) {
                return false;
            }

            // Filter nilai kosong dalam row
            $filteredValues = array_filter($row, function ($value) {
                return $value !== null && $value !== '' && (!is_string($value) || trim($value) !== '');
            });

            // Keep row jika ada nilai yang tidak kosong
            return count($filteredValues) > 0;
        });
    }

    // FIXED: Hapus koma trailing
    protected function previewExcel($fullPath, $scope, $scopeId)
    {
        try {
            $spreadsheet = IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $dataRows = $worksheet->toArray();
            $dataRows = $this->filterEmptyRows($dataRows);

            if (empty($dataRows)) {
                throw new \Exception("File Excel kosong/tidak valid");
            }

            // Ambil 10 baris pertama (tanpa header)
            $sampleRows = array_slice($dataRows, 0, 10);

            return [
                'file_type' => 'excel',
                'headers' => [], // Kosongkan header
                'total_rows' => count($dataRows),
                'sample_rows' => array_map(function ($row) {
                    return [
                        'original' => $row,
                        'processed' => $row
                    ];
                }, $sampleRows),
                'preview_count' => count($sampleRows),
                'has_errors' => false,
                'errors' => [],
                'scope_info' => $this->getScopeInfo($scope, $scopeId),
                'header_row_index' => null // Tidak ada header
            ];
        } catch (\Exception $e) {
            throw new \Exception('Gagal membaca Excel: ' . $e->getMessage());
        }
    }

    protected function processExcel($fullPath, $scope, $scopeId, $batchId, $uploadedBy)
    {
        try {
            $spreadsheet = IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $dataRows = $worksheet->toArray();

            // Filter baris kosong
            $dataRows = $this->filterEmptyRows($dataRows);

            if (empty($dataRows)) {
                throw new \Exception("File Excel kosong/tidak valid");
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($dataRows as $row) {
                try {
                    // Proses langsung tanpa validasi
                    $processedData = [
                        'no' => $row[0] ?? null,
                        'nama' => $row[1] ?? null,
                        'jur_kls_prodi' => $row[2] ?? null,
                        'nilai_toefl' => $row[3] ?? null
                    ];

                    // Gabungkan dengan data jurusan/kelas jika parseJurKlsProdi masih diperlukan
                    if (method_exists($this, 'parseJurKlsProdi')) {
                        $jurKlsProdiData = $this->parseJurKlsProdi($row[2] ?? '');
                        $processedData = array_merge($processedData, $jurKlsProdiData);
                    }

                    // Simpan ke database
                    $this->saveStudentAndScore(
                        $processedData,
                        $scope,
                        $scopeId,
                        $batchId,
                        $uploadedBy
                    );

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Gagal memproses baris: " . $e->getMessage();
                }
            }

            return [
                'total_rows' => count($dataRows),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors,
                'batch_id' => $batchId
            ];
        } catch (\Exception $e) {
            throw new \Exception('Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    protected function parseJurKlsProdi($jurKlsProdi)
    {
        if (empty($jurKlsProdi)) {
            return [
                'kode_jurusan' => null,
                'kode_prodi' => null,
                'kelas' => null
            ];
        }

        // Normalisasi input - hilangkan spasi berlebihan
        $jurKlsProdi = preg_replace('/\s+/', ' ', trim($jurKlsProdi));

        // Split by slash dan trim masing-masing bagian
        $parts = array_map('trim', explode('/', $jurKlsProdi));

        $result = [
            'kode_jurusan' => null,
            'kode_prodi' => null,
            'kelas' => null
        ];

        if (count($parts) >= 3) {
            $result['kode_jurusan'] = $parts[0] ?: null;
            $result['kode_prodi'] = $parts[1] ?: null;
            $result['kelas'] = $parts[2] ?: null;
        } elseif (count($parts) === 2) {
            $result['kode_prodi'] = $parts[0] ?: null;
            $result['kelas'] = $parts[1] ?: null;
        } else {
            $result['kelas'] = $parts[0] ?: null;
        }

        return $result;
    }

    protected function generateNIM($data, $rowNumber)
    {
        $prefix = $data['kode_prodi'] ?? 'STD';
        $year = date('y');
        $sequence = str_pad($rowNumber, 3, '0', STR_PAD_LEFT);

        return $prefix . $year . $sequence;
    }

    protected function formatRowForDisplay($row, $headers)
    {
        $result = [];
        foreach ($headers as $index => $header) {
            $headerKey = trim($header) ?: 'Kolom_' . ($index + 1);  // Handle header kosong
            $result[$headerKey] = $row[$index] ?? '';
        }
        return $result;
    }

    protected function getDisplayHeaders($fileHeaders = [])
    {
        $defaultHeaders = ['NO', 'Nama', 'Jur/Kls/Prodi', 'Nilai TOEFL'];

        // Jika header file punya jumlah kolom sama, gunakan header file
        if (count($fileHeaders) === count($defaultHeaders)) {
            return array_map(fn($h) => trim($h) ?: 'Kolom', $fileHeaders);
        }

        return $defaultHeaders;
    }

    protected function previewCSV($fullPath, $scope, $scopeId)
    {
        $data = Excel::toArray([], $fullPath);

        if (empty($data) || empty($data[0])) {
            throw new \Exception('CSV file is empty or invalid');
        }

        $rows = $data[0];
        $headers = array_shift($rows);

        // Bersihkan header tanpa validasi
        $headers = array_map(function ($header, $index) {
            return trim($header) ?: 'Kolom ' . ($index + 1); // Handle header kosong
        }, $headers, array_keys($headers));

        // Ambil 10 baris pertama untuk preview
        $sampleRows = array_slice($rows, 0, 10);
        $processedRows = [];
        $errors = [];

        foreach ($sampleRows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena header di baris 1 dan array mulai 0

            try {
                // Gabungkan header dengan nilai row
                $rowData = array_combine($headers, $row);

                // Asumsikan urutan kolom default (sesuaikan dengan kebutuhan)
                $processedData = [
                    'no' => $row[0] ?? null,
                    'nama' => $row[1] ?? null,
                    'jur_kls_prodi' => $row[2] ?? null,
                    'nilai_toefl' => $row[3] ?? null
                ];

                // Validasi minimal
                $rowErrors = [];
                if (empty($processedData['nama'])) {
                    $rowErrors[] = "Baris {$rowNumber}: Kolom nama tidak boleh kosong";
                }

                if (isset($processedData['nilai_toefl']) && !is_numeric($processedData['nilai_toefl'])) {
                    $rowErrors[] = "Baris {$rowNumber}: Nilai TOEFL harus berupa angka";
                }

                $processedRows[] = [
                    'original' => $rowData,
                    'processed' => $processedData,
                    'errors' => $rowErrors
                ];

                $errors = array_merge($errors, $rowErrors);
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                $processedRows[] = [
                    'original' => array_combine($headers, $row),
                    'processed' => null,
                    'errors' => [$e->getMessage()]
                ];
            }
        }

        return [
            'file_type' => 'csv',
            'headers' => $headers, // Tampilkan header asli dari file
            'total_rows' => count($rows),
            'sample_rows' => $processedRows,
            'preview_count' => count($sampleRows),
            'has_errors' => !empty($errors),
            'errors' => $errors,
            'scope_info' => $this->getScopeInfo($scope, $scopeId)
        ];
    }

    protected function processCSV($fullPath, $scope, $scopeId, $batchId, $uploadedBy)
    {
        $data = Excel::toArray([], $fullPath);

        if (empty($data) || empty($data[0])) {
            throw new \Exception('CSV file is empty or invalid');
        }

        $rows = $data[0];
        $headers = array_shift($rows);

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena header di baris 1 dan array mulai 0

            try {
                // Asumsikan urutan kolom default
                $processedData = [
                    'no' => $row[0] ?? null,
                    'nama' => $row[1] ?? null,
                    'jur_kls_prodi' => $row[2] ?? null,
                    'nilai_toefl' => $row[3] ?? null
                ];

                // Validasi minimal
                if (empty($processedData['nama'])) {
                    throw new \Exception("Kolom nama tidak boleh kosong");
                }

                if (!is_numeric($processedData['nilai_toefl'])) {
                    throw new \Exception("Kolom nilai TOEFL harus berupa angka");
                }

                // Parsing jur/kls/prodi jika diperlukan
                if (!empty($processedData['jur_kls_prodi'])) {
                    $parsed = $this->parseJurKlsProdi($processedData['jur_kls_prodi']);
                    $processedData = array_merge($processedData, $parsed);
                }

                $this->saveStudentAndScore(
                    $processedData,
                    $scope,
                    $scopeId,
                    $batchId,
                    $uploadedBy
                );

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
            }
        }

        return [
            'total_rows' => count($rows),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'batch_id' => $batchId
        ];
    }

    protected function saveStudentAndScore($data, $scope, $scopeId, $batchId, $uploadedBy)
    {
        try {
            DB::beginTransaction();

            // 1. Handle Student Data
            $nim = $data['nim'] ?? $this->generateNIM($data, $batchId);

            $student = Student::firstOrCreate(
                ['student_id' => $nim],
                [
                    'name' => $data['nama'] ?? 'Unknown',
                    'class_id' => $this->resolveClassInfo($data, $scope, $scopeId)['class_id'],
                    'study_program_id' => $this->resolveClassInfo($data, $scope, $scopeId)['study_program_id'],
                    'department_id' => $this->resolveClassInfo($data, $scope, $scopeId)['department_id'],
                    'no' => $data['no'] ?? null,
                ]
            );

            // 2. Handle TOEFL Score
            $scoreData = [
                'student_id' => $student->id,
                'total_score' => $data['nilai_toefl'] ?? 0,
                'dataset_batch' => $batchId,
                'uploaded_by' => $uploadedBy,
                'test_date' => $data['tanggal_test'] ?? now()->format('Y-m-d'),
                'listening_score' => $data['listening'] ?? null,
                'structure_score' => $data['structure'] ?? null,
                'reading_score' => $data['reading'] ?? null,
            ];

            ToeflScore::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'test_date' => $scoreData['test_date']
                ],
                $scoreData
            );

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to save student and score: " . $e->getMessage());
            return false;
        }
    }

    protected function resolveClassInfo($data, $scope, $scopeId)
    {
        $jurKlsProdi = $this->parseJurKlsProdi($data['jur_kls_prodi'] ?? '');

        return $this->determineClassInfo([
            'kode_jurusan' => $jurKlsProdi['kode_jurusan'] ?? null,
            'kode_prodi' => $jurKlsProdi['kode_prodi'] ?? null,
            'kelas' => $jurKlsProdi['kelas'] ?? null
        ], $scope, $scopeId);
    }

    
    protected function determineClassInfo($parsedData, $scope, $scopeId)
    {
        switch ($scope) {
            case 'class':
                $class = ClassModel::with('studyProgram.department')->find($scopeId);
                return [
                    'class_id' => $class->id,
                    'study_program_id' => $class->study_program_id,
                    'department_id' => $class->studyProgram->department_id,
                ];

            case 'study_program':
                $studyProgram = StudyProgram::with('department')->find($scopeId);

                // Gunakan kelas dari parsed data jika ada
                $className = $parsedData['kelas'] ?? 'Default Class';
                $class = ClassModel::firstOrCreate(
                    ['study_program_id' => $scopeId, 'name' => $className],
                    [
                        'academic_year' => date('Y') . '/' . (date('Y') + 1),
                        'semester' => 1,
                    ]
                );

                return [
                    'class_id' => $class->id,
                    'study_program_id' => $studyProgram->id,
                    'department_id' => $studyProgram->department_id,
                ];

            case 'department':
                $department = Department::with('studyPrograms')->find($scopeId);
                $studyProgram = $department->studyPrograms->first();

                if (!$studyProgram) {
                    $studyProgram = StudyProgram::create([
                        'department_id' => $department->id,
                        'code' => 'PRODI01',
                        'name' => 'Program Studi Default'
                    ]);
                }

                $className = $parsedData['kelas'] ?? 'Default Class';
                $class = ClassModel::firstOrCreate(
                    ['study_program_id' => $studyProgram->id, 'name' => $className],
                    [
                        'academic_year' => date('Y') . '/' . (date('Y') + 1),
                        'semester' => 1,
                    ]
                );

                return [
                    'class_id' => $class->id,
                    'study_program_id' => $studyProgram->id,
                    'department_id' => $department->id,
                ];

            case 'campus':
                $department = Department::firstOrCreate(
                    ['name' => 'Departemen Default'],
                    ['code' => 'DEPT01']
                );

                $studyProgram = StudyProgram::firstOrCreate(
                    ['department_id' => $department->id],
                    [
                        'code' => 'PRODI01',
                        'name' => 'Program Studi Default'
                    ]
                );

                $className = $parsedData['kelas'] ?? 'Default Class';
                $class = ClassModel::firstOrCreate(
                    ['study_program_id' => $studyProgram->id, 'name' => $className],
                    [
                        'academic_year' => date('Y') . '/' . (date('Y') + 1),
                        'semester' => 1,
                    ]
                );

                return [
                    'class_id' => $class->id,
                    'study_program_id' => $studyProgram->id,
                    'department_id' => $department->id,
                ];

            default:
                throw new \Exception('Invalid scope for determining class info');
        }
    }

    protected function getScopeInfo($scope, $scopeId)
    {
        switch ($scope) {
            case 'class':
                $class = ClassModel::with('studyProgram.department')->find($scopeId);
                return $class ? $class->fullName : 'Unknown Class';

            case 'study_program':
                $program = StudyProgram::find($scopeId);
                return $program ? $program->name : 'Unknown Program';

            case 'department':
                $department = Department::find($scopeId);
                return $department ? $department->name : 'Unknown Department';

            case 'campus':
                return 'Seluruh Kampus';

            default:
                return 'Unknown Scope';
        }
    }

    public function getRecentUploads($limit = 10)
    {
        return ToeflScore::select('dataset_batch', 'uploaded_by', 'created_at')
            ->with('uploader:id,name,email') // Tambah email jika diperlukan
            ->whereNotNull('dataset_batch')
            ->groupBy('dataset_batch', 'uploaded_by', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $count = ToeflScore::where('dataset_batch', $item->dataset_batch)->count();
                return [
                    'batch_id' => $item->dataset_batch,
                    'uploader' => [
                        'name' => $item->uploader->name ?? 'System',
                        'email' => $item->uploader->email ?? null,
                    ],
                    'count' => $count,
                    'uploaded_at' => $item->created_at->format('Y-m-d H:i:s'),
                    'date_diff' => $item->created_at->diffForHumans(), // Tambahan human readable time
                ];
            });
    }
}
