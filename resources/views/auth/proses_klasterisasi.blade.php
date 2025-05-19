@extends('layouts.auth')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header h4">{{ __('Klasterisasi Data') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('proses-klasterisasi') }}" enctype="multipart/form-data"
                            id="upload-form">
                            @csrf

                            <div class="row d-flex justify-content-center mt-5">
                                <div class="w-50">
                                    <div class="mb-3 ">
                                        <label for="nama" class="form-label">Nama Anda :</label>
                                        <input type="text" name="nama" id="nama" class="form-control"
                                            aria-label="nama" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Pos-el/Email :</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            aria-label="email" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nohp" class="form-label">No.Kontak :</label>
                                        <input type="text" name="nohp" id="nohp" class="form-control"
                                            aria-label="nohp" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="mb-3">
                                        <input type="hidden" name="id_pesanan" id="id_pesanan" class="form-control"
                                            aria-label="id_pesanan" aria-describedby="basic-addon1"
                                            value="{{ $pesananId }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success" id="upload-button" enabled>
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
