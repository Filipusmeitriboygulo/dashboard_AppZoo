@extends('layouts.auth')

@section('title', 'Manajemen Jurusan')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-users me-2"></i> Manajemen Jurusan</h4>
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                                <i class="fas fa-user-plus me-1"></i> Tambah Baru
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Jurusan</th>
                                        <th>Akronim Jurusan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $department)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $department->name }}</td>
                                            <td>{{ $department->code }}</td>

                                            <td class="text-center">
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <!-- Tombol View (Link ke detail page) -->
                                                    <a href="{{ route('admin.jurusan.show', ['id' => $department->id]) }}"
                                                        class="btn btn-sm btn-info mx-1" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>

                                                    <!-- Tombol Edit (Link ke edit page) -->
                                                    <button class="btn btn-sm btn-warning mx-1 btn-edit"
                                                        data-bs-toggle="modal" data-bs-target="#editJurusanModal"
                                                        data-id="{{ $department->id }}" data-name="{{ $department->name }}"
                                                        data-code="{{ $department->code }}"
                                                        data-url="{{ route('admin.jurusan.update', $department->id) }}">
                                                        <i class="fas fa-edit"></i>Edit
                                                    </button>



                                                    <!-- Tombol Delete (Form) -->
                                                    <form
                                                        action="{{ route('admin.jurusan.destroy', ['id' => $department->id]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mx-1"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                                            title="Hapus">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Di main view (index.blade.php) -->
    @include('admin.jurusan.modals.create')

    @include('admin.jurusan.modals.update')

@endsection
