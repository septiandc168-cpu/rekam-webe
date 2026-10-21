@extends('layouts.adminlte')

@section('content_title', 'Daftar Pengguna')

@section('content')
    <div class="card shadow-sm elevation-1 text-sm">
        <div class="card-header bg-white">
            <h6 class="card-title fw-bold text-dark mt-1 mb-0" style="font-size: 0.95rem;">Daftar Pengguna</h6>
            <div class="card-tools">
                <x-user.form-user />
            </div>
        </div>
        <div class="card-body">
            <x-alert :errors="$errors" />
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle w-100" id="table2">
                    <thead class="bg-navy text-white text-nowrap border-bottom">
                        <tr>
                            <th class="align-middle text-center" style="width: 50px;">No</th>
                            <th class="align-middle text-center" style="width: 150px;">Aksi</th>
                            <th class="align-middle">Nama</th>
                            <th class="align-middle">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr class="border-bottom">
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <x-user.form-user :id="$user->id" />
                                        
                                        <x-user.reset-password :id="$user->id" />
                                        
                                        <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-sm btn-outline-danger shadow-sm mx-1 rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Hapus User" data-confirm-delete="true">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="fw-bold text-dark">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
