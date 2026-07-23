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
                            <th class="align-middle text-center">Role</th>
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
                                <td class="text-center text-nowrap">
                                    @if($user->role)
                                        @if($user->role->role_name === 'admin')
                                            <span style="background:#fde8e8; color:#c81e1e; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500; display:inline-block;">
                                                {{ ucfirst($user->role->role_name) }}
                                            </span>
                                        @else
                                            <span style="background:#e8f0fe; color:#1a56db; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500; display:inline-block;">
                                                {{ ucfirst($user->role->role_name) }}
                                            </span>
                                        @endif
                                    @else
                                        <span style="background:#f1f3f5; color:#495057; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500; display:inline-block;">
                                            Unknown
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- @foreach ($users as $user)
        <!-- Modal -->
        <div class="modal fade" id="roleModal{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ganti Role</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="my-2 text-center text-secondary">Mengganti Role dapat merubah hak akses dari user, klik
                            Ganti Role untuk melanjutkan perintah ini</p>
                        <form action="{{ route('users.update-role') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div>
                                <label for="role_id">Tentukan Role Akses</label>
                                <select name="role_id" id="role_id" class="form-control">
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn bg-navy text-white mt-2 w-100">
                                    Ganti Role
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}
@endsection
