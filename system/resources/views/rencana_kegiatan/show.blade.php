@extends('layouts.adminlte')

@section('content_title', 'Detail Rencana Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">
                Rencana Kegiatan
            </h5>

            <div class="text-end">
                <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                    style="height: 35px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
            {{-- Tombol Laporan Kegiatan --}}
            {{-- @if ($rencana_kegiatan->status === \App\Models\RencanaKegiatan::STATUS_SELESAI)
                    @if ($rencana_kegiatan->hasLaporan() && $rencana_kegiatan->laporanKegiatan)
                        <a href="{{ route('laporan_kegiatan.show', $rencana_kegiatan->laporanKegiatan) }}" class="btn btn-info">
                            <i class="fas fa-file-alt mx-1"></i>Lihat Laporan
                        </a>
                    @else
                        @can('create', \App\Models\LaporanKegiatan::class)
                            <a href="{{ route('laporan_kegiatan.create', ['rencana_kegiatan_id' => $rencana_kegiatan->uuid]) }}" class="btn btn-success">
                                <i class="fas fa-plus mx-1"></i>Buat Laporan
                            </a>
                        @else
                            <button class="btn btn-success" disabled title="Hubungi admin untuk membuat laporan">
                                <i class="fas fa-plus mx-1"></i>Buat Laporan
                            </button>
                        @endcan
                    @endif
                @else
                    <button class="btn btn-secondary" disabled title="Laporan hanya bisa dibuat untuk rencana kegiatan yang selesai">
                        <i class="fas fa-file-alt mx-1"></i>Laporan Tidak Tersedia
                    </button>
                @endif --}}
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="mb-3" id="map-show"
                    style="width:100%; height:70vh; border:1px solid #ddd; border-radius:4px;"></div>
            </div>
        </div>

        <!-- Informasi Dasar Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi Dasar Kegiatan
                </h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 200px; border-top: none;">Nama Kegiatan</th>
                        <td style="border-top: none;">{{ $rencana_kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kegiatan</th>
                        <td>{{ $rencana_kegiatan->getJenisKegiatanLabel() }}</td>
                    </tr>
                    <tr>
                        <th>Desa</th>
                        <td>{{ $rencana_kegiatan->desa }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>
                            @if ($rencana_kegiatan->tanggal_mulai)
                                {{ \Carbon\Carbon::parse($rencana_kegiatan->tanggal_mulai)->format('d F Y') }}
                                @if ($rencana_kegiatan->tanggal_selesai)
                                    - {{ \Carbon\Carbon::parse($rencana_kegiatan->tanggal_selesai)->format('d F Y') }}
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Waktu</th>
                        <td>
                            @if ($rencana_kegiatan->waktu_mulai)
                                {{ \Carbon\Carbon::parse($rencana_kegiatan->waktu_mulai)->format('H:i') }}
                                @if ($rencana_kegiatan->waktu_selesai)
                                    - {{ \Carbon\Carbon::parse($rencana_kegiatan->waktu_selesai)->format('H:i') }}
                                @endif
                            @else
                                Belum ditentukan
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Penanggung Jawab</th>
                        <td>{{ $rencana_kegiatan->penanggung_jawab }}</td>
                    </tr>
                    <tr>
                        <th>Kelompok</th>
                        <td>{{ $rencana_kegiatan->kelompok }}</td>
                    </tr>
                    <tr>
                        <th>Estimasi Peserta</th>
                        <td>{{ $rencana_kegiatan->estimasi_peserta }} orang</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span
                                class="badge bg-{{ $rencana_kegiatan->status == \App\Models\RencanaKegiatan::STATUS_DIAJUKAN
                                    ? 'primary'
                                    : ($rencana_kegiatan->status == \App\Models\RencanaKegiatan::STATUS_REVISI
                                        ? 'warning text-dark'
                                        : ($rencana_kegiatan->status == \App\Models\RencanaKegiatan::STATUS_DITOLAK
                                            ? 'danger'
                                            : ($rencana_kegiatan->status == \App\Models\RencanaKegiatan::STATUS_DISETUJUI
                                                ? 'success'
                                                : 'secondary'))) }}">
                                {{ ucfirst($rencana_kegiatan->status) }}
                            </span>
                        </td>
                    </tr>
                    @if (!empty($rencana_kegiatan->keterangan_status))
                        <tr>
                            <th>Keterangan Status</th>
                            <td>{{ $rencana_kegiatan->keterangan_status }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Detail Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt mr-1"></i>
                    Detail Kegiatan
                </h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 200px; border-top: none;">Deskripsi</th>
                        <td style="border-top: none;">{!! $rencana_kegiatan->deskripsi !!}</td>
                    </tr>
                    <tr>
                        <th>Tujujuan</th>
                        <td>{!! $rencana_kegiatan->tujuan !!}</td>
                    </tr>
                    <tr>
                        <th>Rincian Kebutuhan</th>
                        <td>{!! $rencana_kegiatan->rincian_kebutuhan !!}</td>
                    </tr>
                </table>
            </div>
        </div>
            <!-- Media Publikasi -->
        @php
            $fotoData = $rencana_kegiatan->foto;
            if (is_string($fotoData)) {
                $fotoData = json_decode($fotoData, true);
            }
            $fotoData = is_array($fotoData) ? $fotoData : [];
        @endphp

        @if (!empty($fotoData))
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-images mr-1"></i>
                        Media Publikasi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($fotoData as $index => $foto)
                            @if ($foto)
                                @php
                                    // Handle format baru (array dengan path dan original_name)
                                    if (is_array($foto)) {
                                        $fotoPath = $foto['path'];
                                        $fotoName = $foto['original_name'];
                                    } else {
                                        // Handle format lama (string path)
                                        $fotoPath = $foto;
                                        $fotoName = 'Foto ' . ($index + 1);
                                    }
                                @endphp
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('public/storage/app/' . $fotoPath) }}" class="card-img-top"
                                            style="height: 200px; object-fit: cover; width: 100%;"
                                            alt="{{ $fotoName }}" data-toggle="modal"
                                            data-target="#imageModal{{ $index }}">
                                        <div class="card-body p-2 text-center">
                                            <small class="text-muted">{{ $fotoName }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Dokumen Pendukung -->
        @if (!empty($rencana_kegiatan->dokumen))
            @php
                // Pastikan selalu array
                $dokumens = $rencana_kegiatan->dokumen;

                if (is_string($dokumens)) {
                    $dokumens = json_decode($dokumens, true);
                }

                $dokumens = is_array($dokumens) ? $dokumens : [];
            @endphp

            @if (count($dokumens))
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-alt mr-1"></i>
                            Dokumen Pendukung Kegiatan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($dokumens as $index => $file)
                                @if ($file)
                                    @php
                                        // Handle format baru (array dengan path dan original_name)
                                        if (is_array($file)) {
                                            $filePath = $file['path'];
                                            $fileName = $file['original_name'];
                                        } else {
                                            // Handle format lama (string path)
                                            $filePath = $file;
                                            $fileName = basename($file);
                                        }
                                    @endphp
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                                    <a href="{{ asset('public/storage/app/' . $filePath) }}" target="_blank"
                                                        class="text-decoration-none text-truncate" download="{{ $fileName }}">
                                                        {{ $fileName }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Anggaran Kegiatan -->
        @if (!empty($rencana_kegiatan->anggaran_kegiatan))
            @php
                $anggaranFile = $rencana_kegiatan->anggaran_kegiatan;
                $anggaranPath = is_array($anggaranFile) ? $anggaranFile['path'] : $anggaranFile;
                $anggaranName = is_array($anggaranFile) ? $anggaranFile['original_name'] : basename($anggaranPath);
            @endphp
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-excel mr-1"></i>
                        Anggaran Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-excel text-success me-2"></i>
                                        <a href="{{ asset('public/storage/app/' . $anggaranPath) }}" target="_blank"
                                            class="text-decoration-none text-truncate" download="{{ $anggaranName }}">
                                            {{ $anggaranName }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- MODAL FOTO --}}
    @if (!empty($fotoData))
        {{-- Debug: tampilkan info modal --}}
        {{-- <small class="text-muted d-block mb-2">Membuat {{ count($fotoData) }} modal</small> --}}
        @foreach ($fotoData as $index => $foto)
            @if ($foto)
                @php
                    // Handle format baru (array dengan path dan original_name)
                    if (is_array($foto)) {
                        $fotoPath = $foto['path'];
                        $fotoName = $foto['original_name'];
                    } else {
                        // Handle format lama (string path)
                        $fotoPath = $foto;
                        $fotoName = 'Foto ' . ($index + 1);
                    }
                @endphp
                <div class="modal fade" id="imageModal{{ $index }}">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $fotoName }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center p-2">
                                <img src="{{ asset('public/storage/app/' . $fotoPath) }}" class="img-fluid"
                                    alt="{{ $fotoName }}" style="max-height: 70vh; object-fit: contain;">
                            </div>
                            <div class="modal-footer justify-content-between">
                                <a href="{{ asset('public/storage/app/' . $fotoPath) }}" download="{{ $fotoName }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lat = parseFloat('{{ $rencana_kegiatan->lat ?? -6.2 }}');
                const lng = parseFloat('{{ $rencana_kegiatan->lng ?? 106.816666 }}');
                const map = L.map('map-show').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                if (!isNaN(lat) && !isNaN(lng)) {
                    L.marker([lat, lng]).addTo(map);
                }

                setTimeout(function() {
                    map.invalidateSize();
                }, 200);
            });
        </script>
    @endpush

    {{-- SCRIPT MODAL --}}
    @push('scripts')
        <script>
            // Bootstrap 4 - modal akan otomatis berfungsi dengan data-toggle dan data-target
            console.log('Modal Bootstrap 4 siap');
        </script>
    @endpush

    {{-- RESPONSIVE MODAL CSS --}}
    @push('styles')
        <style>
            /* Responsive modal untuk mobile */
            @media (max-width: 576px) {
                .modal-dialog {
                    margin: 10px;
                    max-width: calc(100vw - 20px);
                }

                .modal-body {
                    padding: 10px !important;
                }

                .modal-body img {
                    max-height: 60vh !important;
                }

                .modal-footer {
                    padding: 10px !important;
                    flex-direction: column;
                    gap: 5px;
                }

                .modal-footer .btn {
                    width: 100%;
                    margin: 0;
                }
            }

            @media (max-width: 768px) {
                .modal-dialog.modal-lg {
                    max-width: 95%;
                }
            }
        </style>
    @endpush

    <style>
        .card-img-top {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .card-img-top:hover {
            transform: scale(1.05);
        }

        .modal-body img {
            max-height: 70vh;
            object-fit: contain;
        }

        /* Responsive foto cards */
        @media (max-width: 768px) {
            .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Responsive dokumen cards */
        @media (max-width: 768px) {
            .col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Dokumen card styling */
        .card-body .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 576px) {
            .card-body .text-truncate {
                max-width: 150px;
            }
        }
    </style>

@endsection
