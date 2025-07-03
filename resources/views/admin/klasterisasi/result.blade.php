@extends('layouts.auth')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <!-- Enhanced Header Card -->
                <div class="card shadow-sm mb-4 border-0" style="border-radius: 12px;">
                    <div class="card-header bg-gradient-primary text-white py-3" style="border-radius: 12px 12px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">
                                    <i class="fas fa-chart-network me-2"></i> Hasil Klasterisasi TOEFL
                                </h3>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-white text-dark me-2">
                                        <i class="fas fa-hashtag me-1"></i> Upload ID: {{ $upload->id }}
                                    </span>
                                    <span class="badge bg-white text-dark me-2">
                                        <i class="fas fa-hashtag me-1"></i> Nama File: {{ $upload->file_name }}
                                    </span>
                                    <span class="text-white-50">
                                        <i class="far fa-clock me-1"></i> {{ $upload->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-white text-dark py-2">
                                    <i class="fas fa-users me-1"></i> {{ $results->count() }} Mahasiswa
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($upload->clusterResults->isEmpty())
                    <div class="alert alert-warning shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fs-4 me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Data hasil klasterisasi belum tersedia</h5>
                                <p class="mb-0">Silakan tunggu atau lakukan proses klasterisasi terlebih dahulu</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Main Content Grid -->
                    <div class="row g-4 mb-4">
                        <!-- Visualization Card -->
                        <div class="col-lg-7">
                            <div class="card h-100 shadow-sm border-0" style="border-radius: 12px;">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center"
                                    style="border-radius: 12px 12px 0 0;">
                                    <h4 class="mb-0 text-dark">
                                        <i class="fas fa-project-diagram me-2"></i> Visualisasi Klaster
                                    </h4>
                                    @if ($visualization)
                                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleFullscreen(this)">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                    @endif
                                </div>
                                <div class="card-body p-4">
                                    @if ($visualization)
                                        <div class="text-center" id="visualization-container">
                                            <img src="{{ $visualization }}"
                                                class="img-fluid rounded shadow visualization-img thumbnail"
                                                style="max-height: 400px; width: auto; cursor: pointer;"
                                                alt="Visualisasi Hasil Klasterisasi TOEFL" onclick="openLightbox(this)">
                                            <div class="mt-3">
                                                <small class="text-muted">Distribusi mahasiswa berdasarkan klaster skor
                                                    TOEFL</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info m-0">
                                            <i class="fas fa-info-circle me-2"></i> Data visualisasi belum tersedia
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div id="lightbox" class="lightbox">
                                <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
                                <div class="lightbox-content">
                                    <img id="lightbox-image" class="lightbox-image" src="">
                                    <div class="lightbox-caption" id="lightbox-caption"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Lightbox HTML -->


                        <!-- Distribution Card -->
                        <div class="col-lg-5">
                            <div class="card h-100 shadow-sm border-0" style="border-radius: 12px;">
                                <div class="card-header bg-white" style="border-radius: 12px 12px 0 0;">
                                    <h4 class="mb-0 text-dark">
                                        <i class="fas fa-chart-pie me-2"></i> Distribusi Cluster
                                    </h4>
                                </div>
                                <div class="card-body pt-0 position-relative" style="min-height: 300px;">
                                    <div class="chart-container" style="height: 250px; position: relative;">
                                        <canvas id="clusterBarChart"></canvas>
                                        <div id="chartError"
                                            class="alert alert-danger d-none position-absolute top-50 start-50 translate-middle w-75">
                                        </div>
                                        <div id="chartLoading" class="position-absolute top-50 start-50 translate-middle">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <small class="text-muted">Distribusi mahasiswa per klaster</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommendations Section -->
                    @if (isset($cluster_info['recommendations']))
                        <div class="card shadow-sm mb-4 border-0" style="border-radius: 12px;">

                            <div class="card-body p-4">
                                <div class="row g-4">
                                    @foreach ($cluster_info['recommendations'] as $cluster => $rec)
                                        <div class="col-md-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div
                                                    class="card-header bg-{{ $cluster == 1 ? 'danger' : ($cluster == 2 ? 'warning' : 'success') }} text-white">
                                                    <h5 class="mb-0">
                                                        <i
                                                            class="fas fa-{{ $cluster == 1 ? 'exclamation-triangle' : ($cluster == 2 ? 'hourglass-half' : 'check-circle') }} me-2"></i>
                                                        Cluster {{ $cluster }}
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($rec as $item)
                                                            <li class="mb-2 d-flex align-items-start">
                                                                <span
                                                                    class="badge bg-{{ $cluster == 1 ? 'danger' : ($cluster == 2 ? 'warning' : 'success') }} me-2 mt-1"
                                                                    style="min-width: 20px;">
                                                                    <i class="fas fa-arrow-right"
                                                                        style="font-size: 0.7rem;"></i>
                                                                </span>
                                                                <span>{{ $item }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Results Table -->
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center"
                            style="border-radius: 12px 12px 0 0;">
                            <h4 class="mb-0 text-dark">
                                <i class="fas fa-table me-2"></i> Detail Hasil Klasterisasi
                            </h4>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2" id="exportExcel">
                                    <i class="fas fa-file-excel me-1"></i> Excel
                                </button>
                                <button class="btn btn-sm btn-outline-danger" id="exportPDF">
                                    <i class="fas fa-file-pdf me-1"></i> PDF
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="resultsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="50">No</th>
                                            <th>Mahasiswa</th>
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
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                            style="width: 32px; height: 32px;">
                                                            {{ substr($result->toeflScoreEntry->nama ?? '?', 0, 1) }}
                                                        </div>
                                                        <span>{{ $result->toeflScoreEntry->nama ?? '-' }}</span>
                                                    </div>
                                                </td>
                                                <td class="align-middle">{{ $result->toeflScoreEntry->nim ?? '-' }}</td>
                                                <td class="align-middle">{{ $result->toeflScoreEntry->listening ?? '-' }}
                                                </td>
                                                <td class="align-middle">{{ $result->toeflScoreEntry->structure ?? '-' }}
                                                </td>
                                                <td class="align-middle">{{ $result->toeflScoreEntry->reading ?? '-' }}
                                                </td>
                                                <td class="align-middle">
                                                    <strong>{{ $result->toeflScoreEntry->total_score ?? '-' }}</strong>
                                                </td>
                                                <td class="align-middle">
                                                    <span
                                                        class="badge bg-{{ $result->cluster == 1 ? 'danger' : ($result->cluster == 2 ? 'warning' : 'success') }} py-2">
                                                        Cluster {{ $result->cluster }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <ul class="mb-0 ps-3">
                                                        @foreach (collect($result->membership)->sortKeys() as $key => $value)
                                                            @php
                                                                $label = Str::startsWith($key, 'Membership_Cluster_')
                                                                    ? 'Cluster ' .
                                                                        Str::after($key, 'Membership_Cluster_')
                                                                    : $key;
                                                            @endphp
                                                            <li>{{ $label }}: {{ round((float) $value * 100, 1) }}%
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>


                                                <td class="align-middle">
                                                    <button class="btn btn-sm btn-outline-secondary insight-btn"
                                                        data-bs-toggle="tooltip" title="Lihat insight"
                                                        data-insight="{{ $result->insight }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                </td>
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

    <!-- Insight Modal -->
    <div class="modal fade" id="insightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i> Detail Insight
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="insightContent">
                    Loading insight...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chart
            const initClusterChart = () => {
                const ctx = document.getElementById('clusterBarChart');
                const chartError = document.getElementById('chartError');
                const chartLoading = document.getElementById('chartLoading');

                try {
                    // Data from controller
                    const clusterData = @json($cluster_counts);

                    if (!clusterData || Object.keys(clusterData).length === 0) {
                        throw new Error('Data klaster tidak tersedia');
                    }

                    // Calculate total for percentages
                    const total = Object.values(clusterData).reduce((sum, count) => sum + count, 0);

                    // Prepare chart data
                    // const clusterData = @json($cluster_counts);

                    const labels = Object.keys(clusterData).map(key => {
                        const num = key.replace('cluster_', '');
                        return `Cluster ${num}`;
                    });

                    const data = Object.values(clusterData);

                    // Buat warna otomatis (pakai array warna atau hue)
                    const backgroundColors = labels.map((_, index) => {
                        const hue = (index * 60) % 360; // Hue dinamis
                        return `hsl(${hue}, 70%, 60%)`;
                    });


                    // Chart configuration
                    const config = {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Mahasiswa',
                                data: data,
                                backgroundColor: backgroundColors,
                                borderColor: backgroundColors.map(color => color.replace('0.7',
                                    '1')),
                                borderWidth: 1,
                                borderRadius: 4,
                                barPercentage: 0.7
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const percentage = (context.raw / total * 100).toFixed(1);
                                            return `${context.raw} mahasiswa (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        stepSize: 1
                                    },
                                    grid: {
                                        drawBorder: false
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    };

                    // Hide loading and render chart
                    chartLoading.classList.add('d-none');
                    new Chart(ctx, config);

                } catch (error) {
                    console.error('Error creating chart:', error);
                    chartLoading.classList.add('d-none');
                    chartError.classList.remove('d-none');
                    chartError.textContent = 'Gagal memuat data distribusi klaster: ' + error.message;
                }
            };

            // Initialize tooltips
            const initTooltips = () => {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            };

            // Initialize insight modal
            const initInsightModal = () => {
                const insightButtons = document.querySelectorAll('.insight-btn');
                const insightModal = new bootstrap.Modal(document.getElementById('insightModal'));
                const insightContent = document.getElementById('insightContent');

                insightButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const insight = this.getAttribute('data-insight');
                        insightContent.textContent = insight || 'Tidak ada insight tersedia';
                        insightModal.show();
                    });
                });
            };

            // Initialize all components
            initClusterChart();
            initTooltips();
            initInsightModal();

            // Fungsi Toggle Screen Visualisasi 
            // Lightbox functionality
            document.addEventListener('DOMContentLoaded', function() {
                // Get the lightbox elements
                const lightbox = document.getElementById('custom-lightbox');
                const lightboxImg = document.getElementById('custom-lightbox-img');
                const lightboxCaption = document.getElementById('custom-lightbox-caption');
                const closeBtn = document.querySelector('.custom-lightbox-close');

                // Get all trigger elements
                const triggers = document.querySelectorAll('.lightbox-trigger');

                // Add click event to all triggers
                triggers.forEach(trigger => {
                    trigger.addEventListener('click', function() {
                        lightbox.style.display = 'block';
                        lightboxImg.src = this.src;
                        lightboxCaption.innerHTML = this.alt;
                        document.body.style.overflow = 'hidden'; // Disable scrolling
                    });
                });

                // Close lightbox
                closeBtn.addEventListener('click', function() {
                    lightbox.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Enable scrolling
                });

                // Close when clicking outside image
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox) {
                        lightbox.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                });

                // Close with ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && lightbox.style.display === 'block') {
                        lightbox.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                });
            });

            // Original fullscreen function
            function toggleFullscreen(button) {
                const container = document.getElementById('visualization-container');
                if (!document.fullscreenElement) {
                    if (container.requestFullscreen) {
                        container.requestFullscreen();
                        button.innerHTML = '<i class="fas fa-compress"></i>';
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                        button.innerHTML = '<i class="fas fa-expand"></i>';
                    }
                }
            }

            // Export buttons functionality
            document.getElementById('exportExcel')?.addEventListener('click', function() {
                window.location.href = '{{ route('export.excel', ['upload_id' => $upload->id]) }}';
            });

            document.getElementById('exportPDF')?.addEventListener('click', function() {
                window.location.href = '{{ route('export.pdf', ['upload_id' => $upload->id]) }}';
            });

        });
    </script>
@endpush

@section('styles')
    <style>
        /* Improved chart styling */
        g .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        #clusterBarChart {
            width: 100% !important;
            height: 100% !important;
        }

        /* Loading states */
        #chartLoading {
            z-index: 10;
        }

        #chartError {
            z-index: 5;
        }

        /* Card improvements */
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-weight: 600;
        }

        /* Table improvements */
        .table {
            font-size: 0.9rem;
        }

        .table th {
            font-weight: 600;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .thead-light th {
            background-color: #f8f9fa;
        }

        /* Avatar styling */
        .avatar {
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Progress bar styling */
        .progress-thin {
            height: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .chart-container {
                height: 200px;
            }

            .table-responsive {
                font-size: 0.8rem;
            }
        }

        /* Badge improvements */
        .badge {
            font-weight: 500;
            padding: 5px 10px;
        }

        /* style fungsi toggle  */
        .fullscreen-img {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: contain;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            cursor: zoom-out;
            padding: 20px;
            box-sizing: border-box;
        }


        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            text-align: center;
        }

        .lightbox-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .lightbox-image {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .lightbox-caption {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: #fff;
            padding: 10px 0;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .thumbnail {
            cursor: pointer;
            transition: 0.3s;
        }

        .thumbnail:hover {
            opacity: 0.8;
        }
    </style>
@endsection
