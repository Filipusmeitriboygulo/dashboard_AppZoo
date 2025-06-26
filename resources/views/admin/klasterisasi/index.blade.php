@extends('layouts.auth')

@section('title', 'Klasterisasi Data')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h2>Klasterisasi Data</h2>
                    </div>
                    
                    <div class="card-body">
                        <div class="mb-4">
                            <p class="text-muted">
                                Silahkan Pilih File yang ingin diannalsisi klasterisasi
                            </p>
                        </div>

                        <form method="POST" action="{{ route('klasterisasi.analyze') }}" id="clusterForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cakupan">Pilih Cakupan Data:</label>
                                        <select class="form-control" id="cakupan" name="cakupan" required>
                                            <option value="">-- Pilih Cakupan --</option>
                                            <option value="kampus">Kampus</option>
                                            <option value="jurusan">Jurusan</option>
                                            <option value="prodi">Program Studi</option>
                                            <option value="kelas">Kelas</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit_name">Nama Unit: va</label>
                                    <input type="text" class="form-control" id="unit_name" name="unit_name"
                                        placeholder="Masukkan nama unit" disabled>
                                </div>
                            </div> --}}

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_name">Nama Unit:</label>
                                        <input type="text" class="form-control" id="unit_name" name="unit_name"
                                            placeholder="Silahkan Isi Nama Unit" 
                                            >
                                    </div>
                                </div>
                            </div>
                    </div>



                    <div class="form-group mt-3">
                        <label for="file_upload_id">Pilih File untuk Analisis:</label>
                        <select class="form-control" id="file_upload_id" name="file_upload_id" required disabled>
                            <option value="">-- Pilih File --</option>
                            @foreach ($file_uploads as $file)
                                <option value="{{ $file->id }}" data-cakupan="{{ $file->cakupan }}"
                                    data-unit="{{ $file->unit_nama }}">
                                    {{ $file->file_name }} (Upload: {{ $file->created_at->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="form-group mt-4 p-3 ">
                        <button type="submit" class="btn btn-primary" id="analyzeBtn" disabled>
                            <i class="fas fa-chart-pie mr-2"></i> Analisis Klasterisasi
                        </button>
                    </div>
                    </form>

                    <!-- Tabel Preview Data -->
                    {{-- <div class="mt-4" id="dataPreview" style="display:none;">
                        <h5>Preview Data</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="previewTable">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th>Jurusan</th>
                                        <th>Prodi</th>
                                        <th>Kelas</th>
                                        <th>Total Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div> --}}

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

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Aktifkan input unit name ketika cakupan dipilih
                $('#cakupan').change(function() {
                    const cakupan = $(this).val();
                    $('#unit_name').prop('disabled', !cakupan);

                    // Filter file upload berdasarkan cakupan
                    filterFilesByScope(cakupan);
                });

                // Aktifkan tombol analisis ketika file dipilih
                $('#file_upload_id').change(function() {
                    const fileSelected = $(this).val();
                    $('#analyzeBtn').prop('disabled', !fileSelected);

                    // if (fileSelected) {
                    //     loadPreviewData(fileSelected);
                    // } else {
                    //     $('#dataPreview').hide();
                    // }
                });

                // Fungsi filter file berdasarkan cakupan
                function filterFilesByScope(cakupan) {
                    $('#file_upload_id').val('').prop('disabled', !cakupan);
                    $('#file_upload_id option').show();

                    if (cakupan) {
                        $('#file_upload_id option[data-cakupan]').each(function() {
                            $(this).toggle($(this).data('cakupan') === cakupan);
                        });
                    }
                }

                // Fungsi untuk load preview data via AJAX
                // function loadPreviewData(fileId) {
                //     $.get(`/api/toefl-scores/preview/${fileId}`, function(data) {
                //         const tableBody = $('#previewTable tbody');
                //         tableBody.empty();

                //         data.forEach(item => {
                //             tableBody.append(`
        //         <tr>
        //             <td>${item.nama}</td>
        //             <td>${item.nim || '-'}</td>
        //             <td>${item.jurusan}</td>
        //             <td>${item.prodi}</td>
        //             <td>${item.kelas || '-'}</td>
        //             <td>${item.total_score}</td>
        //         </tr>
        //     `);
                //         });

                //         $('#dataPreview').show();
                //     }).fail(function() {
                //         alert('Gagal memuat preview data');
                //     });
                // }
            });
        </script>
    @endpush


@endsection
