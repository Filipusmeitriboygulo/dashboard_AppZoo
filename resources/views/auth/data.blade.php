@extends('layouts.auth')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Upload Data') }}</div>

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
                                    <input type="file" name="files[]" id="fileInput" class="file-input" multiple>
                                </div>
                            </div>
                            
                            <div id="file-list" class="mt-3"></div>
                            
                            <div class="progress mt-3" style="display: none;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                            
                            <div class="alert alert-success mt-3" id="upload-success" style="display: none;">
                                File berhasil diupload!
                            </div>
                            
                            <div class="alert alert-danger mt-3" id="upload-error" style="display: none;">
                                Terjadi kesalahan saat upload file.
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-success" id="upload-button" disabled>
                                Upload
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
    }
    
    .file-item .file-size {
        color: #6c757d;
        margin-right: 10px;
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
        const uploadSuccess = document.getElementById('upload-success');
        const uploadError = document.getElementById('upload-error');
        
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
            handleFiles({ target: { files } });
        }
        
        function handleFiles(e) {
            const files = e.target.files;
            
            if (files.length > 0) {
                uploadButton.disabled = false;
                updateFileList(files);
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
        
        function handleSubmit(e) {
            e.preventDefault();
            
            // Reset alerts
            uploadSuccess.style.display = 'none';
            uploadError.style.display = 'none';
            
            const formData = new FormData(uploadForm);
            
            // Show progress bar
            progress.style.display = 'block';
            progressBar.style.width = '0%';
            progressBar.textContent = '0%';
            
            fetch(uploadForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    uploadSuccess.style.display = 'block';
                    uploadForm.reset();
                    fileList.innerHTML = '';
                    uploadButton.disabled = true;
                } else {
                    uploadError.style.display = 'block';
                    uploadError.textContent = data.message || 'Terjadi kesalahan saat upload file.';
                }
                
                // Simulate progress
                simulateProgress();
            })
            .catch(error => {
                console.error('Error:', error);
                uploadError.style.display = 'block';
                progress.style.display = 'none';
            });
        }
        
        function simulateProgress() {
            let width = 0;
            const interval = setInterval(() => {
                if (width >= 100) {
                    clearInterval(interval);
                } else {
                    width += 5;
                    progressBar.style.width = width + '%';
                    progressBar.textContent = width + '%';
                    
                    if (width >= 100) {
                        setTimeout(() => {
                            progress.style.display = 'none';
                        }, 500);
                    }
                }
            }, 50);
        }
    });
</script>
@endsection