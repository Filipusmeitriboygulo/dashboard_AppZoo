@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h2>Hasil Klasterisasi</h2>
                        <p class="mb-0">File: {{ $filename }}</p>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Distribusi Klaster</h5>
                                        <canvas id="clusterChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Rekomendasi</h5>
                                        <ul class="list-group">
                                            @foreach ($results['recommendations'] as $recommendation)
                                                <li class="list-group-item">
                                                    <strong>{{ $recommendation['Cluster'] }}:</strong>
                                                    {{ $recommendation['Rekomendasi'] }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Listening</th>
                                        <th>Structure</th>
                                        <th>Reading</th>
                                        <th>Total</th>
                                        <th>Klaster</th>
                                        <th>Rekomendasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['student_results'] as $student)
                                        <tr>
                                            <td>{{ $student['Nama'] ?? '-' }}</td>
                                            <td>{{ $student['Listening'] }}</td>
                                            <td>{{ $student['Structure'] }}</td>
                                            <td>{{ $student['Reading'] }}</td>
                                            <td>{{ $student['Total'] ?? $student['Listening'] + $student['Structure'] + $student['Reading'] }}
                                            </td>
                                            <td>{{ $student['Cluster_Label'] }}</td>
                                            <td>{{ $student['Rekomendasi'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('klasterisasi') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('clusterChart').getContext('2d');
        const clusterData = @json($results['cluster_counts']);

        const labels = Object.keys(clusterData);
        const data = Object.values(clusterData);
        const backgroundColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)'
        ];

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endsection
@endsection
