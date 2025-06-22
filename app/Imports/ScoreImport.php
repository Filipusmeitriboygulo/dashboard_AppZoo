<?php

namespace App\Imports;

use App\Models\ToeflScoreEntry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScoreImport implements ToModel, WithHeadingRow
{
    protected $upload_id;

    public function __construct($upload_id)
    {
        $this->$upload_id = $upload_id;
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

        return new ToeflScoreEntry([
            'toefl_file_id' => $this->upload_id,
            'nama' => $row['nama'],
            'nim' => (string) ($row['nim'] ?? null),
            'jurusan' => $jurusan,
            'prodi' => $prodi,
            'kelas' => $kelas,
            // 'listening_score' => $this->nullIfNotNumber($row['l'] ?? null),
            // 'structure_score' => $this->nullIfNotNumber($row['s'] ?? null),
            // 'reading_score' => $this->nullIfNotNumber($row['r'] ?? null),
            'listening' => $this->nullIfNotNumber($row['listening'] ?? null),
            'structure' => $this->nullIfNotNumber($row['structure'] ?? null),
            'reading' => $this->nullIfNotNumber($row['reading'] ?? null),
            'total_score' => $this->nullIfNotNumber($row['total'] ?? null),
        ]);
    }
}
