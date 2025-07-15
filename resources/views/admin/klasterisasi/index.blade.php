@extends('layouts.auth')

@section('title', 'Klasterisasi Data')

@section('content')
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-chart-pie me-2"></i> Klasterisasi Data</h4>
            </div>

            {{-- Hanya Admin --}}
            @if (auth()->user()->role === 'admin')
                <div class="card-body">
                    <p class="text-muted">Silakan pilih file dan cakupan data untuk proses klasterisasi.</p>

                    {{-- FORM KLASTERISASI --}}

                    <form method="POST" action="{{ route('klasterisasi.analyze') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cakupan" class="form-label">Pilih Cakupan Data</label>
                                <select class="form-select" id="cakupan" name="cakupan" required>
                                    <option value="">-- Pilih Cakupan --</option>
                                    <option value="kampus">Kampus</option>
                                    <option value="jurusan">Jurusan</option>
                                    <option value="prodi">Program Studi</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="unit_name" class="form-label">Nama Unit</label>
                                <input type="text" class="form-control" id="unit_name" name="unit_name"
                                    placeholder="Contoh: Teknik Informatika" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="file_upload_id" class="form-label">Pilih File</label>
                            <select class="form-select" id="file_upload_id" name="file_upload_id" required>
                                <option value="">-- Pilih File --</option>
                                @foreach ($file_uploads as $file)
                                    <option value="{{ $file->id }}" data-cakupan="{{ $file->cakupan }}"
                                        data-unit="{{ $file->unit_nama }}">
                                        {{ $file->file_name }} ({{ $file->created_at->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="analyzeBtn" disabled>
                                <i class="fas fa-play-circle me-1"></i> Mulai Analisis Klasterisasi
                            </button>
                        </div>
                    </form>
            @endif

            {{-- TABEL FILE YANG PERNAH DIUPLOAD --}}
            <div class="container">
                <div class="mt-5">
                    <h5>Riwayat File yang Pernah Diupload</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama File</th>
                                    <th>Cakupan</th>
                                    <th>Status</th>
                                    <th>Waktu Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($file_uploads as $upload)
                                    <tr>
                                        <td>{{ $upload->file_name }}</td>
                                        <td>{{ ucfirst($upload->cakupan) }} - {{ $upload->unit_nama }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $upload->status_klasterisasi === 'sudah' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($upload->status_klasterisasi) }}
                                            </span>
                                        </td>
                                        <td>{{ $upload->created_at->format('d-m-Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" style="gap: 8px;">
                                                <!-- Tombol Lihat Hasil -->
                                                <a href="{{ route('klasterisasi.result', $upload->id) }}"
                                                    class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm hover-effect"
                                                    title="Lihat Hasil Klasterisasi"
                                                    style="min-width: 120px; transition: all 0.3s ease;">
                                                    <i class="fas fa-eye me-1"></i>
                                                    <span class="fw-medium">Lihat Detail</span>
                                                </a>

                                                <!-- Tombol Proses Ulang -->
                                                {{-- Hanya Admin --}}
                                                @if (auth()->user()->role === 'admin')
                                                    <form action="{{ route('klasterisasi.reanalyze', $upload->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm hover-effect"
                                                            onclick="return confirm('Yakin ingin proses ulang file ini?')"
                                                            title="Proses ulang file ini"
                                                            style="min-width: 140px; transition: all 0.3s ease;">
                                                            <i class="fas fa-sync-alt me-1"></i>
                                                            <span class="fw-medium">Analisis Ulang</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>

                                        <style>
                                            .hover-effect:hover {
                                                transform: translateY(-2px);
                                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                                            }

                                            .btn-primary {
                                                background: linear-gradient(135deg, #3b82f6, #2563eb);
                                                border: none;
                                            }

                                            .btn-warning {
                                                background: linear-gradient(135deg, #f59e0b, #d97706);
                                                border: none;
                                                color: white;
                                            }

                                            .btn-warning:hover {
                                                color: white;
                                            }
                                        </style>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada file yang diunggah</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            @if (session('debug'))
                <div class="mt-4 p-3 bg-light border">
                    <h5>Debug Information</h5>
                    <pre>{{ print_r(session('debug'), true) }}</pre>
                </div>
            @endif
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize disabled states
            $('#unit_name').prop('disabled', true);
            $('#file_upload_id').prop('disabled', true);
            $('#analyzeBtn').prop('disabled', true);

            // Cakupan change handler
            $('#cakupan').change(function() {
                const cakupan = $(this).val();
                const unitName = $('#unit_name').val();

                // Enable/disable unit name field based on cakupan selection
                $('#unit_name').prop('disabled', !cakupan);

                // If cakupan is selected, enable file selection
                if (cakupan) {
                    filterFilesByScope(cakupan, unitName);
                    $('#file_upload_id').prop('disabled', false);
                } else {
                    $('#file_upload_id').prop('disabled', true);
                    $('#file_upload_id').val('');
                    $('#analyzeBtn').prop('disabled', true);
                }
            });

            // Unit name change handler
            $('#unit_name').change(function() {
                const cakupan = $('#cakupan').val();
                const unitName = $(this).val();

                if (cakupan && unitName) {
                    filterFilesByScope(cakupan, unitName);
                    $('#file_upload_id').prop('disabled', false);
                } else {
                    $('#file_upload_id').prop('disabled', true);
                    $('#file_upload_id').val('');
                    $('#analyzeBtn').prop('disabled', true);
                }
            });

            // File selection change handler
            $('#file_upload_id').change(function() {
                const fileSelected = $(this).val();
                $('#analyzeBtn').prop('disabled', !fileSelected);
            });

            // Filter files by scope and unit name
            function filterFilesByScope(cakupan, unitName) {
                $('#file_upload_id').val('');
                $('#analyzeBtn').prop('disabled', true);

                // Show all options first
                $('#file_upload_id option').show();

                if (cakupan) {
                    // Hide options that don't match the selected cakupan and unit name
                    $('#file_upload_id option').each(function() {
                        const optionCakupan = $(this).data('cakupan');
                        const optionUnitName = $(this).data('unit-name') || '';

                        // Show only matching files
                        const shouldShow = optionCakupan === cakupan &&
                            (unitName === '' || optionUnitName === unitName);
                        $(this).toggle(shouldShow);
                    });

                    // If no files match, show message
                    if ($('#file_upload_id option:visible').length === 0) {
                        $('#file_upload_id').append(
                            $('<option>', {
                                value: '',
                                text: 'Tidak ada file yang tersedia',
                                disabled: true,
                                selected: true
                            })
                        );
                    }
                }
            }
        });
    </script>
@endpush
