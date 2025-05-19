@extends('layouts.auth')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header h4">{{ __('Klasterisasi Data') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('klasterisasi') }}" enctype="multipart/form-data"
                            id="upload-form">
                            @csrf
                            <div class="row d-flex justify-content-center mt-5">
                                <div class="w-50">
                                    @foreach($file_uploads as $file)
                                        <div class="mb-3 ">
                                            <label for="nama" class="form-select">Nama Data :</label>
                                            <input type="text" name="nama" id="nama" class="form-control"
                                                aria-label="nama" aria-describedby="basic-addon1" value="{{ $file->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama Admin Pengelola :</label>
                                            <input type="nama" name="nama" id="nama" class="form-control"
                                                aria-label="nama" aria-describedby="basic-addon1"
                                                value="{{ auth()->user()->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="nohp" class="form-label">Tanggal:</label>
                                            <input type="text" name="nohp" id="nohp" class="form-control"
                                                aria-label="nohp" aria-describedby="basic-addon1"
                                                value="{{ $file->created_at }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success" id="upload-button" enabled>
                                    Mulai Analisis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
