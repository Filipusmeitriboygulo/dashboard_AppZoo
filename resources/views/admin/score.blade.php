@extends('layouts.auth')

@section('title', 'Data Upload')
@section('content')
<div class="container">
    <h4>Data Score: <span class="text-primary">{{ $file->file_name }}</span></h4>
    <p class="text-muted">Status Klasterisasi: <code>{{ $file->status_klasterisasi }}</code></p>
    <hr>

    <a href="{{ route('home') }}" class="btn btn-secondary mb-3">← Kembali ke Data Upload</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Kelas</th>
                    <th>Listening</th>
                    <th>Structure</th>
                    <th>Reading</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scores as $i => $score)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $score->nama }}</td>
                        <td>{{ $score->nim }}</td>
                        <td>{{ $score->jurusan }}</td>
                        <td>{{ $score->prodi }}</td>
                        <td>{{ $score->kelas }}</td>
                        <td>{{ $score->listening }}</td>
                        <td>{{ $score->structure }}</td>
                        <td>{{ $score->reading }}</td>
                        <td>{{ $score->total_score }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada data score untuk file ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
