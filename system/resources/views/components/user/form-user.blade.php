<div>
    <button type="button" class="btn {{ $id ? 'btn-sm btn-outline-warning shadow-sm rounded-circle mx-1' : 'btn-sm bg-navy text-white shadow-sm' }}"
        title="{{ $id ? 'Edit User' : 'Tambah User' }}"
        style="{{ $id ? 'width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;' : '' }}"
        data-toggle="modal" data-target="#formUser{{ $id ?? '' }}">
        @if ($id)
            <i class="fas fa-edit"></i>
        @else
            <i class="fas fa-plus mr-1"></i> Tambah Pengguna
        @endif
    </button>
    <div class="modal fade" id="formUser{{ $id ?? '' }}">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $id ?? '' }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $id ? 'Form Edit Pengguna' : 'Form Pengguna Baru' }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group my-1">
                            <label for="">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan Nama"
                                value="{{ $id ? $name : old('name') }}">
                        </div>
                        <div class="form-group my-1">
                            <label for="">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email"
                                value="{{ $id ? $email : old('email') }}">
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn bg-navy text-white">Simpan</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
        </form>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
