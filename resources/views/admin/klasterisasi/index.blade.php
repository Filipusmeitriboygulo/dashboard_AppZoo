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

                            {{-- Input untuk Kampus (akan tersembunyi) --}}
                            <div class="col-md-6 d-none" id="kampusContainer">
                                <label for="unit_name_kampus" class="form-label">Nama Kampus</label>
                                <input type="text" class="form-control" id="unit_name_kampus" name="unit_name_kampus"
                                    value="Politeknik Negeri Lhokseumawe" readonly>
                            </div>

                            {{-- Dropdown untuk Jurusan --}}
                            <div class="col-md-6 d-none" id="jurusanContainer">
                                <label for="unit_name_jurusan" class="form-label">Pilih Jurusan</label>
                                <select class="form-select" id="unit_name_jurusan" name="unit_name_jurusan">
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Dropdown untuk Prodi berdasarkan Jurusan --}}
                            <div class="col-md-6 d-none" id="prodiContainer">
                                <label for="unit_name_prodi" class="form-label">Pilih Program Studi</label>
                                <select class="form-select" id="unit_name_prodi" name="unit_name_prodi">
                                    <option value="">-- Pilih Program Studi --</option>
                                    <!-- Opsi prodi akan diisi melalui JavaScript -->
                                </select>
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
                </div>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Data program studi dari database (dikirim dari controller)
            const studyPrograms = @json($studyPrograms);

            // Initialize disabled states
            $('#file_upload_id').prop('disabled', true);
            $('#analyzeBtn').prop('disabled', true);

            // Cakupan change handler
            $('#cakupan').change(function() {
                const cakupan = $(this).val();

                // Sembunyikan semua container terlebih dahulu
                $('#kampusContainer, #jurusanContainer, #prodiContainer').addClass('d-none');

                // Reset semua input/select
                $('#unit_name_kampus, #unit_name_jurusan, #unit_name_prodi').val('');

                // Tampilkan container yang sesuai dengan pilihan
                if (cakupan === 'kampus') {
                    $('#kampusContainer').removeClass('d-none');
                    $('#unit_name_kampus').val('Politeknik Negeri Lhokseumawe');
                } else if (cakupan === 'jurusan') {
                    $('#jurusanContainer').removeClass('d-none');
                } else if (cakupan === 'prodi') {
                    $('#prodiContainer').removeClass('d-none');
                    populateStudyPrograms();
                }

                // Enable file selection
                $('#file_upload_id').prop('disabled', false);
                filterFilesByScope(cakupan);
            });

            // Jurusan change handler
            $('#unit_name_jurusan').change(function() {
                const jurusanId = $(this).val();
                const jurusanName = $(this).find('option:selected').text();

                if (jurusanId) {
                    filterFilesByScope('jurusan', jurusanName);

                    // Jika prodi container terlihat, perbarui daftar prodi
                    if (!$('#prodiContainer').hasClass('d-none')) {
                        populateStudyPrograms(jurusanId);
                    }
                }
            });

            // Prodi change handler
            $('#unit_name_prodi').change(function() {
                const prodiName = $(this).find('option:selected').text();
                if (prodiName) {
                    filterFilesByScope('prodi', prodiName);
                }
            });

            // File selection change handler
            $('#file_upload_id').change(function() {
                const fileSelected = $(this).val();
                $('#analyzeBtn').prop('disabled', !fileSelected);
            });

            // Fungsi untuk mengisi dropdown program studi
            function populateStudyPrograms(departmentId = null) {
                const prodiSelect = $('#unit_name_prodi');
                prodiSelect.empty().append('<option value="">-- Pilih Program Studi --</option>');

                studyPrograms.forEach(program => {
                    // Jika departmentId diberikan, hanya tampilkan prodi dari jurusan tersebut
                    if (!departmentId || program.department_id == departmentId) {
                        prodiSelect.append(
                            $('<option>', {
                                value: program.id,
                                text: program.name
                            })
                        );
                    }
                });
            }

            // Filter files by scope and unit name
            function filterFilesByScope(cakupan, unitName = '') {
                $('#file_upload_id').val(''); // Reset pilihan file

                // Jika kampus, set unitName ke 'Politeknik Negeri Lhokseumawe'
                if (cakupan === 'kampus') {
                    unitName = 'Politeknik Negeri Lhokseumawe';
                }

                $('#file_upload_id option[data-cakupan]').each(function() {
                    const optionCakupan = $(this).data('cakupan');
                    const optionUnit = $(this).data('unit');

                    // Tampilkan hanya file dengan cakupan dan unit yang sesuai
                    const isMatch = optionCakupan === cakupan &&
                        (unitName === '' || optionUnit === unitName);

                    $(this).toggle(isMatch);
                });

                // Tampilkan pesan jika tidak ada file yang cocok
                if ($('#file_upload_id option:visible').length === 0) {
                    $('#file_upload_id').append(
                        $('<option>', {
                            value: '',
                            text: 'Tidak ada file yang tersedia untuk pilihan ini',
                            disabled: true,
                            selected: true
                        })
                    );
                    $('#analyzeBtn').prop('disabled', true);
                }
            }
        });
    </script>
@endpush
