@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Klasterisasi Data</h2>
                    </div>

                    <div class="card-body">
                        <div class="mb-4">
                            <p class="text-muted">
                                Klasterisasi adalah teknik pengelompokan data yang memungkinkan kita menemukan pola
                                tersembunyi
                                dalam dataset. Dengan memilih file yang telah diupload, sistem akan menganalisis data dan
                                mengelompokkannya ke dalam klaster-klaster yang memiliki karakteristik serupa.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('klasterisasi.analyze') }}">
                            @csrf

                            <div class="form-group">
                                <label for="file_upload_id">Pilih File untuk Analisis Klasterisasi:</label>
                                <select class="form-control" id="file_upload_id" name="file_upload_id" required>
                                    <option value="">-- Pilih File --</option>
                                    @foreach ($file_uploads as $file)
                                        <option value="{{ $file->id }}">{{ $file->name }} (Uploaded:
                                            {{ $file->created_at->format('d M Y') }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-chart-pie mr-2"></i> Analisis Klasterisasi
                                </button>
                            </div>
                        </form>

                        @if (session('debug'))
                            <div class="mt-4 p-3 bg-light rounded">
                                <h5>Debug Information:</h5>
                                <pre>{{ print_r(session('debug'), true) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
