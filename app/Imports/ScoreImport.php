<?php
namespace App\Imports;

use App\Models\Score;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScoreImport implements ToModel, WithHeadingRow
{
    protected $toefl_file_id;

    public function __construct($toefl_file_id)
    {
        $this->toefl_file_id = $toefl_file_id;
    }

    public function headingRow(): int
    {
        return 5; // Atur sesuai baris header Excel kamu
    }
    private function nullIfNotNumber($value)
    {
        // Jika kosong, null, atau tidak numerik, return null
        return (is_numeric($value)) ? $value : null;
    }
    public function model(array $row)
    {
        // dd($row);
        if (empty($row['nama'])) {
            return null;
        }

        $jurusan = $prodi = $kelas = null;
        if (!empty($row['jur_prodi_kls'])) {
            $parts = explode('/', $row['jur_prodi_kls']);
            $jurusan = trim($parts[0] ?? null);
            $prodi = trim($parts[1] ?? null);
            $kelas = trim($parts[2] ?? null);
        }

        return new Score([
            'toefl_file_id' => $this->toefl_file_id,
            'nama' => $row['nama'],
            'nim_mahasiswa' => (string) ($row['nim_mahasiswa'] ?? null),
            'jurusan' => $jurusan,
            'prodi' => $prodi,
            'kelas' => $kelas,
            'listening_score' => $this->nullIfNotNumber($row['l'] ?? null),
            'structure_score' => $this->nullIfNotNumber($row['s'] ?? null),
            'reading_score' => $this->nullIfNotNumber($row['r'] ?? null),
            'listening' => $this->nullIfNotNumber($row['listening'] ?? null),
            'structure' => $this->nullIfNotNumber($row['structure'] ?? null),
            'reading' => $this->nullIfNotNumber($row['reading'] ?? null),
            'total' => $this->nullIfNotNumber($row['total'] ?? null),
        ]);

    }
}
