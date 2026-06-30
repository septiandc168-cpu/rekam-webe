@extends('layouts.adminlte')

@section('content_title', 'Daftar Laporan Kegiatan')

@section('content')
    <div class="card">
        {{-- <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Daftar Laporan Kegiatan</h3>
            <div>
            @can('create', \App\Models\LaporanKegiatan::class)
                <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus mx-1"></i>Pilih Rencana Kegiatan
                </a>
            @endcan
        </div>
        </div> --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                {{ session('error') }}
            </div>
        @endif


        <div class="card-header">
            <h3 class="card-title">
                Data Laporan Kegiatan
            </h3>
        </div>
        <div class="card-body">
            @if ($laporans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="table2">
                        <thead class="bg-navy">
                            <tr>
                                <th class="align-middle" style=" padding-left: 18px; height: 35px; width: 35px">No</th>
                                <th class="align-middle" style=" padding-left: 18px; height: 35px; width: 110px">Aksi</th>
                                <th class="align-middle" style=" padding-left: 18px; height: 35px;">Nama Kegiatan</th>
                                <th class="align-middle" style=" padding-left: 18px; height: 35px;">Penanggung Jawab</th>
                                <th class="align-middle" style=" padding-left: 18px; height: 35px;">Lokasi</th>
                                <th class="align-middle" style=" padding-left: 18px; height: 35px;">Tanggal Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporans as $index => $laporan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('laporan_kegiatan.show', $laporan) }}"
                                                class="btn btn-primary btn-sm"
                                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                title="Lihat Detail">
                                                <i class="fas fa-info"></i>
                                            </a>

                                            @can('print', $laporan)
                                                <a href="{{ route('laporan_kegiatan.print', $laporan) }}"
                                                    class="btn btn-secondary btn-sm"
                                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                    target="_blank" title="Cetak Laporan">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            @endcan

                                            @can('update', $laporan)
                                                <a href="{{ route('laporan_kegiatan.edit', $laporan) }}"
                                                    class="btn btn-warning btn-sm"
                                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                    title="Edit Laporan">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $laporan)
                                                <a class="btn btn-danger btn-sm"
                                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                    href="{{ route('laporan_kegiatan.destroy', $laporan) }}"
                                                    title="Hapus Laporan" data-confirm-delete="true">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                    <td>
                                        {{ $laporan->rencanaKegiatan->nama_kegiatan }}
                                        <br>
                                        <small class="text-muted">{{ $laporan->rencanaKegiatan->getJenisKegiatanLabel() }}</small>
                                    </td>
                                    <td>{{ $laporan->rencanaKegiatan->penanggung_jawab ?: '-' }}</td>
                                    <td>{{ $laporan->rencanaKegiatan->desa ?: '-' }}</td>
                                    <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination jika diperlukan -->
                @if (method_exists($laporans, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $laporans->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada laporan kegiatan</h5>
                    <p class="text-muted">
                        @can('create', \App\Models\LaporanKegiatan::class)
                            Buat laporan kegiatan untuk rencana kegiatan yang telah selesai.
                        @else
                            Hubungi admin untuk membuat laporan kegiatan.
                        @endcan
                    </p>
                    @can('create', \App\Models\LaporanKegiatan::class)
                        <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>Buat Laporan Baru
                        </a>
                    @endcan
                </div>
            @endif
        </div>

    </div>

    <style>
        /* .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        } */

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }
    </style>
@endsection
