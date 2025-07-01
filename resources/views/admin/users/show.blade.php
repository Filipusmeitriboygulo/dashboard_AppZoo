@extends('layouts.auth')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user me-2"></i> Detail Pengguna</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Nama:</div>
                            <div class="col-md-9">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Email:</div>
                            <div class="col-md-9">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Role:</div>
                            <div class="col-md-9">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Status:</div>
                            <div class="col-md-9">
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                        @if ($user->department)
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Jurusan:</div>
                                <div class="col-md-9">{{ $user->department->name }}</div>
                            </div>
                        @endif
                        @if ($user->studyProgram)
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Program Studi:</div>
                                <div class="col-md-9">{{ $user->studyProgram->name }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
