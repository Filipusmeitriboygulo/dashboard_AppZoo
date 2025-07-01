@extends('layouts.auth')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-users me-2"></i> Manajemen Pengguna</h4>
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
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <!-- Tombol View (Link ke detail page) -->
                                                    <a href="{{ route('admin.users.show', ['id' => $user->id]) }}"
                                                        class="btn btn-sm btn-info mx-1" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>

                                                    <!-- Tombol Edit (Link ke edit page) -->
                                                    <button class="btn btn-sm btn-warning mx-1 btn-edit"
                                                        data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user='@json($user)' title="Edit">
                                                        <i class="fas fa-edit"></i>Edit
                                                    </button>

                                                    <!-- Tombol Delete (Form) -->
                                                    <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}"
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
    @include('admin.users.modals.create')

    @include('admin.users.modals.update')

@endsection
