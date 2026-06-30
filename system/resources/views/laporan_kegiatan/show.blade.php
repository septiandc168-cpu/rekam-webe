@extends('layouts.adminlte')

@section('content_title', 'Detail Laporan Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Detail Laporan Kegiatan</h5>
            <div class="d-flex gap-2">
                    <a href="{{ route('laporan_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                    style="height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
            </div>
        </div>

        <!-- Informasi Rencana Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi Rencana Kegiatan
                </h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr style="border-top: none;">
                        <th style="width: 200px; border-top: none;">Nama Kegiatan</th>
                        <td style="border-top: none;">{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kegiatan</th>
                        <td>{{ $laporanKegiatan->rencanaKegiatan->getJenisKegiatanLabel() }}</td>
                    </tr>
                    <tr>
                        <th>Penanggung Jawab</th>
                        <td>{{ $laporanKegiatan->rencanaKegiatan->penanggung_jawab }}</td>
                    </tr>
                    <tr>
                        <th>Kelompok / Komunitas Pelaksana</th>
                        <td>{{ $laporanKegiatan->rencanaKegiatan->kelompok }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{!! strip_tags($laporanKegiatan->rencanaKegiatan->deskripsi) !!}</td>
                    </tr>
                    <tr>
                        <th>Tujuan</th>
                        <td>{!! strip_tags($laporanKegiatan->rencanaKegiatan->tujuan) !!}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <td>{{ $laporanKegiatan->created_at->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Detail Pelaksanaan Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks mr-1"></i>
                    Detail Pelaksanaan Kegiatan
                </h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 200px; border-top: none;">Lokasi</th>
                        <td style="border-top: none;">{{ $laporanKegiatan->rencanaKegiatan->desa }}</td>
                    </tr>
                    <tr>
                        <th>Waktu Pelaksanaan</th>
                        <td>
                            @if ($laporanKegiatan->rencanaKegiatan->waktu_mulai && $laporanKegiatan->rencanaKegiatan->waktu_selesai)
                                {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->waktu_selesai)->format('H:i') }}
                            @elseif ($laporanKegiatan->rencanaKegiatan->waktu_mulai)
                                {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->waktu_mulai)->format('H:i') }}
                            @else
                                Belum ditentukan
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Pelaksanaan</th>
                        <td>
                            {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->format('d F Y') }} - 
                            {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_selesai)->format('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Realisasi Tanggal Pelaksanaan</th>
                        <td>{{ $laporanKegiatan->getFormattedRealisasiTanggalPelaksanaan() }}</td>
                    </tr>
                    <tr>
                        <th>Rangkaian Kegiatan / Alur Acara</th>
                        <td>{!! $laporanKegiatan->rangkaian_kegiatan !!}</td>
                    </tr>
                    <tr>
                        <th>Target Peserta</th>
                        <td>{{ $laporanKegiatan->rencanaKegiatan->estimasi_peserta ?? '-' }} orang</td>
                    </tr>
                    <tr>
                        <th>Realisasi Jumlah Peserta</th>
                        <td>{{ $laporanKegiatan->realisasi_peserta }} orang</td>
                    </tr>
                    <tr>
                        <th>Profil Peserta</th>
                        <td>{!! $laporanKegiatan->profil_peserta !!}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Hasil dan Output Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Hasil dan Output Kegiatan
                </h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 200px; border-top: none;">Hasil yang Dicapai</th>
                        <td style="border-top: none;">{!! $laporanKegiatan->hasil_dicapai !!}</td>
                    </tr>
                    <tr>
                        <th>Output Nyata</th>
                        <td>{!! $laporanKegiatan->output_nyata !!}</td>
                    </tr>
                    <tr>
                        <th>Dampak Awal yang Terlihat</th>
                        <td>{!! $laporanKegiatan->dampak_awal !!}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Kendala dan Evaluasi -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Kendala dan Evaluasi
                </h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 200px; border-top: none;">Kendala yang Dihadapi</th>
                        <td style="border-top: none;">{!! $laporanKegiatan->kendala ?: '-' !!}</td>
                    </tr>
                    <tr>
                        <th>Solusi yang Dilakukan</th>
                        <td>{!! $laporanKegiatan->solusi ?: '-' !!}</td>
                    </tr>
                    <tr>
                        <th>Catatan Evaluasi & Rekomendasi</th>
                        <td>{!! $laporanKegiatan->evaluasi_rekomendasi ?: '-' !!}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Dokumentasi Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-upload mr-1"></i>
                    Dokumentasi Kegiatan
                </h3>
            </div>
            <div class="card-body">
                <!-- Foto Kegiatan -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Foto Kegiatan</h6>
                    @if (!empty($laporanKegiatan->foto_kegiatan))
                        <div class="row">
                            @foreach ($laporanKegiatan->foto_kegiatan as $index => $foto_kegiatan)
                                @php
                                    // Handle both old format (string) and new format (array)
                                    $filePath = is_array($foto_kegiatan) ? $foto_kegiatan['path'] : $foto_kegiatan;
                                    $fileName = is_array($foto_kegiatan) ? $foto_kegiatan['original_name'] : basename($foto_kegiatan);
                                @endphp
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('public/storage/app/' . $filePath) }}"
                                            class="card-img-top"
                                            style="height: 150px; object-fit: cover; width: 100%;"
                                            alt="{{ $fileName }}">
                                        <div class="card-body p-2">
                                            <small class="text-muted text-truncate d-block" title="{{ $fileName }}">{{ $fileName }}</small>
                                            <a href="{{ asset('public/storage/app/' . $filePath) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tidak ada foto kegiatan</p>
                    @endif
                </div>

                <!-- Daftar Hadir -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Daftar Hadir</h6>
                    @if (!empty($laporanKegiatan->daftar_hadir))
                        <div class="row">
                            @foreach ($laporanKegiatan->daftar_hadir as $index => $file)
                                @php
                                    // Handle both old format (string) and new format (array)
                                    $filePath = is_array($file) ? $file['path'] : $file;
                                    $fileName = is_array($file) ? $file['original_name'] : basename($file);
                                @endphp
                                <div class="col-md-4 mb-2">
                                    <div class="card">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt me-2"></i>
                                                <small class="text-truncate flex-grow-1" title="{{ $fileName }}">{{ $fileName }}</small>
                                            </div>
                                            <a href="{{ asset('public/storage/app/' . $filePath) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tidak ada daftar hadir</p>
                    @endif
                </div>

                <!-- Notulen -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Notulen</h6>
                    @if (!empty($laporanKegiatan->notulen))
                        <div class="row">
                            @foreach ($laporanKegiatan->notulen as $index => $file)
                                @php
                                    // Handle both old format (string) and new format (array)
                                    $filePath = is_array($file) ? $file['path'] : $file;
                                    $fileName = is_array($file) ? $file['original_name'] : basename($file);
                                @endphp
                                <div class="col-md-4 mb-2">
                                    <div class="card">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt me-2"></i>
                                                <small class="text-truncate flex-grow-1" title="{{ $fileName }}">{{ $fileName }}</small>
                                            </div>
                                            <a href="{{ asset('public/storage/app/' . $filePath) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tidak ada notulen</p>
                    @endif
                </div>

                <!-- Materi -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Materi</h6>
                    @if (!empty($laporanKegiatan->materi))
                        <div class="row">
                            @foreach ($laporanKegiatan->materi as $index => $file)
                                @php
                                    // Handle both old format (string) and new format (array)
                                    $filePath = is_array($file) ? $file['path'] : $file;
                                    $fileName = is_array($file) ? $file['original_name'] : basename($file);
                                @endphp
                                <div class="col-md-4 mb-2">
                                    <div class="card">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt me-2"></i>
                                                <small class="text-truncate flex-grow-1" title="{{ $fileName }}">{{ $fileName }}</small>
                                            </div>
                                            <a href="{{ asset('public/storage/app/' . $filePath) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tidak ada materi</p>
                    @endif
                </div>

                <!-- Berita Acara -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Berita Acara</h6>
                    @if (!empty($laporanKegiatan->berita_acara))
                        <div class="row">
                            @foreach ($laporanKegiatan->berita_acara as $index => $file)
                                @php
                                    // Handle both old format (string) and new format (array)
                                    $filePath = is_array($file) ? $file['path'] : $file;
                                    $fileName = is_array($file) ? $file['original_name'] : basename($file);
                                @endphp
                                <div class="col-md-4 mb-2">
                                    <div class="card">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt me-2"></i>
                                                <small class="text-truncate flex-grow-1" title="{{ $fileName }}">{{ $fileName }}</small>
                                            </div>
                                            <a href="{{ asset('public/storage/app/' . $filePath) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tidak ada berita acara</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
