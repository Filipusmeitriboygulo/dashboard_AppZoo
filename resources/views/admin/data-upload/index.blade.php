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
                        <!-- Upload Options -->
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="card text-center h-100 upload-option" data-scope="campus">
                                    <div class="card-body">
                                        <i class="fas fa-university fa-3x text-primary mb-3"></i>
                                        <h5>Seluruh Kampus</h5>
                                        <p class="text-muted">Upload data untuk semua mahasiswa kampus</p>
                                        <button class="btn btn-primary" onclick="showUploadModal('campus')">
                                            Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card text-center h-100 upload-option" data-scope="department">
                                    <div class="card-body">
                                        <i class="fas fa-building fa-3x text-success mb-3"></i>
                                        <h5>Per Jurusan</h5>
                                        <p class="text-muted">Upload data berdasarkan jurusan</p>
                                        <div class="dropdown">
                                            <button class="btn btn-success dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Pilih Jurusan
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($departments as $dept)
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="showUploadModal('department', {{ $dept->id }}, '{{ $dept->name }}')">
                                                            {{ $dept->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card text-center h-100 upload-option" data-scope="study_program">
                                    <div class="card-body">
                                        <i class="fas fa-graduation-cap fa-3x text-warning mb-3"></i>
                                        <h5>Per Program Studi</h5>
                                        <p class="text-muted">Upload data berdasarkan program studi</p>
                                        <div class="dropdown">
                                            <button class="btn btn-warning dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Pilih Program Studi
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($departments as $dept)
                                                    <li>
                                                        <h6 class="dropdown-header">{{ $dept->name }}</h6>
                                                    </li>
                                                    @foreach ($dept->studyPrograms as $program)
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="showUploadModal('study_program', {{ $program->id }}, '{{ $program->name }}')">
                                                                {{ $program->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                    @if (!$loop->last)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card text-center h-100 upload-option" data-scope="class">
                                    <div class="card-body">
                                        <i class="fas fa-users fa-3x text-info mb-3"></i>
                                        <h5>Per Kelas</h5>
                                        <p class="text-muted">Upload data berdasarkan kelas</p>
                                        <div class="dropdown">
                                            <button class="btn btn-info dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Pilih Kelas
                                            </button>
                                            <ul class="dropdown-menu" style="max-height: 300px; overflow-y: auto;">
                                                @foreach ($departments as $dept)
                                                    <li>
                                                        <h6 class="dropdown-header">{{ $dept->name }}</h6>
                                                    </li>
                                                    @foreach ($dept->studyPrograms as $program)
                                                        <li><small
                                                                class="dropdown-header text-muted">{{ $program->name }}</small>
                                                        </li>
                                                        @foreach ($program->classes as $class)
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="showUploadModal('class', {{ $class->id }}, '{{ $class->fullName }}')">
                                                                    {{ $class->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    @endforeach
                                                    @if (!$loop->last)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Uploads -->
                        <div class="row">
                            <div class="col-12">
                                <h4>Upload Terbaru</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Batch ID</th>
                                                <th>Uploader</th>
                                                <th>Jumlah Data</th>
                                                <th>Tanggal Upload</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentUploads as $upload)
                                                <tr>
                                                    <td><code>{{ $upload['batch_id'] }}</code></td>
                                                    <td>{{ $upload['uploader'] }}</td>
                                                    <td>{{ $upload['count'] }} records</td>
                                                    <td>{{ $upload['uploaded_at'] }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            onclick="viewBatchDetails('{{ $upload['batch_id'] }}')">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
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
                            <label for="data_file" class="form-label">File Data</label>
                            <input type="file" class="form-control" id="data_file" name="data_file"
                                accept=".csv,.xlsx,.xls" required>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="uploadBtn" onclick="uploadFile()">Upload &
                        Preview</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="previewContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="processBtn" onclick="processData()">Process
                        Data</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentBatchId = null;
        let currentFilePath = null;
        let currentFileType = null;

        function showUploadModal(scope, scopeId = null, scopeName = null) {
            document.getElementById('scope').value = scope;
            document.getElementById('scope_id').value = scopeId || '';

            let label = '';
            switch (scope) {
                case 'campus':
                    label = 'Seluruh Kampus';
                    break;
                case 'department':
                case 'study_program':
                case 'class':
                    label = scopeName;
                    break;
            }

            document.getElementById('scopeLabel').textContent = label;

            // Reset form
            document.getElementById('uploadForm').reset();
            document.getElementById('uploadProgress').style.display = 'none';

            new bootstrap.Modal(document.getElementById('uploadModal')).show();
        }

        async function uploadFile() {
            const form = document.getElementById('uploadForm');
            const formData = new FormData(form);
            const uploadBtn = document.getElementById('uploadBtn');
            const progressDiv = document.getElementById('uploadProgress');
            const progressBar = progressDiv.querySelector('.progress-bar');

            // Validate file
            const fileInput = document.getElementById('data_file');
            if (!fileInput.files[0]) {
                alert('Please select a file');
                return;
            }

            const file = fileInput.files[0];
            const allowedTypes = ['csv', 'xlsx', 'xls'];
            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (!allowedTypes.includes(fileExtension)) {
                alert('Please select a valid file (CSV, XLSX, or XLS)');
                return;
            }

            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            progressDiv.style.display = 'block';

            // try {
            //     const response = await fetch('{{ route('admin.data-upload.upload') }}', {
            //         method: 'POST',
            //         body: formData,
            //         headers: {
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
            //                 'content')
            //         }
            //     });

            //     const result = await response.json();

            //     if (result.success) {
            //         currentBatchId = result.batch_id;
            //         currentFilePath = result.file_path;
            //         currentFileType = result.file_type;

            //         // Hide upload modal and show preview
            //         bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
            //         showPreview(result.preview);
            //     } else {
            //         alert('Upload failed: ' + result.message);
            //     }
            // } catch (error) {
            //     alert('Upload failed: ' + error.message);
            // } finally {
            //     uploadBtn.disabled = false;
            //     uploadBtn.innerHTML = 'Upload & Preview';
            //     progressDiv.style.display = 'none';
            // }

            try {
                const response = await fetch('http://127.0.0.1:8000/admin/data-upload/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(
                        errorData.message ||
                        `Upload failed with status ${response.status}`
                    );
                }

                const result = await response.json();
                console.log('Upload success:', result);
                return result;

            } catch (error) {
                console.error('Upload error:', error);
                alert(`Upload failed: ${error.message}`);
                throw error;
            }
        }

        // async function uploadFile(fileInput) {
        //     if (!fileInput.files.length) return;

        //     const formData = new FormData();
        //     formData.append('file', fileInput.files[0]);
        //     formData.append('scope', document.getElementById('scope').value);
        //     formData.append('scope_id', document.getElementById('scope_id').value);

        //     try {
        //         const response = await fetch('http://127.0.0.1:8000/admin/data-upload/upload', {
        //             method: 'POST',
        //             body: formData,
        //             headers: {
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        //                 'Accept': 'application/json'
        //             }
        //         });

        //         if (!response.ok) {
        //             const errorData = await response.json().catch(() => ({}));
        //             throw new Error(
        //                 errorData.message ||
        //                 `Upload failed with status ${response.status}`
        //             );
        //         }

        //         const result = await response.json();
        //         console.log('Upload success:', result);
        //         return result;

        //     } catch (error) {
        //         console.error('Upload error:', error);
        //         alert(`Upload failed: ${error.message}`);
        //         throw error;
        //     }
        // }

        //     function showPreview(preview) {
        //         let content = `
    //     <div class="alert alert-info">
    //         <h6>File Information:</h6>
    //         <ul class="mb-0">
    //             <li>File type: ${preview.file_type.toUpperCase()}</li>
    //             <li>Total rows: ${preview.total_rows}</li>
    //             <li>Preview rows: ${preview.preview_count}</li>
    //             <li>Scope: ${preview.scope_info}</li>
    //             <li>Status: ${preview.has_errors ? '<span class="text-danger">Has Errors</span>' : '<span class="text-success">Valid</span>'}</li>
    //             ${preview.header_row_index !== undefined ? `<li>Header found at row: ${preview.header_row_index + 1}</li>` : ''}
    //         </ul>
    //     </div>
    // `;

        //         if (preview.has_errors) {
        //             content += `
    //         <div class="alert alert-danger">
    //             <h6>Errors Found:</h6>
    //             <ul class="mb-0">
    //                 ${preview.errors.map(error => `<li>${error}</li>`).join('')}
    //             </ul>
    //         </div>
    //     `;
        //         }

        //         // Display table based on file type
        //         if (preview.file_type === 'excel') {
        //             content += `
    //         <div class="alert alert-info">
    //             <small><strong>Excel Structure Detected:</strong> Data will be parsed automatically from the Excel format.</small>
    //         </div>
    //         <div class="table-responsive">
    //             <table class="table table-sm table-bordered">
    //                 <thead class="table-dark">
    //                     <tr>
    //                         ${preview.headers.map(header => `<th>${header}</th>`).join('')}
    //                     </tr>
    //                 </thead>
    //                 <tbody>
    //                     ${preview.sample_rows.map(row => `
        //                             <tr class="${row.errors.length > 0 ? 'table-danger' : 'table-success'}">
        //                                 <td>${row.processed.no || '-'}</td>
        //                                 <td>${row.processed.nama || '-'}</td>
        //                                 <td>${row.processed.kode_jurusan || ''}/${row.processed.kode_prodi || ''}/${row.processed.kelas || ''}</td>
        //                                 <td>${row.processed.listening || '-'}</td>
        //                                 <td>${row.processed.structure || '-'}</td>
        //                                 <td>${row.processed.reading || '-'}</td>
        //                                 <td>${row.processed.total || '-'}</td>
        //                                 <td>
        //                                     ${row.errors.length > 0 ? 
        //                                         `<small class="text-danger">${row.errors.join(', ')}</small>` : 
        //                                         '<small class="text-success">Valid</small>'
        //                                     }
        //                                 </td>
        //                             </tr>
        //                         `).join('')}
    //                 </tbody>
    //             </table>
    //         </div>
    //     `;
        //         } else {
        //             // CSV format
        //             content += `
    //         <div class="table-responsive">
    //             <table class="table table-sm table-bordered">
    //                 <thead class="table-dark">
    //                     <tr>
    //                         ${preview.headers.map(header => `<th>${header}</th>`).join('')}
    //                         <th>Status</th>
    //                     </tr>
    //                 </thead>
    //                 <tbody>
    //                     ${preview.sample_rows.map(row => `
        //                             <tr class="${row.errors.length > 0 ? 'table-danger' : 'table-success'}">
        //                                 ${preview.headers.map(header => `<td>${row.original[header] || ''}</td>`).join('')}
        //                                 <td>
        //                                     ${row.errors.length > 0 ? 
        //                                         `<small class="text-danger">${row.errors.join(', ')}</small>` : 
        //                                         '<small class="text-success">Valid</small>'
        //                                     }
        //                                 </td>
        //                             </tr>
        //                         `).join('')}
    //                 </tbody>
    //             </table>
    //         </div>
    //     `;
        //         }

        //         document.getElementById('previewContent').innerHTML = content;
        //         document.getElementById('processBtn').disabled = preview.has_errors;

        //         new bootstrap.Modal(document.getElementById('previewModal')).show();
        //     }

        function showPreview(preview) {
            const previewContent = document.getElementById('previewContent');
            const processBtn = document.getElementById('processBtn');

            if (!previewContent || !processBtn) {
                console.error('Required elements not found');
                return;
            }

            // Header info
            let content = `
        <div class="alert alert-info">
            <h6>File Information:</h6>
            <ul class="mb-0">
                <li>File type: ${preview.file_type.toUpperCase()}</li>
                <li>Total rows: ${preview.total_rows}</li>
                <li>Preview rows: ${preview.preview_count}</li>
                ${preview.scope_info ? `<li>Scope: ${preview.scope_info}</li>` : ''}
                <li>Status: ${preview.has_errors ? 
                    '<span class="text-danger">Has Errors</span>' : 
                    '<span class="text-success">Valid</span>'}
                </li>
            </ul>
        </div>
    `;

            // Error display
            if (preview.has_errors && preview.errors?.length) {
                content += `
            <div class="alert alert-danger">
                <h6>Errors Found:</h6>
                <ul class="mb-0">
                    ${preview.errors.slice(0, 10).map(error => `<li>${error}</li>`).join('')}
                    ${preview.errors.length > 10 ? `<li>...and ${preview.errors.length - 10} more errors</li>` : ''}
                </ul>
            </div>
        `;
            }

            // Table display - disederhanakan untuk berbagai jenis data
            content += `
        <div class="table-responsive mt-3">
            <table class="table table-sm table-bordered">
                <thead class="table-dark">
                    <tr>
                        ${preview.headers?.map(header => `<th>${header}</th>`).join('')}
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${preview.sample_rows?.map(row => `
                                <tr class="${row.errors?.length ? 'table-danger' : 'table-success'}">
                                    ${preview.headers?.map(header => `
                                <td>${row.original?.[header] || row.processed?.[header] || '-'}</td>
                            `).join('')}
                                    <td>
                                        ${row.errors?.length ? 
                                            `<small class="text-danger">${row.errors.join(', ')}</small>` : 
                                            '<small class="text-success">Valid</small>'
                                        }
                                    </td>
                                </tr>
                            `).join('')}
                </tbody>
            </table>
        </div>
    `;

            previewContent.innerHTML = content;
            processBtn.disabled = preview.has_errors;

            // Initialize modal
            const previewModal = new bootstrap.Modal('#previewModal');
            previewModal.show();
        }

        // async function processData() {
        //     if (!currentBatchId || !currentFilePath || !currentFileType) {
        //         alert('No data to process');
        //         return;
        //     }

        //     const processBtn = document.getElementById('processBtn');
        //     processBtn.disabled = true;
        //     processBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        //     try {
        //         const response = await fetch('{{ route('admin.data-upload.process') }}', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //             },
        //             body: JSON.stringify({
        //                 batch_id: currentBatchId,
        //                 file_path: currentFilePath,
        //                 file_type: currentFileType,
        //                 scope: document.getElementById('scope').value,
        //                 scope_id: document.getElementById('scope_id').value
        //             })
        //         });

        //         const result = await response.json();

        //         if (result.success) {
        //             alert(`Data processed successfully!\nTotal: ${result.result.total_rows}\nSuccess: ${result.result.success_count}\nErrors: ${result.result.error_count}`);

        //             // Show detailed errors if any
        //             if (result.result.error_count > 0 && result.result.errors.length > 0) {
        //                 const errorDetails = result.result.errors.slice(0, 10).join('\n');
        //                 const remainingErrors = result.result.errors.length > 10 ? `\n... and ${result.result.errors.length - 10} more errors` : '';
        //                 console.log('Processing Errors:\n' + errorDetails + remainingErrors);
        //             }

        //             // Hide modal and refresh page
        //             bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
        //             location.reload();
        //         } else {
        //             alert('Processing failed: ' + result.message);
        //         }
        //     } catch (error) {
        //         alert('Processing failed: ' + error.message);
        //     } finally {
        //         processBtn.disabled = false;
        //         processBtn.innerHTML = 'Process Data';
        //     }
        // }


        // Deklarasikan variabel global di awal
        let currentUploadData = {
            batchId: null,
            filePath: null,
            fileType: null
        };

        async function processData() {
            const processBtn = document.getElementById('processBtn');
            if (!processBtn) return;

            if (!currentUploadData.batchId || !currentUploadData.filePath || !currentUploadData.fileType) {
                alert('No data to process');
                return;
            }

            try {
                // Update UI
                processBtn.disabled = true;
                processBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                const response = await fetch('{{ route('admin.data-upload.process') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        batch_id: currentUploadData.batchId,
                        file_path: currentUploadData.filePath,
                        file_type: currentUploadData.fileType,
                        scope: document.getElementById('scope')?.value,
                        scope_id: document.getElementById('scope_id')?.value
                    })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Processing failed');
                }

                showProcessingResult(result);

            } catch (error) {
                console.error('Processing error:', error);
                alert(`Processing failed: ${error.message}`);
            } finally {
                if (processBtn) {
                    processBtn.disabled = false;
                    processBtn.innerHTML = 'Process Data';
                }
            }
        }

        function showProcessingResult(result) {
            let message = `Data processed successfully!\nTotal: ${result.result?.total_rows || 0}\n`;
            message += `Success: ${result.result?.success_count || 0}\n`;
            message += `Errors: ${result.result?.error_count || 0}`;

            alert(message);

            // Hide modal and refresh
            const modal = bootstrap.Modal.getInstance('#previewModal');
            if (modal) modal.hide();

            if (result.result?.error_count > 0) {
                console.log('Processing Errors:', result.result.errors);
            }

            setTimeout(() => location.reload(), 1000);
        }

        function viewBatchDetails(batchId) {
            // Implement batch details view
            window.open(`/admin/data-upload/batch/${batchId}`, '_blank');
        }

        // File input change handler to show file info
        // document.addEventListener('DOMContentLoaded', function() {
        //     const fileInput = document.getElementById('data_file');
        //     if (fileInput) {
        //         fileInput.addEventListener('change', function(e) {
        //             const file = e.target.files[0];
        //             if (file) {
        //                 const fileInfo = document.querySelector('.form-text');
        //                 const fileSize = (file.size / 1024 / 1024).toFixed(2);
        //                 const fileType = file.name.split('.').pop().toLowerCase();

        //                 let additionalInfo = `<br><strong>Selected:</strong> ${file.name} (${fileSize} MB, ${fileType.toUpperCase()})`;

        //                 if (!['csv', 'xlsx', 'xls'].includes(fileType)) {
        //                     additionalInfo += '<br><span class="text-danger">⚠️ Unsupported file format</span>';
        //                 } else if (fileSize > 10) {
        //                     additionalInfo += '<br><span class="text-danger">⚠️ File too large (max 10MB)</span>';
        //                 } else {
        //                     additionalInfo += '<br><span class="text-success">✓ File ready for upload</span>';
        //                 }

        //                 fileInfo.innerHTML = fileInfo.innerHTML.split('<br><strong>Selected:')[0] + additionalInfo;
        //             }
        //         });
        //     }
        // });

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('data_file');
            const fileInfoElement = document.querySelector('.form-text');

            if (!fileInput || !fileInfoElement) return;

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const fileExt = file.name.split('.').pop().toLowerCase();
                const allowedTypes = ['csv', 'xlsx', 'xls'];

                let status = '';
                if (!allowedTypes.includes(fileExt)) {
                    status = '<span class="text-danger">⚠️ Unsupported file format</span>';
                } else if (fileSizeMB > 10) {
                    status = '<span class="text-danger">⚠️ File too large (max 10MB)</span>';
                } else {
                    status = '<span class="text-success">✓ File ready for upload</span>';
                    // Set nilai untuk upload
                    currentUploadData.fileType = fileExt;
                }

                fileInfoElement.innerHTML = `
            <strong>Selected:</strong> ${file.name}<br>
            Size: ${fileSizeMB} MB | Type: ${fileExt.toUpperCase()}<br>
            ${status}
        `;
            });
        });
    </script>
@endpush
