@extends('layouts.auth')

@section('title', 'Data Upload')

@section('content')
    <div class="container-fluid">
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
                                                                    @foreach ($program->classes as $class)
                                                                        <li>
                                                                            <button class="dropdown-item"
                                                                                onclick="showUploadModal('class', '{{ $class->id }}', '{{ $class->name }}')">
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
                                                <th>Path</th>
                                                <th>Tanggal Upload</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($files as $file)
                                                <tr>
                                                    <td><code>{{ $file['id'] }}</code></td>
                                                    <td>{{ $file['file_name'] }}</td>
                                                    <td>{{ $file['file_path'] }}</td>
                                                    <td>{{ $file['uploaded_at'] }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.data-upload.scores', $file['id']) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
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
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="scope" name="scope">
                        <input type="hidden" id="scope_id" name="scope_id">

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

                        <div id="uploadProgress" class="mb-3" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="submitUpload()">Upload &
                                Preview</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.showUploadModal = function(scope, scopeId = null, scopeName = null) {
                const scopeInput = document.getElementById('scope');
                const scopeIdInput = document.getElementById('scope_id');
                const scopeLabel = document.getElementById('scopeLabel');
                const uploadForm = document.getElementById('uploadForm');
                const uploadProgress = document.getElementById('uploadProgress');
                const uploadModalEl = document.getElementById('uploadModal');

                scopeInput.value = scope;
                scopeIdInput.value = scopeId || '';

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
                        break;
                }

                scopeLabel.textContent = label;
                uploadForm.reset();
                uploadProgress.style.display = 'none';
                const progressBar = uploadProgress.querySelector('.progress-bar');
                if (progressBar) progressBar.style.width = '0%';

                const modal = bootstrap.Modal.getOrCreateInstance(uploadModalEl);
                modal.show();
            };
        });

        // async function submitUpload() {
        //     const form = document.getElementById('uploadForm');
        //     const formData = new FormData(form);
        //     const progressBar = document.queryS public
        //     function upload(Request $request) {
        //         $request - > validate([
        //             'fileExcel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        //         ]);

        //         $user = Auth::user();
        //         $file = $request - > file('fileExcel');
        //         $filename = 'toefl_'.time().
        //         '.'.$file - > getClientOriginalExtension();
        //         $path = $file - > storeAs('uploads/toefl', $filename, 'public');

        //         $log = UploadLog::create([
        //             'user_id' => $user - > id,
        //             'file_name' => $filename,
        //             'cakupan' => $request - > scope ?? 'campus',
        //             'unit_nama' => $request - > scope_id ?? 'ALL',
        //             'waktu_upload' => now(),
        //             'status_klasterisasi' => 'pending',
        //         ]);

        //         return response() - > json([
        //             'success' => true,
        //             'message' => 'File uploaded successfully',
        //             'batch_id' => $log - > id,
        //             'file_path' => $path,
        //             'file_type' => $file - > getClientOriginalExtension(),
        //         ]);
        //     }
        //     selector('#uploadProgress .progress-bar');
        //     const uploadProgress = document.getElementById('uploadProgress');
        //     const uploadModal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));

        //     uploadProgress.style.display = 'block';

        //     try {
        //         const response = await fetch("{{ route('toefl.upload') }}", {
        //             method: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        //                 'Accept': 'application/json',
        //             },
        //             body: formData,
        //         });

        //         // Handle non-JSON responses
        //         const contentType = response.headers.get("content-type");
        //         if (!contentType || !contentType.includes("application/json")) {
        //             const text = await response.text();
        //             throw new Error(text.includes('<html') ?
        //                 'Session mungkin habis, silakan refresh halaman' :
        //                 'Respons server tidak valid');
        //         }

        //         const result = await response.json();

        //         if (!response.ok) {
        //             throw new Error(result.message || 'Upload gagal');
        //         }

        //         progressBar.style.width = '100%';
        //         alert(result.message || 'Upload berhasil!');
        //         uploadModal.hide();
        //         location.reload();

        //     } catch (error) {
        //         console.error('Upload error:', error);
        //         alert(`Error: ${error.message}`);
        //         progressBar.style.width = '0%';
        //     }
        // }
        async function submitUpload() {
            const form = document.getElementById('uploadForm');
            const formData = new FormData(form);
            const progressBar = document.querySelector('#uploadProgress .progress-bar');
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadModal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
            const submitBtn = form.querySelector('button[type="button"]');

            // Deklarasikan progressInterval di scope fungsi
            let progressInterval = null;

            // Validasi client-side sebelum upload
            const fileInput = document.getElementById('fileExcel');
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Silakan pilih file terlebih dahulu');
                return;
            }

            const file = fileInput.files[0];
            const validExtensions = ['xlsx', 'xls', 'csv'];
            const fileExt = file.name.split('.').pop().toLowerCase();

            if (!validExtensions.includes(fileExt)) {
                alert('Format file harus xlsx, xls, atau csv');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file maksimal 10MB');
                return;
            }

            // Mulai upload
            uploadProgress.style.display = 'block';
            submitBtn.disabled = true;
            progressBar.style.width = '0%';

            try {
                // Setup progress interval
                progressInterval = setInterval(() => {
                    const currentWidth = parseInt(progressBar.style.width) || 0;
                    if (currentWidth < 90) {
                        progressBar.style.width = `${currentWidth + 5}%`;
                    }
                }, 200);

                const response = await fetch("{{ route('toefl.upload') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                // Hentikan interval dan set ke 100% saat upload selesai
                if (progressInterval) clearInterval(progressInterval);
                progressBar.style.width = '100%';

                // Handle response
                if (response.status === 419) {
                    throw new Error('Session expired, silakan refresh halaman');
                }

                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    const text = await response.text();
                    throw new Error(text.includes('<html') ?
                        'Session mungkin habis, silakan refresh halaman' :
                        'Respons server tidak valid');
                }

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Upload gagal');
                }

                alert(result.message || 'Upload berhasil!');
                uploadModal.hide();
                setTimeout(() => location.reload(), 1000); // Beri jeda sebelum reload

            } catch (error) {
                console.error('Upload error:', error);
                alert(`Error: ${error.message}`);
                progressBar.style.width = '0%';
            } finally {
                submitBtn.disabled = false;
                if (progressInterval) clearInterval(progressInterval);
            }
        }
    </script>
@endpush
