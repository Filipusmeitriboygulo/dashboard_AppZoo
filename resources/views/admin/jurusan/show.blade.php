@extends('layouts.auth')

@section('title', 'Detail Jurusan')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user me-2"></i> Detail Jurusan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">ID Jurusan:</div>
                            <div class="col-md-9">{{ $department->id }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Nama Jurusan:</div>
                            <div class="col-md-9">{{ $department->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Email:</div>
                            <div class="col-md-9">{{ $department->code }}</div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
