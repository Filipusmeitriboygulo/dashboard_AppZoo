@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-chart-pie mr-2"></i>Hasil Klasterisasi
                            <small class="float-end">
                                File: {{ $file_info['name'] }} |
                                Upload: {{ $file_info['uploaded_at'] }}
                            </small>
                        </h3>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Charts Section -->
                        <div class="row mb-4">
                            <!-- Bar Chart -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">Distribusi Klaster</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container" style="height: 300px;">
                                            <canvas id="barChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Radar Chart -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">Pusat Klaster</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container" style="height: 300px;">
                                            <canvas id="radarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Centroids Table -->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">Detail Pusat Klaster</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Klaster</th>
                                                        <th>Listening</th>
                                                        <th>Structure</th>
                                                        <th>Reading</th>
                                                        <th>Rekomendasi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($analysis_data['centroids'] as $cluster => $centroid)
                                                        <tr>
                                                            <td>{{ $cluster }}</td>
                                                            <td>{{ number_format($centroid[0], 2) }}</td>
                                                            <td>{{ number_format($centroid[1], 2) }}</td>
                                                            <td>{{ number_format($centroid[2], 2) }}</td>
                                                            <td>
                                                                @foreach ($analysis_data['recommendations'] as $rec)
                                                                    @if ($rec['Cluster'] === $cluster)
                                                                        {{ $rec['Rekomendasi'] }}
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Students Table -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    Detail Peserta (Total: {{ $analysis_data['total_students'] }} siswa)
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="studentsTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIM</th>
                                                <th>Listening</th>
                                                <th>Structure</th>
                                                <th>Reading</th>
                                                <th>Klaster</th>
                                                <th>Rekomendasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($analysis_data['student_results'] as $student)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $student['Nama'] }}</td>
                                                    <td>{{ $student['NIM'] }}</td>
                                                    <td>{{ $student['Listening'] }}</td>
                                                    <td>{{ $student['Structure'] }}</td>
                                                    <td>{{ $student['Reading'] }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $student['Cluster_Label'] === 'Pemula'
                                                                ? 'bg-warning'
                                                                : ($student['Cluster_Label'] === 'Menengah'
                                                                    ? 'bg-info'
                                                                    : 'bg-success') }}">
                                                            {{ $student['Cluster_Label'] }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $student['Rekomendasi'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Export Buttons -->
                        <div class="text-center mt-4">
                            <button class="btn btn-primary me-2">
                                <i class="fas fa-file-excel me-2"></i>Export ke Excel
                            </button>
                            <button class="btn btn-danger">
                                <i class="fas fa-file-pdf me-2"></i>Export ke PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .chart-container {
            position: relative;
            width: 100%;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 0.75em;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#studentsTable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                pageLength: 10
            });

            // Initialize Charts
            @isset($chart_data)
                const chartData = @json($chart_data);

                // Helper function to convert hex to rgba
                function hexToRgba(hex, alpha) {
                    const r = parseInt(hex.slice(1, 3), 16);
                    const g = parseInt(hex.slice(3, 5), 16);
                    const b = parseInt(hex.slice(5, 7), 16);
                    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
                }

                // Bar Chart
                const barCtx = document.getElementById('barChart').getContext('2d');
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Jumlah Siswa',
                            data: chartData.counts,
                            backgroundColor: [
                                chartData.colors.Pemula,
                                chartData.colors.Menengah,
                                chartData.colors.Mahir
                            ],
                            borderColor: [
                                chartData.colors.Pemula,
                                chartData.colors.Menengah,
                                chartData.colors.Mahir
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                // Radar Chart
                const radarCtx = document.getElementById('radarChart').getContext('2d');
                new Chart(radarCtx, {
                    type: 'radar',
                    data: {
                        labels: ['Listening', 'Structure', 'Reading'],
                        datasets: [{
                                label: 'Pemula',
                                data: chartData.centroids.Pemula,
                                backgroundColor: hexToRgba(chartData.colors.Pemula, 0.2),
                                borderColor: chartData.colors.Pemula,
                                borderWidth: 2
                            },
                            {
                                label: 'Menengah',
                                data: chartData.centroids.Menengah,
                                backgroundColor: hexToRgba(chartData.colors.Menengah, 0.2),
                                borderColor: chartData.colors.Menengah,
                                borderWidth: 2
                            },
                            {
                                label: 'Mahir',
                                data: chartData.centroids.Mahir,
                                backgroundColor: hexToRgba(chartData.colors.Mahir, 0.2),
                                borderColor: chartData.colors.Mahir,
                                borderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: {
                                    display: true
                                },
                                suggestedMin: 0,
                                suggestedMax: 100
                            }
                        }
                    }
                });
            @endisset
        });
    </script>

