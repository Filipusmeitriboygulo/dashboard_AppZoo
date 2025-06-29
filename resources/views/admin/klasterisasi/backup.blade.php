@extends('layouts.auth')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-chart-pie"></i> Hasil Klasterisasi TOEFL
                            <small class="float-end">
                                Upload ID: {{ $upload->id }} |
                                Tanggal: {{ $upload->created_at->format('d/m/Y H:i') }}
                            </small>
                        </h3>
                    </div>

                    <div class="card-body">
                        @if ($upload->clusterResults->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> Data hasil klasterisasi belum tersedia
                            </div>
                        @else
                            <!-- Visualisasi 3D -->
                            {{-- <pre>{{ Str::limit($visualization, 100) }}</pre> lihat sebagian base64 --}}
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h4><i class="fas fa-project-diagram"></i> Visualisasi Cluster</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            @if ($visualization)
                                            <div class="my-4 text-center">
                                                <h5>Visualisasi Klasterisasi</h5>
                                                <img src="{{ $visualization }}" class="img-fluid" />

                                            </div>
                                        @else
                                            <p class="text-danger">Gambar visualisasi tidak tersedia.</p>
                                        @endif
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistik Cluster -->
                            <div class="row mb-4">
                                <div class="col-md">
                                    <div class="card h-100">
                                        <div class="card-header bg-success text-white">
                                            <h4><i class="fas fa-chart-pie"></i> Distribusi Cluster</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="clusterChart" height="250"></canvas>
                                        </div>
                                    </div>
                                </div>

                                 <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-success text-white">
                                            <h4><i class="fas fa-shapes"></i> Informasi Cluster</h4>
                                        </div>
                                         <div class="card-body">
                                            @if (isset($cluster_info['centroids']))
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Cluster</th>
                                                                <th>Listening</th>
                                                                <th>Structure</th>
                                                                <th>Reading</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($ ['centroids'] as $cluster => $centroid)
                                                                <tr>
                                                                    <td>Cluster {{ $cluster }}</td>
                                                                    <td>{{ number_format($centroid[0], 2) }}</td>
                                                                    <td>{{ number_format($centroid[1], 2) }}</td>
                                                                    <td>{{ number_format($centroid[2], 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    Data centroid tidak tersedia
                                                </div>
                                            @endif
                                        </div> 
                                     </div> 
                                 </div>
                            </div>

                            <!-- Rekomendasi -->
                            @if (isset($cluster_info['recommendations']))
                                <div class="card mb-4">
                                    <div class="card-header bg-warning text-dark">
                                        <h4><i class="fas fa-lightbulb"></i> Rekomendasi Pembelajaran</h4>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($cluster_info['recommendations'] as $cluster => $rec)
                                            <div class="mb-4 p-3 border rounded">
                                                <h5 class="d-flex align-items-center">
                                                    <span
                                                        class="badge bg-{{ $cluster == 1 ? 'danger' : ($cluster == 2 ? 'warning' : 'success') }} me-2">
                                                        Cluster {{ $cluster }}
                                                    </span>
                                                </h5>
                                                <ul>
                                                    @foreach ($rec as $item)
                                                        <li>{{ $item }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Tabel Hasil Lengkap -->
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h4><i class="fas fa-table"></i> Detail Hasil Klasterisasi</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="resultsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>NIM</th>
                                                    <th>Listening</th>
                                                    <th>Structure</th>
                                                    <th>Reading</th>
                                                    <th>Total</th>
                                                    <th>Cluster</th>
                                                    <th>Membership</th>
                                                    <th>Insight</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($results as $result)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $result->toeflScoreEntry->nama ?? '-' }}</td>
                                                        <td>{{ $result->toeflScoreEntry->nim ?? '-' }}</td>
                                                        <td>{{ $result->toeflScoreEntry->listening ?? '-' }}</td>
                                                        <td>{{ $result->toeflScoreEntry->structure ?? '-' }}</td>
                                                        <td>{{ $result->toeflScoreEntry->reading ?? '-' }}</td>
                                                        <td>{{ $result->toeflScoreEntry->total_score ?? '-' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $result->cluster == 1 ? 'danger' : ($result->cluster == 2 ? 'warning' : 'success') }}">
                                                                Cluster {{ $result->cluster }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <ul class="mb-0 ps-3">
                                                                <li>Cluster 1:
                                                                    {{ round($result->membership_cluster1 * 100, 1) }}%
                                                                </li>
                                                                <li>Cluster 2:
                                                                    {{ round($result->membership_cluster2 * 100, 1) }}%
                                                                </li>
                                                                <li>Cluster 3:
                                                                    {{ round($result->membership_cluster3 * 100, 1) }}%
                                                                </li>
                                                            </ul>
                                                        </td>
                                                        <td>{{ $result->insight }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart untuk Distribusi Cluster
        $(document).ready(function() {
            // Hitung distribusi cluster
            let clusterCounts = {
                1: 0,
                2: 0,
                3: 0
            };

            @foreach ($results as $result)
                clusterCounts[{{ $result->cluster }}]++;
            @endforeach

            // Buat chart
            const ctx = document.getElementById('clusterChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cluster 1', 'Cluster 2', 'Cluster 3'],
                    datasets: [{
                        data: [clusterCounts[1], clusterCounts[2], clusterCounts[3]],
                        backgroundColor: [
                            '#dc3545', // Cluster 1 (Danger)
                            '#ffc107', // Cluster 2 (Warning)
                            '#28a745' // Cluster 3 (Success)
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // DataTable
            $('#resultsTable').DataTable({
                responsive: true,
                dom: '<"top"Bf>rt<"bottom"lip><"clear">',
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                pageLength: 25,
                order: [
                    [7, 'asc']
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endsection


