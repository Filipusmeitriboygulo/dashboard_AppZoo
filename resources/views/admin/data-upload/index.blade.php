@extends('layouts.auth')

@section('title', 'Data Upload')

@section('content')



    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Upload Data Nilai TOEFL</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $role = auth()->user()->role;

                            $availableScopes = [];

                            if (in_array($role, ['admin', 'kepala_upa', 'wakil_direktur'])) {
                                $availableScopes = ['campus', 'department', 'study_program', 'class'];
                            } elseif ($role === 'ketua_jurusan') {
                                $availableScopes = ['department', 'study_program', 'class'];
                            } elseif ($role === 'ketua_prodi') {
                                $availableScopes = ['study_program', 'class'];
                            }

                            $icons = [
                                'campus' => [
                                    'icon' => 'fa-university',
                                    'color' => 'primary',
                                    'label' => 'Seluruh Kampus',
                                ],
                                'department' => [
                                    'icon' => 'fa-building',
                                    'color' => 'success',
                                    'label' => 'Per Jurusan',
                                ],
                                'study_program' => [
                                    'icon' => 'fa-graduation-cap',
                                    'color' => 'warning',
                                    'label' => 'Per Program Studi',
                                ],
                                'class' => [
                                    'icon' => 'fa-users',
                                    'color' => 'info',
                                    'label' => 'Per Kelas',
                                ],
                            ];
                        @endphp

                        <div class="row mb-4">
                            @foreach ($availableScopes as $scope)
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center h-100 upload-option" data-scope="{{ $scope }}">
                                        <div class="card-body">
                                            <i
                                                class="fas {{ $icons[$scope]['icon'] }} fa-3x text-{{ $icons[$scope]['color'] }} mb-3"></i>
                                            <h5>{{ $icons[$scope]['label'] }}</h5>
                                            <p class="text-muted">Upload data berdasarkan
                                                {{ str_replace('_', ' ', $scope) }}</p>
                                            @if ($scope === 'campus')
                                                <button class="btn btn-{{ $icons[$scope]['color'] }}"
                                                    onclick="showUploadModal('{{ $scope }}', '', 'Seluruh Kampus')">Upload</button>
                                            @else
                                                <div class="dropdown">
                                                    <button class="btn btn-{{ $icons[$scope]['color'] }} dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                        Pilih {{ ucfirst(str_replace('_', ' ', $scope)) }}
                                                    </button>
                                                    <ul class="dropdown-menu" style="max-height: 300px; overflow-y: auto;">
                                                        @foreach ($departments as $dept)
                                                            @if ($scope === 'department')
                                                                <li>
                                                                    <button class="dropdown-item"
                                                                        onclick="showUploadModal('department', '{{ $dept->id }}', '{{ $dept->name }}')">
                                                                        {{ $dept->name }}
                                                                    </button>
                                                                </li>
                                                            @elseif ($scope === 'study_program')
                                                                @foreach ($dept->studyPrograms as $program)
                                                                    <li>
                                                                        <button class="dropdown-item"
                                                                            onclick="showUploadModal('study_program', '{{ $program->id }}', '{{ $program->name }}')">
                                                                            {{ $program->name }}
                                                                        </button>
                                                                    </li>
                                                                @endforeach
                                                            @elseif ($scope === 'class')
                                                                @foreach ($dept->studyPrograms as $program)
                                                                    <h6 class="ms-3">{{ $program->name }}</h6>
                                                                    @foreach ($program->classes as $class)
                                                                        <li>
                                                                            <button class="dropdown-item"
                                                                                onclick="showUploadModal('class',  '{{ $class->id }}', '{{ $class->fullname }}')">

                                                                                {{ $class->name }}
                                                                            </button>
                                                                        </li>
                                                                    @endforeach
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h4>Upload Terbaru</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>File Name</th>
                                                <th>Cakupan</th>
                                                <th>Tanggal Upload</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($files as $file)
                                                <tr>
                                                    <td><code>{{ $loop->iteration }}</code></td>
                                                    <td>{{ $file['file_name'] }}</td>
                                                    <td>{{ $file['cakupan'] }}</td>
                                                    <td>{{ $file['waktu_upload'] }}</td>
                                                    <td>
                                                        <a href="{{ route('data.scores', $file['id']) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('data.score-delete', $file['id']) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash-alt"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Belum ada upload data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Data TOEFL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('data.upload') }}" id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="scope" name="cakupan">
                        <input type="hidden" id="scope_id" name="unit_nama">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="mb-3">
                            <label class="form-label">Upload untuk: <span id="scopeLabel"
                                    class="fw-bold text-primary"></span></label>
                        </div>

                        <div class="mb-3">
                            <label for="fileExcel" class="form-label">File Data</label>
                            <input class="form-control" type="file" name="fileExcel" id="fileExcel"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                required>
                            <div class="form-text">
                                <strong>Format yang didukung:</strong> CSV, Excel (.xlsx, .xls)<br>
                                <strong>Struktur Excel:</strong> NO, Nama, Jur/Kls/Prodi, Listening, Structure, Reading,
                                Total<br>
                                <strong>Contoh format Jur/Kls/Prodi:</strong> TIK / TRKJ / 1A<br>
                                Maksimal ukuran file: 10MB
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                        </div>

                        <div id="uploadProgress" class="mb-3" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <div class="text-center mt-2" id="progressText">Mengupload file...</div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="upload-button">
                                <i class="fa fa-upload mr-2"></i> Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        function showUploadModal(scope, scopeId = null, scopeName = null) {
            const modal = new bootstrap.Modal(document.getElementById('uploadModal'));

            // Mapping scope values to match database enum
            const scopeMap = {
                'campus': 'kampus',
                'department': 'jurusan',
                'study_program': 'prodi',
                'class': 'kelas'
            };

            // Set nilai input hidden dan label
            document.getElementById('scope').value = scopeMap[scope]; // Use mapped value
            document.getElementById('scope_id').value = scopeName || scopeId || ''; // Use scopeName as fallback

            // Set display label (unchanged)
            let label = '';
            switch (scope) {
                case 'campus':
                    label = 'Seluruh Kampus';
                    break;
                case 'department':
                    label = 'Jurusan: ' + scopeName;
                    break;
                case 'study_program':
                    label = 'Program Studi: ' + scopeName;
                    break;
                case 'class':
                    label = 'Kelas: ' + scopeName;
                    break;
                default:
                    label = scopeName || scope;
            }

            document.getElementById('scopeLabel').textContent = label;

            // Reset form and progress bar
            document.getElementById('uploadForm').reset();
            const progressBar = document.querySelector('#uploadProgress .progress-bar');
            if (progressBar) progressBar.style.width = '0%';
            const progressText = document.getElementById('progressText');
            if (progressText) progressText.textContent = 'Mengupload file...';
            document.getElementById('uploadProgress').style.display = 'none';
            document.getElementById('upload-button').disabled = false;

            // Tampilkan modal
            modal.show();
        }

        // Form submission handler
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const progressBar = document.querySelector('#uploadProgress .progress-bar');
            const progressText = document.getElementById('progressText');
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadButton = document.getElementById('upload-button');

            // Show progress
            uploadProgress.style.display = 'block';
            uploadButton.disabled = true;
            progressBar.style.width = '10%';
            progressText.textContent = 'Mengupload file...';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Upload gagal');
                }

                // Update progress
                progressBar.style.width = '100%';
                progressText.textContent = 'Upload berhasil! Memproses data...';

                // Reload after delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

            } catch (error) {
                console.error('Upload error:', error);
                progressBar.style.width = '0%';
                progressText.textContent = 'Error: ' + error.message;
                uploadButton.disabled = false;
            }
        });
    </script>
@endpush
