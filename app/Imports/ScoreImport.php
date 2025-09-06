<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\StudyProgram;
use App\Models\ToeflScoreEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScoreImport implements ToModel, WithHeadingRow
{
    protected $upload_id;
    protected Collection $departments;
    protected Collection $studyPrograms;

    public function __construct($upload_id)
    {
        $this->upload_id = $upload_id;
        // Muat semua data departemen dan prodi ke memori sekali saja
        $this->departments = Department::all();
        $this->studyPrograms = StudyProgram::all();
    }

    public function headingRow(): int
    {
        return 5;
    }

    private function nullIfNotNumber($value)
    {
        return is_numeric($value) ? $value : null;
    }

    /**
     * Fungsi pencocokan yang sangat kuat untuk Departemen (Jurusan).
     */
    private function findDepartmentId($input)
    {
        if (empty($input)) return null;

        // Bersihkan input secara agresif
        $cleanedInput = trim(strtolower($input));

        // Strategi 1: Cocokkan dengan KODE (case-insensitive)
        $match = $this->departments->firstWhere(fn($dept) => strtolower($dept->code) === $cleanedInput);
        if ($match) return $match->id;

        // Strategi 2: Cocokkan dengan NAMA (case-insensitive)
        $match = $this->departments->firstWhere(fn($dept) => strtolower($dept->name) === $cleanedInput);
        if ($match) return $match->id;

        return null; // Gagal menemukan
    }

    /**
     * Fungsi pencocokan yang sangat kuat untuk Program Studi.
     */
    private function findStudyProgramId($input)
    {
        if (empty($input)) return null;

        // Bersihkan input secara agresif
        $cleanedInput = trim(strtolower($input));

        // Strategi 1: Cocokkan dengan KODE (case-insensitive)
        $match = $this->studyPrograms->firstWhere(fn($prog) => strtolower($prog->code) === $cleanedInput);
        if ($match) return $match->id;

        // Strategi 2: Cocokkan dengan NAMA (case-insensitive)
        $match = $this->studyPrograms->firstWhere(fn($prog) => strtolower($prog->name) === $cleanedInput);
        if ($match) return $match->id;

        return null; // Gagal menemukan
    }


    public function model(array $row)
    {
        // ======================================================================
        // LANGKAH WAJIB: Hentikan proses dan tampilkan isi baris pertama.
        // Hapus atau beri komentar pada baris ini SETELAH Anda mengirimkan hasilnya.
        dd($row);
        // ======================================================================

        if (empty($row['nama'])) {
            return null;
        }

        $jurusanInput = null;
        $prodiInput = null;
        $kelas = null;

        if (!empty($row['jur_prodi_kls'])) {
            $parts = explode('/', $row['jur_prodi_kls']);
            $jurusanInput = trim($parts[0] ?? null);
            $prodiInput = trim($parts[1] ?? null);
            $kelas = trim($parts[2] ?? null);
        }

        $departmentId = $this->findDepartmentId($jurusanInput);
        $studyProgramId = $this->findStudyProgramId($prodiInput);

        // Jika departmentId GAGAL ditemukan, ini adalah error kritis.
        // Catat di log dan lewati baris ini agar tidak menyebabkan error database.
        if ($jurusanInput && is_null($departmentId)) {
            Log::error("KRITIS: Jurusan '{$jurusanInput}' dari Excel tidak dapat dicocokkan dengan data manapun di tabel 'departments'. Baris untuk NIM '{$row['nim']}' dilewati.");
            return null;
        }

        return new ToeflScoreEntry([
            'upload_id' => $this->upload_id,
            'nama' => $row['nama'],
            'nim' => (string) ($row['nim'] ?? null),
            'jurusan' => $jurusanInput,
            'prodi' => $prodiInput,
            'kelas' => $kelas,
            'listening' => $this->nullIfNotNumber($row['listening'] ?? null),
            'structure' => $this->nullIfNotNumber($row['structure'] ?? null),
            'reading' => $this->nullIfNotNumber($row['reading'] ?? null),
            'total_score' => $this->nullIfNotNumber($row['total'] ?? null),
            'department_id' => $departmentId,
            'study_program_id' => $studyProgramId,
        ]);
    }
}
