@extends('layouts.auth')

@section('title', 'Detail Jurusan')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user me-2"></i> Detail Prodi</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">ID Prodi:</div>
                            <div class="col-md-9">{{ $prodi->id }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Nama Prodi:</div>
                            <div class="col-md-9">{{ $prodi->name }}</div>
                        </div>
                         <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Akronim Prodi:</div>
                            <div class="col-md-9">{{ $prodi->code }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Jurusan:</div>
                            <div class="col-md-9">{{ $prodi->department->name}}</div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.prodi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
