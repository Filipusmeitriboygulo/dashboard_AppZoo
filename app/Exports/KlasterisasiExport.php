<?php

namespace App\Exports;

use App\Models\UploadLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KlasterisasiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $upload_id;
    protected $upload;

    public function __construct($upload_id)
    {
        $this->upload_id = $upload_id;
        $this->upload = UploadLog::findOrFail($upload_id);
    }

    public function collection()
    {
        return $this->upload->clusterResults()->with('toeflScoreEntry')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Mahasiswa',
            'NIM',
            'Listening Score',
            'Structure Score',
            'Reading Score',
            'Total Score',
            'Cluster',
            'Status Lulus',
            'Insight & Rekomendasi'
        ];
    }

    public function map($result): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $result->toeflScoreEntry->nama ?? '-',
            $result->toeflScoreEntry->nim ?? '-',
            $result->toeflScoreEntry->listening ?? '-',
            $result->toeflScoreEntry->structure ?? '-',
            $result->toeflScoreEntry->reading ?? '-',
            $result->toeflScoreEntry->total_score ?? '-',
            'Cluster ' . $result->cluster,
            $result->status_lulus ?? '-',
            $result->insight ?? 'Tidak ada insight tersedia'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = $this->collection()->count() + 1; // +1 for heading
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            "A1:J$rowCount" => [ // Apply border to all cells
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // No
            'C:C' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // NIM
            'D:G' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Scores
            'H:H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Cluster
            'I:I' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Status Lulus
            'J:J' => [
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_TOP
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 25,  // Nama
            'C' => 15,  // NIM
            'D' => 12,  // Listening
            'E' => 12,  // Structure
            'F' => 12,  // Reading
            'G' => 12,  // Total
            'H' => 12,  // Cluster
            'I' => 15,  // Status Lulus
            'J' => 50,  // Insight
        ];
    }

    public function title(): string
    {
        return 'Klasterisasi_' . $this->upload->id;
    }
}
