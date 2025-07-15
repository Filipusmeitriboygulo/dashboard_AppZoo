<!DOCTYPE html>
<html>

<head>
    <title>Hasil Klasterisasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
            line-height: 1.4;
        }

        .header-flex-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .header-left,
        .header-right {
            flex: 1;
            min-width: 250px;
        }

        .header-right {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            font-size: 9px;
        }


        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 120px;
            font-weight: bold;
            padding: 3px 0;
        }

        .info-value {
            display: table-cell;
            padding: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            padding: 8px 4px;
            text-align: center;
            font-size: 9px;
        }

        td {
            padding: 6px 4px;
            vertical-align: top;
            word-wrap: break-word;
        }

        /* Specific column widths */
        .col-no {
            width: 4%;
        }

        .col-nama {
            width: 15%;
        }

        .col-nim {
            width: 12%;
        }

        .col-listening {
            width: 8%;
        }

        .col-structure {
            width: 8%;
        }

        .col-reading {
            width: 8%;
        }

        .col-total {
            width: 8%;
        }

        .col-cluster {
            width: 10%;
        }

        .col-status {
            width: 10%;
        }


        .col-insight {
            width: 27%;
        }


        /* Text wrapping for insight column */
        .insight-text {
            word-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
            line-height: 1.3;
            max-width: 200px;
        }

        /* Cluster styling */
        .cluster-badge {
            background-color: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }

        /* Center align for numeric columns */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Page break handling */
        .page-break {
            page-break-before: always;
        }

        /* Summary section */
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
        }

        /* Responsive table for better PDF rendering */
        @media print {
            body {
                margin: 0;
                font-size: 9px;
            }

            table {
                font-size: 8px;
            }

            .insight-text {
                font-size: 7px;
                line-height: 1.2;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Hasil Klasterisasi TOEFL</h2>

        {{-- Hitung  Total Lusu, dan Distribusi Klaster--}}
        @php
            $clusterCounts = $results->groupBy('cluster')->map->count();
            $totalStudents = $results->count();

            $totalLulus = $results->where('status_lulus', 'Lulus')->count();
            $totalTidakLulus = $results->where('status_lulus', 'Tidak Lulus')->count();
        @endphp

        <div class="header-flex-container">
            <!-- Kiri: Info Upload -->
            <div class="header-left">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Upload ID:</div>
                        <div class="info-value">{{ $upload->id }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal:</div>
                        <div class="info-value">{{ $upload->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Cakupan:</div>
                        <div class="info-value">{{ $upload->cakupan ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama File:</div>
                        <div class="info-value">{{ $upload->file_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Unit:</div>
                        <div class="info-value">{{ $upload->unit_nama ?? '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Total Mahasiswa:</div>
                        <div class="info-value">{{ $results->count() }} orang</div>
                    </div>
                </div>
            </div>

            <!-- Kanan: Ringkasan -->
            <div class="header-right">
                <h3 style="font-size: 11px; margin-top: 0;">Ringkasan Distribusi</h3>
                <div class="info-grid">
                    @foreach ($clusterCounts as $cluster => $count)
                        <div class="info-row">
                            <div class="info-label">Cluster {{ $cluster }}:</div>
                            <div class="info-value">{{ $count }} mahasiswa
                                ({{ round(($count / $totalStudents) * 100, 1) }}%)
                            </div>
                        </div>
                    @endforeach
                    <div class="info-row">
                        <div class="info-label">Total Lulus:</div>
                        <div class="info-value">{{ $totalLulus }} mahasiswa
                            ({{ round(($totalLulus / $totalStudents) * 100, 1) }}%)</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tidak Lulus:</div>
                        <div class="info-value">{{ $totalTidakLulus }} mahasiswa
                            ({{ round(($totalTidakLulus / $totalStudents) * 100, 1) }}%)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama Mahasiswa</th>
                <th class="col-nim">NIM</th>
                <th class="col-listening">Listening</th>
                <th class="col-structure">Structure</th>
                <th class="col-reading">Reading</th>
                <th class="col-total">Total</th>
                <th class="col-cluster">Cluster</th>
                <th class="col-status">Status Lulus</th>
                <th class="col-insight">Insight & Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $i => $result)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $result->toeflScoreEntry->nama ?? '-' }}</td>
                    <td class="text-center">{{ $result->toeflScoreEntry->nim ?? '-' }}</td>
                    <td class="text-center">{{ $result->toeflScoreEntry->listening ?? '-' }}</td>
                    <td class="text-center">{{ $result->toeflScoreEntry->structure ?? '-' }}</td>
                    <td class="text-center">{{ $result->toeflScoreEntry->reading ?? '-' }}</td>
                    <td class="text-center"><strong>{{ $result->toeflScoreEntry->total_score ?? '-' }}</strong></td>
                    <td class="text-center">
                        <span class="cluster-badge">Cluster {{ $result->cluster }}</span>
                    </td>
                    <td class="text-center">
                        {{ $result->status_lulus ?? '-' }}
                    </td>

                    <td>
                        <div class="insight-text">
                            {{ $result->insight ?? 'Tidak ada insight tersedia' }}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Section -->



    <div style="margin-top: 30px; font-size: 8px; color: #666; text-align: center;">
        Dicetak pada: {{ now()->format('d M Y H:i:s') }} |
        Halaman ini berisi {{ $results->count() }} data mahasiswa
    </div>
</body>

</html>
