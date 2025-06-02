@extends('layouts.auth')
<pre>
    {{ print_r($file_info, true) }}
    {{ print_r($cluster_data, true) }}
    {{ print_r($centroids, true) }}
    {{ print_r($recommendations, true) }}
    {{ print_r($student_results, true) }}
    Total Siswa: {{ $total_students }}
    </pre>
    
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
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Cluster Distribution -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Distribusi Klaster</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="clusterChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Pusat Klaster (Centroid)</h5>
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
                                                @foreach($cluster_data['labels'] as $index => $label)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td>{{ $centroids[$index][0] }}</td>
                                                    <td>{{ $centroids[$index][1] }}</td>
                                                    <td>{{ $centroids[$index][2] }}</td>
                                                    <td>{{ $recommendations[$index+1] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Results -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Detail Peserta (Total: {{ $total_students }} siswa)</h5>
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
                                            <th>Keanggotaan Klaster 1</th>
                                            <th>Keanggotaan Klaster 2</th>
                                            <th>Keanggotaan Klaster 3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student_results as $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $student['nama'] }}</td>
                                            <td>{{ $student['nim'] }}</td>
                                            <td>{{ $student['listening'] }}</td>
                                            <td>{{ $student['structure'] }}</td>
                                            <td>{{ $student['reading'] }}</td>
                                            <td>
                                                <span class="badge {{ 
                                                    $student['cluster_label'] === 'Pemula' ? 'bg-warning' : 
                                                    ($student['cluster_label'] === 'Menengah' ? 'bg-info' : 'bg-success') 
                                                }}">
                                                    {{ $student['cluster_label'] }}
                                                </span>
                                            </td>
                                            <td>{{ $student['membership']['cluster_1'] }}</td>
                                            <td>{{ $student['membership']['cluster_2'] }}</td>
                                            <td>{{ $student['membership']['cluster_3'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Export Buttons -->
                    <div class="text-center mt-3">
                        <button id="exportExcel" class="btn btn-primary me-2">
                            <i class="fas fa-file-excel me-2"></i>Export ke Excel
                        </button>
                        <button id="exportPdf" class="btn btn-danger">
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
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 0.75em;
    }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with export buttons
    $('#studentsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success',
                title: 'Hasil Klasterisasi TOEFL',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger',
                title: 'Hasil Klasterisasi TOEFL',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        responsive: true,
        pageLength: 10,
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
        }
    });

    // Initialize Cluster Distribution Chart
    const ctx = document.getElementById('clusterChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($cluster_data['labels']),
            datasets: [{
                label: 'Jumlah Siswa',
                data: @json($cluster_data['counts']),
                backgroundColor: @json($cluster_data['colors']),
                borderColor: @json($cluster_data['colors']),
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
});
</script>
@endsection