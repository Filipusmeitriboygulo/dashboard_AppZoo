<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\UploadLog;

class KlasterisasiExport implements FromCollection, WithHeadings
{
    protected $upload;

    public function __construct(UploadLog $upload)
    {
        $this->upload = $upload;
    }

    public function collection()
    {
        return $this->upload->clusterResults->map(function ($result) {
            return [
                'Nama' => $result->toeflScoreEntry->nama ?? '-',
                'NIM' => $result->toeflScoreEntry->nim ?? '-',
                'Listening' => $result->toeflScoreEntry->listening ?? '-',
                'Structure' => $result->toeflScoreEntry->structure ?? '-',
                'Reading' => $result->toeflScoreEntry->reading ?? '-',
                'Total Score' => $result->toeflScoreEntry->total_score ?? '-',
                'Cluster' => $result->cluster,
                'Membership Cluster 1' => round($result->membership_cluster1 * 100, 2) . '%',
                'Membership Cluster 2' => round($result->membership_cluster2 * 100, 2) . '%',
                'Membership Cluster 3' => round($result->membership_cluster3 * 100, 2) . '%',
                'Insight' => $result->insight,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIM',
            'Listening',
            'Structure',
            'Reading',
            'Total Score',
            'Cluster',
            'Membership Cluster 1',
            'Membership Cluster 2',
            'Membership Cluster 3',
            'Insight',
        ];
    }
}
