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
            return $this->previewCSV($fullPath, $scope, $scopeId);
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

    protected function previewExcel($fullPath, $scope, $scopeId)
    {
        try {
            $spreadsheet = IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();

            // Convert to array
            $data = $worksheet->toArray();

            // Find header row (contains "NO", "Nama", "Nilai TOEFL", etc.)
            $headerRowIndex = $this->findHeaderRow($data);

            if ($headerRowIndex === -1) {
                throw new \Exception('Cannot find header row. Expected columns: NO, Nama, Jur/Kls/Prodi, Nilai TOEFL');
            }

            $headers = $data[$headerRowIndex];
            $dataRows = array_slice($data, $headerRowIndex + 1);

            // Remove empty rows
            $dataRows = array_filter($dataRows, function ($row) {
                return !empty(array_filter($row));
            });

            // Process sample rows (first 10 for preview)
            $sampleRows = array_slice($dataRows, 0, 10);
            $processedRows = [];
            $errors = [];

            foreach ($sampleRows as $index => $row) {
                $validation = $this->validateExcelRow($row, $headers, $index + $headerRowIndex + 2);

                $processedRows[] = [
                    'original' => $this->formatRowForDisplay($row, $headers),
                    'processed' => $validation['data'],
                    'errors' => $validation['errors']
                ];

                $errors = array_merge($errors, $validation['errors']);
            }

            return [
                'file_type' => 'excel',
                'headers' => $this->getDisplayHeaders(),
                'total_rows' => count($dataRows),
                'sample_rows' => $processedRows,
                'preview_count' => count($sampleRows),
                'has_errors' => !empty($errors),
                'errors' => $errors,
                'scope_info' => $this->getScopeInfo($scope, $scopeId),
                'header_row_index' => $headerRowIndex
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error reading Excel file: ' . $e->getMessage());
        }
    }

    protected function processExcel($fullPath, $scope, $scopeId, $batchId, $uploadedBy)
    {
        $spreadsheet = IOFactory::load($fullPath);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();

        $headerRowIndex = $this->findHeaderRow($data);
        if ($headerRowIndex === -1) {
            throw new \Exception('Cannot find header row');
        }

        $headers = $data[$headerRowIndex];
        $dataRows = array_slice($data, $headerRowIndex + 1);

        // Remove empty rows
        $dataRows = array_filter($dataRows, function ($row) {
            return !empty(array_filter($row));
        });

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($dataRows as $index => $row) {
            $rowNumber = $index + $headerRowIndex + 2;

            try {
                $validation = $this->validateExcelRow($row, $headers, $rowNumber);

                if (!empty($validation['errors'])) {
                    $errorCount++;
                    $errors = array_merge($errors, $validation['errors']);
                    continue;
                }

                $this->saveStudentAndScore(
                    $validation['data'],
                    $scope,
                    $scopeId,
                    $batchId,
                    $uploadedBy
                );

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }

        return [
            'total_rows' => count($dataRows),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'batch_id' => $batchId
        ];
    }

    protected function findHeaderRow($data)
    {
        foreach ($data as $index => $row) {
            if (empty($row)) continue;

            $rowString = strtolower(implode(' ', array_filter($row)));

            // Look for key columns in the header
            if (
                strpos($rowString, 'nama') !== false &&
                (strpos($rowString, 'nilai') !== false || strpos($rowString, 'toefl') !== false) &&
                (strpos($rowString, 'listening') !== false || strpos($rowString, 'structure') !== false)
            ) {
                return $index;
            }
        }
        return -1;
    }

    protected function validateExcelRow($row, $headers, $rowNumber)
    {
        $errors = [];
        $data = [];

        // Map columns based on Excel structure
        $columnMap = $this->mapExcelColumns($headers);

        // Extract NO (if available)
        if (isset($columnMap['no']) && isset($row[$columnMap['no']])) {
            $data['no'] = $row[$columnMap['no']];
        }

        // Extract and validate Nama
        if (!isset($columnMap['nama']) || empty(trim($row[$columnMap['nama']] ?? ''))) {
            $errors[] = "Row {$rowNumber}: Nama is required";
        } else {
            $data['nama'] = trim($row[$columnMap['nama']]);
        }

        // Extract and parse Jur/Kls/Prodi
        if (isset($columnMap['jur_kls_prodi']) && !empty($row[$columnMap['jur_kls_prodi']])) {
            $jurKlsProdi = trim($row[$columnMap['jur_kls_prodi']]);
            $parsed = $this->parseJurKlsProdi($jurKlsProdi);
            $data = array_merge($data, $parsed);
        }

        // Generate NIM if not provided (based on prodi and sequence)
        if (empty($data['nim'] ?? '')) {
            // Generate NIM based on available info or use row number
            $data['nim'] = $this->generateNIM($data, $rowNumber);
        }

        // Extract TOEFL scores
        $scoreColumns = ['listening', 'structure', 'reading', 'total'];
        foreach ($scoreColumns as $scoreType) {
            if (isset($columnMap[$scoreType])) {
                $value = $row[$columnMap[$scoreType]] ?? '';
                if (!is_numeric($value) || $value < 0 || $value > 677) {
                    $errors[] = "Row {$rowNumber}: {$scoreType} score must be between 0-677";
                } else {
                    $data[$scoreType] = (int) $value;
                }
            }
        }

        // Validate total score logic
        if (isset($data['listening']) && isset($data['structure']) && isset($data['reading']) && isset($data['total'])) {
            $calculatedTotal = $data['listening'] + $data['structure'] + $data['reading'];
            if (abs($calculatedTotal - $data['total']) > 10) {
                $errors[] = "Row {$rowNumber}: Total score doesn't match sum of individual scores";
            }
        }

        // Set default test date if not provided
        $data['tanggal_test'] = Carbon::now()->format('Y-m-d');

        return [
            'data' => $data,
            'errors' => $errors
        ];
    }

    protected function mapExcelColumns($headers)
    {
        $map = [];

        foreach ($headers as $index => $header) {
            $headerLower = strtolower(trim($header ?? ''));

            if (strpos($headerLower, 'no') === 0 && strlen($headerLower) <= 3) {
                $map['no'] = $index;
            } elseif (strpos($headerLower, 'nama') !== false) {
                $map['nama'] = $index;
            } elseif (strpos($headerLower, 'jur') !== false || strpos($headerLower, 'kls') !== false || strpos($headerLower, 'prodi') !== false) {
                $map['jur_kls_prodi'] = $index;
            } elseif (strpos($headerLower, 'listening') !== false) {
                $map['listening'] = $index;
            } elseif (strpos($headerLower, 'structure') !== false) {
                $map['structure'] = $index;
            } elseif (strpos($headerLower, 'reading') !== false) {
                $map['reading'] = $index;
            } elseif (strpos($headerLower, 'total') !== false || strpos($headerLower, 'score') !== false) {
                $map['total'] = $index;
            }
        }

        return $map;
    }

    protected function parseJurKlsProdi($jurKlsProdi)
    {
        // Format: "TIK / TRKJ / 1A" or similar
        $parts = array_map('trim', explode('/', $jurKlsProdi));

        $result = [];
        if (count($parts) >= 3) {
            $result['kode_jurusan'] = $parts[0]; // TIK
            $result['kode_prodi'] = $parts[1];   // TRKJ  
            $result['kelas'] = $parts[2];        // 1A
        } elseif (count($parts) >= 2) {
            $result['kode_prodi'] = $parts[0];
            $result['kelas'] = $parts[1];
        } else {
            $result['kelas'] = $jurKlsProdi;
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
            $result[trim($header)] = $row[$index] ?? '';
        }
        return $result;
    }

    protected function getDisplayHeaders()
    {
        return ['NO', 'Nama', 'Jur/Kls/Prodi', 'Listening', 'Structure', 'Reading', 'Total', 'Status'];
    }

    // Include CSV methods for backward compatibility
    protected function previewCSV($fullPath, $scope, $scopeId)
    {
        $data = Excel::toArray([], $fullPath);

        if (empty($data) || empty($data[0])) {
            throw new \Exception('CSV file is empty or invalid');
        }

        $rows = $data[0];
        $headers = array_shift($rows);

        // Clean headers
        $headers = array_map('trim', $headers);
        $headers = array_map('strtolower', $headers);

        // Validate required columns for CSV
        $requiredColumns = ['nim', 'nama', 'listening', 'structure', 'reading', 'total'];
        $missingColumns = array_diff($requiredColumns, $headers);

        if (!empty($missingColumns)) {
            throw new \Exception('Missing required columns: ' . implode(', ', $missingColumns));
        }

        // Process sample rows
        $sampleRows = array_slice($rows, 0, 10);
        $processedRows = [];
        $errors = [];

        foreach ($sampleRows as $index => $row) {
            $rowData = array_combine($headers, $row);
            $validation = $this->validateCSVRow($rowData, $index + 2);

            $processedRows[] = [
                'original' => $rowData,
                'processed' => $validation['data'],
                'errors' => $validation['errors']
            ];

            $errors = array_merge($errors, $validation['errors']);
        }

        return [
            'file_type' => 'csv',
            'headers' => $headers,
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
        $rows = $data[0];
        $headers = array_shift($rows);

        $headers = array_map('trim', $headers);
        $headers = array_map('strtolower', $headers);

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowData = array_combine($headers, $row);
            $rowNumber = $index + 2;

            try {
                $validation = $this->validateCSVRow($rowData, $rowNumber);

                if (!empty($validation['errors'])) {
                    $errorCount++;
                    $errors = array_merge($errors, $validation['errors']);
                    continue;
                }

                $this->saveStudentAndScore(
                    $validation['data'],
                    $scope,
                    $scopeId,
                    $batchId,
                    $uploadedBy
                );

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
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

    protected function validateCSVRow($rowData, $rowNumber)
    {
        $errors = [];
        $data = [];

        // Validate NIM
        $nim = trim($rowData['nim'] ?? '');
        if (empty($nim)) {
            $errors[] = "Row {$rowNumber}: NIM is required";
        } else {
            $data['nim'] = $nim;
        }

        // Validate Name
        $nama = trim($rowData['nama'] ?? '');
        if (empty($nama)) {
            $errors[] = "Row {$rowNumber}: Nama is required";
        } else {
            $data['nama'] = $nama;
        }

        // Validate Scores
        $scores = ['listening', 'structure', 'reading', 'total'];
        foreach ($scores as $score) {
            $value = $rowData[$score] ?? '';
            if (!is_numeric($value) || $value < 0 || $value > 677) {
                $errors[] = "Row {$rowNumber}: {$score} score must be between 0-677";
            } else {
                $data[$score] = (int) $value;
            }
        }

        // Validate Total Score Logic
        if (isset($data['listening']) && isset($data['structure']) && isset($data['reading']) && isset($data['total'])) {
            $calculatedTotal = $data['listening'] + $data['structure'] + $data['reading'];
            if (abs($calculatedTotal - $data['total']) > 10) {
                $errors[] = "Row {$rowNumber}: Total score doesn't match sum of individual scores";
            }
        }

        // Validate Test Date
        $testDate = trim($rowData['tanggal_test'] ?? '');
        if (empty($testDate)) {
            $data['tanggal_test'] = Carbon::now()->format('Y-m-d');
        } else {
            try {
                $data['tanggal_test'] = Carbon::parse($testDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: Invalid date format";
            }
        }

        // Optional fields
        $data['kelas'] = trim($rowData['kelas'] ?? '');
        $data['prodi'] = trim($rowData['prodi'] ?? '');
        $data['jurusan'] = trim($rowData['jurusan'] ?? '');

        return [
            'data' => $data,
            'errors' => $errors
        ];
    }

    protected function saveStudentAndScore($data, $scope, $scopeId, $batchId, $uploadedBy)
    {
        // Find or create student
        $student = Student::where('student_id', $data['nim'])->first();

        if (!$student) {
            $classInfo = $this->determineClassInfo($data, $scope, $scopeId);

            $student = Student::create([
                'student_id' => $data['nim'],
                'name' => $data['nama'],
                'class_id' => $classInfo['class_id'],
                'study_program_id' => $classInfo['study_program_id'],
                'department_id' => $classInfo['department_id'],
            ]);
        }

        // Check if TOEFL score already exists
        $existingScore = ToeflScore::where('student_id', $student->id)
            ->where('test_date', $data['tanggal_test'])
            ->first();

        $scoreData = [
            'listening_score' => $data['listening'],
            'structure_score' => $data['structure'],
            'reading_score' => $data['reading'],
            'total_score' => $data['total'],
            'dataset_batch' => $batchId,
            'uploaded_by' => $uploadedBy,
        ];

        if ($existingScore) {
            $existingScore->update($scoreData);
        } else {
            ToeflScore::create(array_merge($scoreData, [
                'student_id' => $student->id,
                'test_date' => $data['tanggal_test'],
            ]));
        }
    }

    protected function determineClassInfo($data, $scope, $scopeId)
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
                $class = ClassModel::where('study_program_id', $scopeId)->first();
                if (!$class) {
                    $class = ClassModel::create([
                        'study_program_id' => $scopeId,
                        'name' => $data['kelas'] ?? 'Default Class',
                        'academic_year' => date('Y') . '/' . (date('Y') + 1),
                        'semester' => 1,
                    ]);
                }
                return [
                    'class_id' => $class->id,
                    'study_program_id' => $studyProgram->id,
                    'department_id' => $studyProgram->department_id,
                ];

            case 'department':
                $department = Department::with('studyPrograms')->find($scopeId);
                $studyProgram = $department->studyPrograms->first();
                $class = ClassModel::where('study_program_id', $studyProgram->id)->first();

                if (!$class) {
                    $class = ClassModel::create([
                        'study_program_id' => $studyProgram->id,
                        'name' => $data['kelas'] ?? 'Default Class',
                        'academic_year' => date('Y') . '/' . (date('Y') + 1),
                        'semester' => 1,
                    ]);
                }

                return [
                    'class_id' => $class->id,
                    'study_program_id' => $studyProgram->id,
                    'department_id' => $department->id,
                ];

            case 'campus':
                $department = Department::first();
                $studyProgram = $department->studyPrograms->first();
                $class = ClassModel::where('study_program_id', $studyProgram->id)->first();

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
            ->with('uploader:id,name')
            ->whereNotNull('dataset_batch')
            ->groupBy('dataset_batch', 'uploaded_by', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $count = ToeflScore::where('dataset_batch', $item->dataset_batch)->count();
                return [
                    'batch_id' => $item->dataset_batch,
                    'uploader' => $item->uploader->name ?? 'Unknown',
                    'count' => $count,
                    'uploaded_at' => $item->created_at->format('Y-m-d H:i:s'),
                ];
            });
    }
}
