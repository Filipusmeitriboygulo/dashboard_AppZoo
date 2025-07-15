@extends('layouts.auth')


@php
    function cluster_color($cluster)
    {
        $hue = (($cluster - 1) * 60) % 360;
        return "hsl($hue, 70%, 60%)";
    }

@endphp

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <!-- Enhanced Header Card -->
                <div class="card shadow-lg border-0 mb-4 overflow-hidden style="border-radius: 12px; border: none;">
                    <div class="card-header bg-gradient-primary text-white py-3" style="border-radius: 12px 12px 0 0;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-chart-network fa-lg me-2"></i>
                                    <h3 class="mb-0">Hasil Klasterisasi TOEFL</h3>
                                </div>
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <span class="badge bg-white bg-opacity-20 text-dark">
                                        <i class="fas fa-hashtag me-1"></i> Upload ID: {{ $upload->id }}
                                    </span>
                                    <span class="badge bg-white bg-opacity-20 text-dark">
                                        <i class="fas fa-file-alt me-1"></i> Nama File :{{ $upload->file_name }}
                                    </span>
                                    <span class="text-dark text-opacity-75 small">
                                        <i class="far fa-clock me-1"></i> Tanggal Upload
                                        :{{ $upload->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex flex-column ms-3" style="min-width: 180px;">
                                <div class="alert alert-light alert-sm mb-2 p-2 text-center">
                                    <span class="d-block fw-bold">Total</span>
                                    {{ $totalStudents }} Mahasiswa
                                </div>
                                <div class="alert alert-success alert-sm mb-2 p-2 text-center">
                                    <span class="d-block fw-bold">Lulus</span>
                                    {{ $totalLulus }} ({{ round(($totalLulus / $totalStudents) * 100, 1) }}%)
                                </div>
                                <div class="alert alert-danger alert-sm p-2 text-center">
                                    <span class="d-block fw-bold">Tidak Lulus</span>
                                    {{ $totalTidakLulus }} ({{ round(($totalTidakLulus / $totalStudents) * 100, 1) }}%)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .alert-sm {
                        padding: 0.35rem 0.5rem;
                        font-size: 0.85rem;
                        border-radius: 8px;
                    }

                    .bg-opacity-20 {
                        background-color: rgba(255, 255, 255, 0.2);
                    }

                    .text-opacity-75 {
                        opacity: 0.75;
                    }
                </style>

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
                            <div class="card shadow-lg border-0 mb-4 overflow-hidden" style="border-radius: 12px;">
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
                                                class="img-fluid rounded shadow visualization-img thumbnail lightbox-trigger"
                                                style="max-height: 400px; width: auto; cursor: pointer;"
                                                alt="Visualisasi Hasil Klasterisasi TOEFL">

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
                        </div>

                        <!-- Lightbox HTML -->


                        <!-- Distribution Card -->
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 mb-4 overflow-hidden" style="border-radius: 12px;">
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
                @endif
                </tbody>


                {{-- Informasi Klaster  --}}

                <div class="row">
                    @if (isset($cluster_info['recommendations']))
                        <div class="card shadow-lg border-0 mb-4 overflow-hidden" style="border-radius: 12px;">
                            <div class="card-header bg-gradient-info text-white py-3" style="border-radius: 12px 12px 0 0;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-pie fa-lg me-3"></i>
                                    <h4 class="mb-0">Analisis Klaster & Level CEFR</h4>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-info">
                                            <tr>
                                                <th class="py-3 px-4" style="width: 20%;">Cluster</th>
                                                <th class="py-3 px-4">Karakteristik & Rekomendasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Sort clusters numerically by their number
                                                $sortedClusters = collect($clusterInsights)->sortBy(function (
                                                    $item,
                                                    $key,
                                                ) {
                                                    return (int) $item['cluster'];
                                                });
                                            @endphp

                                            @foreach ($sortedClusters as $cluster => $info)
                                                <tr class="border-top">
                                                    <td class="px-4 py-3 align-top fw-semibold">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-primary rounded-circle me-2"
                                                                style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                                                                {{ $info['cluster'] }}
                                                            </span>
                                                            Cluster {{ $info['cluster'] }}
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach ($info['insights'] as $insight)
                                                                <li class="mb-2 d-flex">
                                                                    <i class="fas fa-circle text-info me-2 mt-1"
                                                                        style="font-size: 8px;"></i>
                                                                    <span>{!! nl2br(e($insight)) !!}</span>

                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Standard Info -->
                                <div class="p-4 border-top bg-light">
                                    <div class="alert alert-primary border-0 mb-0 d-flex align-items-center">
                                        <i class="fas fa-graduation-cap fa-2x me-3 text-primary"></i>
                                        <div>
                                            <h5 class="alert-heading mb-1">Standar Kelulusan TOEFL PNL</h5>
                                            <p class="mb-0">Minimal skor total TOEFL yang harus dicapai adalah <strong
                                                    class="text-primary">400</strong> poin untuk dinyatakan lulus.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Recommendations Section -->
                @if (isset($cluster_info['recommendations']))
                    <div class="card shadow-sm mb-4 border-0" style="border-radius: 12px;">

                        <div class="card-body p-4">
                            <div class="row g-4">
                                @foreach ($cluster_info['recommendations'] as $cluster => $rec)
                                    @php
                                        $clusterIndex = $cluster - 1;
                                        $hue = ($clusterIndex * 60) % 360;
                                        $color = "hsl($hue, 70%, 60%)";
                                    @endphp

                                    <div class="col-md-4">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-header text-white"
                                                style="background-color: {{ $color }};">
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
                                                            <span class="badge me-2 mt-1"
                                                                style="background-color: {{ $color }}; color: #fff;">
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
                <div class="card shadow-lg border-0 mb-4 overflow-hidden" style="border-radius: 12px;">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center"
                        style="border-radius: 12px 12px 0 0;">
                        <h4 class="mb-0 text-dark">
                            <i class="fas fa-table me-2"></i> Detail Hasil Klasterisasi
                        </h4>
                        <div>
                            <button class="btn btn-sm btn-outline-success me-2" id="exportExcel">
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
                                        <th>Status Lulus</th>
                                        <th>Insight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td class="align-middle">
                                                {{ ($results->currentPage() - 1) * $results->perPage() + $loop->iteration }}
                                            </td>

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
                                                @php
                                                    $clusterIndex = $result->cluster - 1; // karena index dimulai dari 0
                                                    $hue = ($clusterIndex * 60) % 360;
                                                    $color = "hsl($hue, 70%, 60%)";
                                                @endphp

                                                <span class="badge py-2"
                                                    style="background-color: {{ $color }}; color: #fff;">
                                                    Cluster {{ $result->cluster }}
                                                </span>

                                            </td>
                                            <td>
                                                <ul class="mb-0 ps-3">
                                                    @foreach (collect($result->membership)->sortKeys() as $key => $value)
                                                        @php
                                                            $label = Str::startsWith($key, 'Membership_Cluster_')
                                                                ? 'Cluster ' . Str::after($key, 'Membership_Cluster_')
                                                                : $key;
                                                        @endphp
                                                        <li>{{ $label }}: {{ round((float) $value * 100, 1) }}%
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="align-middle">
                                                @if (isset($result->status_lulus))
                                                    <span
                                                        class="badge {{ $result->status_lulus === 'Lulus' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $result->status_lulus }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <button class="btn btn-sm btn-outline-secondary insight-btn"
                                                    data-bs-toggle="tooltip" title="Lihat insight"
                                                    data-insight="{!! nl2br(e($result->insight)) !!}">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white py-3 px-4">
                            <div class="d-flex justify-content-center">
                                {{ $results->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                    </div>
                </div>
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

    {{-- Lightbox  --}}
    <div id="lightbox" class="lightbox">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <div class="lightbox-content">
            <img id="lightbox-image" class="lightbox-image" src="">
            <div class="lightbox-caption" id="lightbox-caption"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            // 1. Chart Cluster

            const initClusterChart = () => {
                const ctx = document.getElementById('clusterBarChart');
                const chartError = document.getElementById('chartError');
                const chartLoading = document.getElementById('chartLoading');

                try {
                    const clusterData = @json($cluster_counts);
                    if (!clusterData || Object.keys(clusterData).length === 0) {
                        throw new Error('Data klaster tidak tersedia');
                    }

                    const sortedKeys = Object.keys(clusterData).sort((a, b) => {
                        return parseInt(a.replace('cluster_', '')) - parseInt(b.replace('cluster_',
                            ''));
                    });

                    const total = sortedKeys.reduce((sum, key) => sum + clusterData[key], 0);
                    const labels = sortedKeys.map(key => `Cluster ${key.replace('cluster_', '')}`);

                    const data = sortedKeys.map(key => clusterData[key]);
                    const backgroundColors = sortedKeys.map(key => {
                        const index = parseInt(key.replace('cluster_', '')) - 1;
                        const hue = (index * 60) % 360;
                        return `hsl(${hue}, 70%, 60%)`;
                    });

                    const config = {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Mahasiswa',
                                data: data,
                                backgroundColor: backgroundColors,
                                borderColor: backgroundColors,
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
                                },
                                font: {
                                    weight: 'bold',
                                    size: 14
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

                    chartLoading.classList.add('d-none');
                    new Chart(ctx, config);
                } catch (error) {
                    chartLoading.classList.add('d-none');
                    chartError.classList.remove('d-none');
                    chartError.textContent = 'Gagal memuat data distribusi klaster: ' + error.message;
                }
            };

            // 2. Tooltip Bootstrap
            const initTooltips = () => {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
            };

            // 3. Insight Modal
            const initInsightModal = () => {
                const insightButtons = document.querySelectorAll('.insight-btn');
                const insightModal = new bootstrap.Modal(document.getElementById('insightModal'));
                const insightContent = document.getElementById('insightContent');

                insightButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const insight = this.getAttribute('data-insight');
                        insightContent.innerHTML = insight || 'Tidak ada insight tersedia';
                        insightModal.show();
                    });
                });
            };

            // 4. Lightbox
            // 4. Lightbox
            const initLightbox = () => {
                const triggers = document.querySelectorAll('.lightbox-trigger');
                const lightbox = document.getElementById('lightbox');
                const lightboxImage = document.getElementById('lightbox-image');
                const lightboxCaption = document.getElementById('lightbox-caption');

                const openLightbox = (src, alt) => {
                    lightbox.classList.add('active');
                    lightboxImage.src = src;
                    lightboxCaption.innerText = alt || 'Visualisasi Klaster';
                    document.body.style.overflow = 'hidden';
                };

                const closeLightbox = () => {
                    lightbox.classList.remove('active');
                    document.body.style.overflow = 'auto';
                };

                triggers.forEach(trigger => {
                    trigger.addEventListener('click', function() {
                        openLightbox(this.src, this.alt);
                    });
                });

                document.querySelector('.lightbox-close').addEventListener('click', closeLightbox);

                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox) {
                        closeLightbox();
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === "Escape" && lightbox.classList.contains('active')) {
                        closeLightbox();
                    }
                });
            };

            // Tambahkan ini ke init functions
            initLightbox();

            // 5. Fullscreen toggle
            window.toggleFullscreen = function(button) {
                const container = document.getElementById('visualization-container');
                if (!document.fullscreenElement) {
                    container.requestFullscreen?.();
                    button.innerHTML = '<i class="fas fa-compress"></i>';
                } else {
                    document.exitFullscreen?.();
                    button.innerHTML = '<i class="fas fa-expand"></i>';
                }
            }

            // 6. Export Button
            document.getElementById('exportExcel')?.addEventListener('click', function() {
                window.location.href = '{{ route('export.excel', ['upload_id' => $upload->id]) }}';
            });

            document.getElementById('exportPDF')?.addEventListener('click', function() {
                window.location.href = '{{ route('export.pdf', ['upload_id' => $upload->id]) }}';
            });

            // INIT semua
            initClusterChart();
            initTooltips();
            initInsightModal();
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


        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            box-sizing: border-box;
            transition: opacity 0.3s ease;
        }

        .lightbox.active {
            display: flex;
        }

        .lightbox-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            max-height: 100%;
            max-width: 100%;
            overflow: auto;
        }

        .lightbox-image {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .lightbox-caption {
            color: white;
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
        }
    </style>
@endsection
