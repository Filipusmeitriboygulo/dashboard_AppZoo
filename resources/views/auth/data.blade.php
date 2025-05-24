@extends('layouts.auth')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header h4">{{ __('Upload Data') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('data.upload') }}" enctype="multipart/form-data" id="upload-form">
                            @csrf

                            <div class="form-group">
                                <div id="drop-area" class="drop-area">
                                    <div class="drop-message text-center">
                                        <i class="fa fa-cloud-upload fa-3x mb-3"></i>
                                        <h4>Drag & Drop files disini</h4>
                                        <p>atau</p>
                                        <label for="fileInput" class="btn btn-primary">
                                            Pilih File
                                        </label>
                                        <input type="file" name="file" id="fileInput" class="file-input" accept=".csv,.txt,.xlsx">
                                    </div>
                                </div>

                                <div id="file-list" class="mt-3"></div>

                                <div class="progress mt-3" style="display: none;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success" id="upload-button" enabled>
                                    <i class="fa fa-upload mr-2"></i> Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .drop-area {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .drop-area.highlight {
            border-color: #007bff;
            background-color: rgba(0, 123, 255, 0.1);
        }

        .file-input {
            display: none;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            margin-bottom: 8px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .file-item .file-name {
            flex-grow: 1;
            margin-right: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-item .file-size {
            color: #6c757d;
            margin-right: 10px;
            font-size: 0.9em;
        }

        .file-item .file-remove {
            color: #dc3545;
            cursor: pointer;
        }

        .progress {
            height: 20px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropArea = document.getElementById('drop-area');
            const fileInput = document.getElementById('fileInput');
            const fileList = document.getElementById('file-list');
            const uploadButton = document.getElementById('upload-button');
            const uploadForm = document.getElementById('upload-form');
            const progressBar = document.querySelector('.progress-bar');
            const progress = document.querySelector('.progress');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            // Handle dropped files
            dropArea.addEventListener('drop', handleDrop, false);

            // Handle file input change
            fileInput.addEventListener('change', handleFiles, false);

            // Handle click on drop area
            dropArea.addEventListener('click', function() {
                fileInput.click();
            });

            // Form submission
            uploadForm.addEventListener('submit', handleSubmit, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight() {
                dropArea.classList.add('highlight');
            }

            function unhighlight() {
                dropArea.classList.remove('highlight');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles({
                    target: {
                        files
                    }
                });
            }

            function handleFiles(e) {
                const files = e.target.files;

                if (files.length > 0) {
                    uploadButton.disabled = false;
                    updateFileList(files);
                } else {
                    uploadButton.disabled = true;
                }
            }

            function updateFileList(files) {
                fileList.innerHTML = '';

                Array.from(files).forEach(file => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';

                    const fileName = document.createElement('div');
                    fileName.className = 'file-name';
                    fileName.textContent = file.name;

                    const fileSize = document.createElement('div');
                    fileSize.className = 'file-size';
                    fileSize.textContent = formatFileSize(file.size);

                    const fileRemove = document.createElement('div');
                    fileRemove.className = 'file-remove';
                    fileRemove.innerHTML = '<i class="fa fa-times"></i>';
                    fileRemove.addEventListener('click', function(e) {
                        e.stopPropagation();
                        fileItem.remove();
                        fileInput.value = '';
                        
                        // Check if there are no more files
                        if (fileList.children.length === 0) {
                            uploadButton.disabled = true;
                        }
                    });

                    fileItem.appendChild(fileName);
                    fileItem.appendChild(fileSize);
                    fileItem.appendChild(fileRemove);
                    fileList.appendChild(fileItem);
                });
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';

                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            async function handleSubmit(e) {
                e.preventDefault();

                // Show progress bar
                progress.style.display = 'block';
                progressBar.style.width = '0%';
                progressBar.textContent = '0%';

                const formData = new FormData(uploadForm);
                const files = fileInput.files;

                if (files.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silakan pilih file terlebih dahulu!',
                    });
                    progress.style.display = 'none';
                    return;
                }

                try {
                    const response = await axios.post(uploadForm.action, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        onUploadProgress: function(progressEvent) {
                            const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                            progressBar.style.width = percentCompleted + '%';
                            progressBar.textContent = percentCompleted + '%';
                        }
                    });

                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.data.message || 'File berhasil diupload!',
                            timer: 3000
                        });
                        
                        // Reset form
                        fileInput.value = '';
                        fileList.innerHTML = '';
                        uploadButton.disabled = true;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.data.message || 'Terjadi kesalahan saat mengupload file',
                        });
                    }
                } catch (error) {
                    let errorMessage = 'Terjadi kesalahan saat mengupload file';
                    
                    if (error.response) {
                        if (error.response.data.message) {
                            errorMessage = error.response.data.message;
                        } else if (error.response.data.errors) {
                            errorMessage = Object.values(error.response.data.errors).join('<br>');
                        }
                    } else if (error.request) {
                        errorMessage = 'Tidak ada respon dari server';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: errorMessage,
                    });
                } finally {
                    setTimeout(() => {
                        progress.style.display = 'none';
                    }, 1000);
                }
            }
        });
    </script>
@endsection