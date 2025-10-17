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
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Upload Data Nilai TOEFL</h3>
                    </div>

                    <div class="card-body">
                        <!-- Upload Options Section -->
                        <div class="mb-5">
                            <h4 class="mb-4 border-bottom pb-2">Pilih Jenis Upload</h4>
                            <div class="row">
                                @php
                                    $role = auth()->user()->role;
                                    $availableScopes = [];

                                    if (in_array($role, ['admin', 'kepala_upa', 'wakil_direktur'])) {
                                        $availableScopes = ['campus', 'department', 'study_program'];
                                    } elseif ($role === 'ketua_jurusan') {
                                        $availableScopes = ['department', 'study_program'];
                                    } elseif ($role === 'ketua_prodi') {
                                        $availableScopes = ['study_program'];
                                    }

                                    $icons = [
                                        'campus' => [
                                            'icon' => 'fa-university',
                                            'color' => 'primary',
                                            'label' => 'Politeknik Negeri Lhokseumawe',
                                        ],
                                        'department' => [
                                            'icon' => 'fa-building',
                                            'color' => 'danger',
                                            'label' => 'Jurusan',
                                        ],
                                        'study_program' => [
                                            'icon' => 'fa-graduation-cap',
                                            'color' => 'success',
                                            'label' => 'Program Studi',
                                        ],
                                    ];
                                @endphp

                                <div class="row mb-4">
                                    @foreach ($availableScopes as $scope)
                                        <div class="col-md mb-3">
                                            <div class="card text-center h-100 upload-option"
                                                data-scope="{{ $scope }}">
                                                <div class="card-body">
                                                    <i
                                                        class="fas {{ $icons[$scope]['icon'] }} fa-3x text-{{ $icons[$scope]['color'] }} mb-3"></i>
                                                    <h5>{{ $icons[$scope]['label'] }}</h5>
                                                    <p class="text-muted">Upload data berdasarkan
                                                        {{ str_replace('_', ' ', $scope) }}</p>
                                                    @if ($scope === 'campus')
                                                        <button class="btn btn-{{ $icons[$scope]['color'] }}"
                                                            onclick="showUploadModal('{{ $scope }}', '', 'Politeknik Negeri Lhokseumawe')">Upload</button>
                                                    @else
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn btn-{{ $icons[$scope]['color'] }} dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                Pilih {{ ucfirst(str_replace('_', ' ', $scope)) }}
                                                            </button>
                                                            <ul class="dropdown-menu"
                                                                style="max-height: 300px; overflow-y: auto;">
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
                            </div>
                        </div>

                        <!-- Recent Uploads Section -->
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
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Data TOEFL</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('data.upload') }}" id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="scope" name="cakupan">
                        <input type="hidden" id="scope_id" name="unit_nama">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda akan mengupload data untuk: <strong id="scopeLabel" class="text-primary"></strong>
                        </div>

                        <div class="mb-4">
                            <label for="fileExcel" class="form-label fw-bold">File Data</label>
                            <div class="file-upload-wrapper">
                                <input type="file" class="form-control" id="fileExcel" name="fileExcel"
                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                    required>
                                <div class="form-text">
                                    <ul class="small">
                                        <li><strong>Format yang didukung:</strong> CSV, Excel (.xlsx, .xls)</li>
                                        <li><strong>Struktur kolom:</strong> NO, Nama, Jurusan/Prodi/Kelas, Listening,
                                            Structure, Reading, Total</li>
                                        <li><strong>Contoh format:</strong> TIK / TRKJ / 1A</li>
                                        <li><strong>Ukuran maksimal:</strong> 10MB</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Keterangan (Opsional)</label>
                            <textarea class="form-control" name="description" id="description" rows="2"
                                placeholder="Masukkan keterangan tambahan tentang data ini..."></textarea>
                        </div>

                        <div id="uploadProgress" class="mb-3" style="display: none;">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Proses upload...</span>
                                <span id="progressPercent">0%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary" id="upload-button">
                                <i class="fas fa-upload me-1"></i> Upload Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .file-upload-wrapper {
            position: relative;
        }

        .table th {
            white-space: nowrap;
        }

        .progress {
            background-color: #e9ecef;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function showUploadModal(scope, scopeId = null, scopeName = null) {
            const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            const scopeMap = {
                'campus': 'kampus',
                'department': 'jurusan',
                'study_program': 'prodi',
            };

            document.getElementById('scope').value = scopeMap[scope];
            document.getElementById('scope_id').value = scopeName || scopeId || '';

            let label = '';
            switch (scope) {
                case 'campus':
                    label = 'Politeknik Negeri Lhokseumawe ';
                    break;
                case 'department':
                    label = 'Jurusan: ' + scopeName;
                    break;
                case 'study_program':
                    label = 'Program Studi: ' + scopeName;
                    break;
                default:
                    label = scopeName || scope;
            }
            document.getElementById('scopeLabel').textContent = label;

            // Reset form
            document.getElementById('uploadForm').reset();
            document.getElementById('uploadProgress').style.display = 'none';
            document.getElementById('upload-button').disabled = false;

            // Show modal
            modal.show();
        }

        // Form submission with progress tracking
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const progressBar = document.querySelector('#uploadProgress .progress-bar');
            const progressPercent = document.getElementById('progressPercent');
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadButton = document.getElementById('upload-button');

            uploadProgress.style.display = 'block';
            uploadButton.disabled = true;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', this.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressPercent.textContent = percentComplete + '%';
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    progressBar.classList.remove('progress-bar-animated');
                    progressBar.classList.add('bg-success');
                    progressPercent.textContent = '100%';

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Upload gagal: ' + xhr.responseText);
                    uploadButton.disabled = false;
                    uploadProgress.style.display = 'none';
                }
            };

            xhr.onerror = function() {
                alert('Terjadi kesalahan saat mengupload');
                uploadButton.disabled = false;
                uploadProgress.style.display = 'none';
            };

            xhr.send(formData);
        });
    </script>
@endpush
