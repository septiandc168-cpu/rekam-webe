@extends('layouts.adminlte')

@section('content_title', 'Detail Rencana Kegiatan')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            width: 100%;
            height: 160px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .list-group-item {
            border-left: 0;
            border-right: 0;
            padding-left: 0;
            padding-right: 0;
        }
        .list-group-item:first-child {
            border-top: 0;
        }
        .list-group-item:last-child {
            border-bottom: 0;
        }
        .info-box-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 15px;
        }
        
        .gallery-img-wrapper {
            overflow: hidden;
            border-radius: 4px;
            cursor: pointer;
            height: 180px;
            width: 100%;
        }
        .gallery-img {
            transition: transform 0.3s ease;
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        .gallery-img-wrapper:hover .gallery-img {
            transform: scale(1.1);
        }
        
        /* Timeline Tracker CSS */
        .timeline-tracker {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 2rem;
            margin-top: 1rem;
            padding: 0 20px;
        }
        .timeline-tracker::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 50px;
            right: 50px;
            height: 4px;
            background: #e9ecef;
            z-index: 1;
        }
        .timeline-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }
        .timeline-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 14px;
            border: 4px solid #fff;
            transition: all 0.3s;
        }
        .timeline-step.active .timeline-icon {
            background: #007bff;
            color: #fff;
            box-shadow: 0 0 0 3px #007bff;
        }
        .timeline-step.completed .timeline-icon {
            background: #28a745;
            color: #fff;
            box-shadow: 0 0 0 3px #28a745;
        }
        .timeline-step.rejected .timeline-icon {
            background: #dc3545;
            color: #fff;
            box-shadow: 0 0 0 3px #dc3545;
        }
        .timeline-step.revision .timeline-icon {
            background: #ffc107;
            color: #212529;
            box-shadow: 0 0 0 3px #ffc107;
        }
        .timeline-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
        }
        .timeline-step.active .timeline-label { color: #007bff; }
        .timeline-step.completed .timeline-label { color: #28a745; }
        .timeline-step.rejected .timeline-label { color: #dc3545; }
        .timeline-step.revision .timeline-label { color: #d39e00; }

        /* Accordion polished */
        .accordion-toggle-icon {
            font-size: 0.75rem;
            transition: transform 0.3s;
        }
        .accordion-item-header {
            transition: background-color 0.2s ease;
        }
        .accordion-item-header:hover {
            background-color: #f0f4f8 !important;
        }
        .accordion-item-header.is-open {
            background-color: #eef2ff !important;
        }
        .accordion-item-header.is-open h6,
        .accordion-item-header.is-open .accordion-toggle-icon {
            color: #1a365d !important;
        }
        .accordion-item-header.is-open .accordion-toggle-icon {
            transform: rotate(180deg);
        }

@endpush

@section('content')

<div class="container-fluid text-sm">
    <!-- Header & Action Bar -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 pb-3 border-bottom">
        <div class="d-flex align-items-center mb-3 mb-lg-0 pr-lg-3" style="flex: 1; min-width: 0;">
            <h4 class="mb-0 mr-3 fw-bold text-dark" style="line-height: 1.4;" title="{{ $rencana_kegiatan->nama_kegiatan }}">{{ $rencana_kegiatan->nama_kegiatan }}</h4>
            <div class="flex-shrink-0 mt-2 mt-sm-0">
                {!! $rencana_kegiatan->status_badge !!}
            </div>
        </div>
        
        <div class="d-flex align-items-center flex-wrap flex-shrink-0">
            @if(auth()->user()->role->role_name === 'admin' && in_array($rencana_kegiatan->status, ['diajukan', 'revisi']))
                <!-- Tombol Setujui Langsung -->
                <form action="{{ route('rencana_kegiatan.setujui', $rencana_kegiatan->uuid ?? $rencana_kegiatan->id) }}" method="POST" class="d-inline mr-2">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Setujui</button>
                </form>

                @if($rencana_kegiatan->status !== 'revisi')
                <!-- Tombol Buka Modal Revisi -->
                <button type="button" class="btn btn-sm btn-warning font-weight-bold mr-2" data-toggle="modal" data-target="#modal-revisi">
                    <i class="fas fa-edit mr-1"></i> Minta Revisi
                </button>
                @endif
                
                <!-- Tombol Buka Modal Tolak -->
                <button type="button" class="btn btn-danger btn-sm mr-2" data-toggle="modal" data-target="#modal-tolak">
                    <i class="fas fa-times mr-1"></i> Tolak
                </button>
            @endif

            @if(auth()->user()->role->role_name === 'admin' && ($rencana_kegiatan->status === \App\Models\RencanaKegiatan::STATUS_SELESAI || ($rencana_kegiatan->hasLaporan() && $rencana_kegiatan->laporanKegiatan->status === 'final')))
                <a href="{{ route('laporan_kegiatan.show', $rencana_kegiatan->laporanKegiatan->uuid ?? $rencana_kegiatan->laporanKegiatan->id) }}" class="btn btn-sm btn-info mr-2 shadow-sm fw-bold text-white">
                    <i class="fas fa-file-alt mr-1"></i> Lihat Laporan
                </a>
            @endif

            @php
                $missingFieldsShow = [];
                if ($rencana_kegiatan->status === \App\Models\RencanaKegiatan::STATUS_DRAFT && auth()->user()->role->role_name === 'anggota' && $rencana_kegiatan->user_id == auth()->id()) {
                    if (empty(trim($rencana_kegiatan->nama_kegiatan ?? ''))) $missingFieldsShow[] = 'Nama Kegiatan';
                    if (empty(trim($rencana_kegiatan->jenis_kegiatan ?? ''))) $missingFieldsShow[] = 'Jenis Kegiatan';
                    elseif ($rencana_kegiatan->jenis_kegiatan === 'lainnya' && empty(trim($rencana_kegiatan->jenis_kegiatan_lainnya ?? ''))) $missingFieldsShow[] = 'Deskripsi Jenis Kegiatan Lainnya';
                    if (empty(trim(strip_tags($rencana_kegiatan->deskripsi ?? '')))) $missingFieldsShow[] = 'Deskripsi Kegiatan';
                    if (empty(trim(strip_tags($rencana_kegiatan->tujuan ?? '')))) $missingFieldsShow[] = 'Tujuan Kegiatan';
                    if (empty(trim($rencana_kegiatan->penanggung_jawab ?? ''))) $missingFieldsShow[] = 'Penanggung Jawab';
                    if (empty(trim($rencana_kegiatan->kelompok ?? ''))) $missingFieldsShow[] = 'Kelompok / Komunitas Pelaksana';
                    if (empty($rencana_kegiatan->estimasi_peserta)) $missingFieldsShow[] = 'Estimasi Jumlah Peserta';
                    if (empty($rencana_kegiatan->tanggal_mulai)) $missingFieldsShow[] = 'Tanggal Mulai';
                    if (empty($rencana_kegiatan->tanggal_selesai)) $missingFieldsShow[] = 'Tanggal Selesai';
                    if (empty($rencana_kegiatan->waktu_mulai)) $missingFieldsShow[] = 'Waktu Mulai';
                    if (empty($rencana_kegiatan->waktu_selesai)) $missingFieldsShow[] = 'Waktu Selesai';
                    if (empty(trim($rencana_kegiatan->desa ?? ''))) $missingFieldsShow[] = 'Desa / Wilayah';
                    if (empty($rencana_kegiatan->lat) || empty($rencana_kegiatan->lng)) $missingFieldsShow[] = 'Koordinat Lokasi (Peta)';
                    if (empty(trim(strip_tags($rencana_kegiatan->rincian_kebutuhan ?? '')))) $missingFieldsShow[] = 'Rincian Kebutuhan';
                    if (empty($rencana_kegiatan->anggaran_kegiatan)) $missingFieldsShow[] = 'File Anggaran Kegiatan';
                }
            @endphp

            @if(auth()->user()->role->role_name === 'anggota' && $rencana_kegiatan->user_id == auth()->id())
                @if ($rencana_kegiatan->user_id == auth()->id())
                    @if (in_array($rencana_kegiatan->status, [\App\Models\RencanaKegiatan::STATUS_DRAFT, \App\Models\RencanaKegiatan::STATUS_REVISI]))
                        @if ($rencana_kegiatan->status === \App\Models\RencanaKegiatan::STATUS_DRAFT)
                            @if(empty($missingFieldsShow))
                                <form action="{{ route('rencana_kegiatan.ajukan', $rencana_kegiatan->uuid ?? $rencana_kegiatan->id) }}" method="POST" class="d-inline mr-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn bg-navy text-white btn-sm shadow-sm fw-bold">
                                        <i class="fas fa-paper-plane mr-1"></i> Ajukan Sekarang
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn bg-navy text-white btn-sm shadow-sm fw-bold mr-2" style="opacity: 0.8;" onclick="Swal.fire({icon: 'error', title: 'Draft Belum Lengkap!', text: 'Rencana kegiatan ini belum dapat diajukan karena ada {{ count($missingFieldsShow) }} data wajib yang belum terisi. Silakan lengkapi data terlebih dahulu.', confirmButtonText: 'Mengerti', confirmButtonColor: '#001f3f'})">
                                    <i class="fas fa-paper-plane mr-1"></i> Ajukan Sekarang
                                </button>
                            @endif
                        @endif
                        <a href="{{ route('rencana_kegiatan.edit', $rencana_kegiatan->uuid ?? $rencana_kegiatan->id) }}" class="btn btn-warning btn-sm mr-2 shadow-sm text-dark">
                            <i class="fas fa-edit mr-1"></i> Edit Rencana
                        </a>
                    @endif
                    
                    @if ($rencana_kegiatan->status === \App\Models\RencanaKegiatan::STATUS_DRAFT)
                        <form action="{{ route('rencana_kegiatan.destroy', $rencana_kegiatan->uuid ?? $rencana_kegiatan->id) }}" method="POST" class="d-inline mr-2" data-confirm-delete="true">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    @endif
                @endif
            @endif
            
            {{-- Tombol Lihat Laporan Kegiatan (Khusus Anggota jika sudah ada laporan) --}}
            @if(auth()->user()->role->role_name === 'anggota' && $rencana_kegiatan->user_id == auth()->id())
                @if($rencana_kegiatan->hasLaporan() && $rencana_kegiatan->laporanKegiatan)
                    <a href="{{ route('laporan_kegiatan.show', $rencana_kegiatan->laporanKegiatan->uuid ?? $rencana_kegiatan->laporanKegiatan->id) }}" class="btn btn-sm btn-info mr-2 shadow-sm fw-bold text-white">
                        <i class="fas fa-file-alt mr-1"></i> Lihat Laporan Kegiatan
                        @php
                            $lapStatus = $rencana_kegiatan->laporanKegiatan->status;
                            $badgeLapClass = match($lapStatus) {
                                'draft' => 'bg-secondary',
                                'diajukan' => 'bg-primary',
                                'revisi' => 'bg-warning text-dark',
                                'final' => 'bg-success',
                                default => 'bg-light text-dark'
                            };
                        @endphp
                        <span class="badge {{ $badgeLapClass }} ml-1" style="font-size: 0.7rem;">{{ strtoupper($lapStatus) }}</span>
                    </a>
                @endif
            @endif

            @php
                $backRoute = route('rencana_kegiatan.index');
                if ($rencana_kegiatan->status === 'disetujui' || request('from') === 'laporan') {
                    $backRoute = route('laporan_kegiatan.index');
                } elseif (in_array($rencana_kegiatan->status, ['selesai', 'draft']) || request('from') === 'history') {
                    $backRoute = route('history_realisasi.index');
                } elseif (auth()->user()->role->role_name === 'anggota' && $rencana_kegiatan->user_id != auth()->id()) {
                    // Jika anggota melihat dokumen milik orang lain (dari dashboard), kembalikan ke dashboard
                    $backRoute = route('home');
                }
            @endphp
            <a href="{{ $backRoute }}" class="btn btn-secondary text-white btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @php
        $missingFieldsShow = [];
        if ($rencana_kegiatan->status === \App\Models\RencanaKegiatan::STATUS_DRAFT && auth()->user()->role->role_name === 'anggota' && $rencana_kegiatan->user_id == auth()->id()) {
            if (empty(trim($rencana_kegiatan->nama_kegiatan ?? ''))) $missingFieldsShow[] = 'Nama Kegiatan';
            if (empty(trim($rencana_kegiatan->jenis_kegiatan ?? ''))) $missingFieldsShow[] = 'Jenis Kegiatan';
            elseif ($rencana_kegiatan->jenis_kegiatan === 'lainnya' && empty(trim($rencana_kegiatan->jenis_kegiatan_lainnya ?? ''))) $missingFieldsShow[] = 'Deskripsi Jenis Kegiatan Lainnya';
            if (empty(trim(strip_tags($rencana_kegiatan->deskripsi ?? '')))) $missingFieldsShow[] = 'Deskripsi Kegiatan';
            if (empty(trim(strip_tags($rencana_kegiatan->tujuan ?? '')))) $missingFieldsShow[] = 'Tujuan Kegiatan';
            if (empty(trim($rencana_kegiatan->penanggung_jawab ?? ''))) $missingFieldsShow[] = 'Penanggung Jawab';
            if (empty(trim($rencana_kegiatan->kelompok ?? ''))) $missingFieldsShow[] = 'Kelompok / Komunitas Pelaksana';
            if (empty($rencana_kegiatan->estimasi_peserta)) $missingFieldsShow[] = 'Estimasi Jumlah Peserta';
            if (empty($rencana_kegiatan->tanggal_mulai)) $missingFieldsShow[] = 'Tanggal Mulai';
            if (empty($rencana_kegiatan->tanggal_selesai)) $missingFieldsShow[] = 'Tanggal Selesai';
            if (empty($rencana_kegiatan->waktu_mulai)) $missingFieldsShow[] = 'Waktu Mulai';
            if (empty($rencana_kegiatan->waktu_selesai)) $missingFieldsShow[] = 'Waktu Selesai';
            if (empty(trim($rencana_kegiatan->desa ?? ''))) $missingFieldsShow[] = 'Desa / Wilayah';
            if (empty($rencana_kegiatan->lat) || empty($rencana_kegiatan->lng)) $missingFieldsShow[] = 'Koordinat Lokasi (Peta)';
            if (empty(trim(strip_tags($rencana_kegiatan->rincian_kebutuhan ?? '')))) $missingFieldsShow[] = 'Rincian Kebutuhan';
            if (empty($rencana_kegiatan->anggaran_kegiatan)) $missingFieldsShow[] = 'File Anggaran Kegiatan';
        }
    @endphp

    @if(!empty($missingFieldsShow))
        <div class="alert alert-warning alert-important border-0 shadow-sm mb-4 p-3 rounded position-relative alert-dismissible fade show" role="alert" style="background-color: #fff8e6; border-left: 4px solid #ffc107 !important;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="top: 10px; right: 15px; opacity: 0.7;">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="d-flex align-items-start pr-4">
                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center mr-3 mt-1" style="width: 36px; height: 36px; flex-shrink: 0;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                        <h6 class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">Draft Belum Lengkap</h6>
                        <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 0.75rem;">{{ count($missingFieldsShow) }} Data Wajib Belum Terisi</span>
                    </div>
                    <p class="text-muted mb-0 mt-1" style="font-size: 0.88rem;">
                        Lengkapi data rencana kegiatan ini agar dapat diajukan ke admin. 
                        <a href="javascript:void(0);" onclick="toggleMissingFields(event)" class="text-primary font-weight-bold ml-1" style="text-decoration: underline;">
                            <i class="fas fa-chevron-down mr-1" id="icon-missing-chevron"></i><span id="text-missing-toggle">Lihat Selengkapnya</span>
                        </a>
                    </p>
                    <div class="mt-2" id="collapseMissingFields" style="display: none;">
                        <div class="p-2 rounded bg-white border" style="border-color: #ffe8a1 !important;">
                            <strong class="d-block text-dark mb-1" style="font-size: 0.8rem;">Daftar Kolom Wajib yang Belum Terisi:</strong>
                            <div class="d-flex flex-wrap" style="gap: 6px;">
                                @foreach($missingFieldsShow as $mf)
                                    <span class="badge bg-light text-dark border p-1" style="font-size: 0.78rem;">
                                        <i class="fas fa-exclamation-circle text-warning mr-1"></i> {{ $mf }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!empty($rencana_kegiatan->keterangan_status))
        @php
            $st = $rencana_kegiatan->status;
            $alertClass = match($st) {
                'disetujui', 'selesai' => 'alert-success',
                'ditolak' => 'alert-danger',
                'revisi' => 'alert-warning',
                default => 'alert-info'
            };
            $iconClass = match($st) {
                'disetujui', 'selesai' => 'fa-check-circle',
                'ditolak' => 'fa-times-circle',
                'revisi' => 'fa-exclamation-triangle',
                default => 'fa-info-circle'
            };
            $titleText = match($st) {
                'revisi' => 'Catatan Revisi:',
                'disetujui' => 'Catatan Persetujuan:',
                'ditolak' => 'Catatan Penolakan:',
                'selesai' => 'Catatan Penyelesaian / Verifikasi Laporan:',
                default => 'Catatan Status:'
            };
        @endphp
        <div class="alert {{ $alertClass }} mb-4 shadow-sm border-0">
            <i class="fas {{ $iconClass }} mr-1"></i> <strong>{{ $titleText }}</strong><br>
            <span class="d-block mt-1">{!! nl2br(e($rencana_kegiatan->keterangan_status)) !!}</span>
        </div>
    @endif

    <!-- Visual Progress Tracker -->
    @php
        $st = $rencana_kegiatan->status;
        $hasLap = $rencana_kegiatan->hasLaporan();
        
        $isDiajukan = in_array($st, ['diajukan', 'revisi', 'disetujui', 'ditolak', 'selesai']);
        $isRevisi = $st === 'revisi';
        $isDisetujui = in_array($st, ['disetujui', 'selesai']);
        $isDitolak = $st === 'ditolak';
        $isSelesai = $st === 'selesai' || ($hasLap && $rencana_kegiatan->laporanKegiatan->status === 'final');

        $step2Class = '';
        if ($isRevisi) {
            $step2Class = 'revision';
        } elseif ($isDisetujui || $isDitolak || $isSelesai) {
            $step2Class = 'completed';
        } elseif ($isDiajukan) {
            $step2Class = 'active';
        }
    @endphp
    <div class="card shadow-sm mb-4 border-0 rounded">
        <div class="card-body py-4">
            <div class="timeline-tracker">
                <div class="timeline-step completed">
                    <div class="timeline-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="timeline-label">Draft Dibuat</div>
                </div>
                <div class="timeline-step {{ $step2Class }}">
                    <div class="timeline-icon">
                        <i class="fas {{ $isRevisi ? 'fa-exclamation-triangle' : 'fa-paper-plane' }}"></i>
                    </div>
                    <div class="timeline-label">{{ $isRevisi ? 'Perlu Revisi' : 'Diajukan' }}</div>
                </div>
                <div class="timeline-step {{ $isDisetujui ? ($isSelesai ? 'completed' : 'active') : ($isDitolak ? 'rejected' : '') }}">
                    <div class="timeline-icon">
                        <i class="fas {{ $isDitolak ? 'fa-times' : 'fa-check' }}"></i>
                    </div>
                    <div class="timeline-label">{{ $isDitolak ? 'Ditolak' : 'Disetujui' }}</div>
                </div>
                <div class="timeline-step {{ $isSelesai ? 'completed' : '' }}">
                    <div class="timeline-icon"><i class="fas fa-flag-checkered"></i></div>
                    <div class="timeline-label">Laporan Selesai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Layout 8-4 -->
    <div class="row">
        <!-- Kolom Kiri: Informasi Utama -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title fw-bold text-dark"><i class="fas fa-info-circle mr-1"></i> Detail Kegiatan</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-primary"><i class="fas fa-calendar-alt"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Tanggal Pelaksanaan</small>
                                        <strong>
                                            @if ($rencana_kegiatan->tanggal_mulai)
                                                {{ \Carbon\Carbon::parse($rencana_kegiatan->tanggal_mulai)->translatedFormat('d F Y') }}
                                                @if ($rencana_kegiatan->tanggal_selesai && $rencana_kegiatan->tanggal_selesai != $rencana_kegiatan->tanggal_mulai)
                                                    - {{ \Carbon\Carbon::parse($rencana_kegiatan->tanggal_selesai)->translatedFormat('d F Y') }}
                                                @endif
                                            @endif
                                        </strong>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-success"><i class="fas fa-clock"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Waktu</small>
                                        <strong>
                                            @if ($rencana_kegiatan->waktu_mulai)
                                                {{ \Carbon\Carbon::parse($rencana_kegiatan->waktu_mulai)->format('H:i') }}
                                                @if ($rencana_kegiatan->waktu_selesai)
                                                    - {{ \Carbon\Carbon::parse($rencana_kegiatan->waktu_selesai)->format('H:i') }}
                                                @endif
                                                WIB
                                            @else
                                                Belum ditentukan
                                            @endif
                                        </strong>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-info"><i class="fas fa-tags"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Jenis Kegiatan</small>
                                        <strong>{{ $rencana_kegiatan->getJenisKegiatanLabel() }}</strong>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-warning"><i class="fas fa-users"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Target Peserta</small>
                                        <strong>{{ $rencana_kegiatan->estimasi_peserta }} Orang</strong>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-danger"><i class="fas fa-user-tie"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Penanggung Jawab</small>
                                        <strong>{{ $rencana_kegiatan->penanggung_jawab }}</strong>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-secondary"><i class="fas fa-sitemap"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Kelompok / Komunitas</small>
                                        <strong>{{ $rencana_kegiatan->kelompok }}</strong>
                                    </div>
                                </li>
                            </ul>
                        </div>
                </div>{{-- end row mb-4 --}}

                </div>{{-- end card-body info grid --}}
            </div>{{-- end card Detail Kegiatan --}}

            {{-- Card: Narasi Kegiatan (Accordion) --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex align-items-center">
                    <h3 class="card-title fw-bold text-dark mb-0"><i class="fas fa-file-alt mr-2"></i>Narasi Kegiatan</h3>
                    <small class="text-muted ml-2">— klik bagian untuk membuka</small>
                </div>
                <div class="card-body p-3">

                    {{-- ACCORDION --}}
                    <div class="accordion" id="accordionRencana">

                        {{-- Deskripsi Kegiatan --}}
                        <div class="rounded mb-2 overflow-hidden" style="border: 1px solid #e2e8f0;">
                            <div class="accordion-item-header card-header bg-white py-2 px-3" id="headDeskripsi"
                                 style="cursor:pointer;" data-toggle="collapse" data-target="#collapseDeskripsi"
                                 aria-expanded="false" aria-controls="collapseDeskripsi">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-align-left mr-2" style="color:#1a365d;"></i>Deskripsi Kegiatan</h6>
                                    <i class="fas fa-chevron-down text-muted accordion-toggle-icon"></i>
                                </div>
                            </div>
                            <div id="collapseDeskripsi" class="collapse" aria-labelledby="headDeskripsi" data-parent="#accordionRencana">
                                <div class="card-body text-justify" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                                    {!! $rencana_kegiatan->deskripsi !!}
                                </div>
                            </div>
                        </div>

                        {{-- Tujuan --}}
                        <div class="rounded mb-2 overflow-hidden" style="border: 1px solid #e2e8f0;">
                            <div class="accordion-item-header card-header bg-white py-2 px-3" id="headTujuan"
                                 style="cursor:pointer;" data-toggle="collapse" data-target="#collapseTujuan"
                                 aria-expanded="false" aria-controls="collapseTujuan">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-bullseye mr-2" style="color:#1a365d;"></i>Tujuan Kegiatan</h6>
                                    <i class="fas fa-chevron-down text-muted accordion-toggle-icon"></i>
                                </div>
                            </div>
                            <div id="collapseTujuan" class="collapse" aria-labelledby="headTujuan" data-parent="#accordionRencana">
                                <div class="card-body text-justify" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                                    {!! $rencana_kegiatan->tujuan !!}
                                </div>
                            </div>
                        </div>

                        {{-- Rincian Kebutuhan Logistik --}}
                        <div class="rounded mb-0 overflow-hidden" style="border: 1px solid #e2e8f0;">
                            <div class="accordion-item-header card-header bg-white py-2 px-3" id="headLogistik"
                                 style="cursor:pointer;" data-toggle="collapse" data-target="#collapseLogistik"
                                 aria-expanded="false" aria-controls="collapseLogistik">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-boxes mr-2" style="color:#1a365d;"></i>Rincian Kebutuhan Logistik</h6>
                                    <i class="fas fa-chevron-down text-muted accordion-toggle-icon"></i>
                                </div>
                            </div>
                            <div id="collapseLogistik" class="collapse" aria-labelledby="headLogistik" data-parent="#accordionRencana">
                                <div class="card-body text-justify" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                                    {!! $rencana_kegiatan->rincian_kebutuhan !!}
                                </div>
                            </div>
                        </div>

                    </div>{{-- End accordion --}}

                </div>{{-- end card-body accordion --}}
            </div>{{-- end card Narasi --}}

            {{-- Media Publikasi --}}
            @php
                $fotoData = $rencana_kegiatan->foto;
                if (is_string($fotoData)) $fotoData = json_decode($fotoData, true);
                $fotoData = is_array($fotoData) ? $fotoData : [];
            @endphp
            @if (count($fotoData) > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark"><i class="fas fa-images mr-1"></i> Media Publikasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($fotoData as $index => $foto)
                                @if ($foto)
                                    @php
                                        if (is_array($foto)) {
                                            $fotoPath = $foto['path'];
                                            $fotoName = $foto['original_name'];
                                        } else {
                                            $fotoPath = $foto;
                                            $fotoName = 'Foto ' . ($index + 1);
                                        }
                                    @endphp
                                    <div class="col-6 col-sm-4 col-md-4 col-lg-3 mb-3">
                                        <img src="/public/storage/app/{{ $fotoPath }}" class="gallery-img border shadow-sm" alt="{{ $fotoName }}" data-toggle="modal" data-target="#imageModal{{ $index }}" style="height: 180px; width: 100%; object-fit: cover;">
                                    </div>

                                    <!-- Modal for Image -->
                                    <div class="modal fade" id="imageModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">{{ $fotoName }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center p-0 bg-dark">
                                                    <img src="/public/storage/app/{{ $fotoPath }}" class="img-fluid" style="max-height: 80vh;" alt="{{ $fotoName }}">
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
        </div>{{-- end col-md-8 --}}

        <!-- Kolom Kanan: Lokasi & Dokumen -->
        <div class="col-md-4">
            <!-- Peta Lokasi -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-2 px-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-map-marker-alt mr-1"></i> Titik Lokasi</h6>
                </div>
                <div class="card-body p-2">
                    <div class="d-flex align-items-center mb-2 px-1">
                        <i class="fas fa-map-pin text-success mr-2" style="font-size:0.85rem;"></i>
                        <div>
                            <small class="text-muted" style="font-size:0.72rem;">Desa / Wilayah</small>
                            <strong class="d-block" style="font-size:0.82rem;">{{ $rencana_kegiatan->desa }}</strong>
                        </div>
                    </div>
                    <div id="map"></div>
                    <small class="text-muted d-block mt-1 text-center" style="font-size:0.7rem;">{{ $rencana_kegiatan->lat }}, {{ $rencana_kegiatan->lng }}</small>
                </div>
            </div>

            <!-- Anggaran Kegiatan -->
            @if (!empty($rencana_kegiatan->anggaran_kegiatan))
                @php
                    $anggaranFile = $rencana_kegiatan->anggaran_kegiatan;
                    $anggaranPath = is_array($anggaranFile) ? $anggaranFile['path'] : $anggaranFile;
                    $anggaranName = is_array($anggaranFile) ? $anggaranFile['original_name'] : basename($anggaranPath);
                @endphp
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white py-2 px-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-file-invoice-dollar mr-1"></i> Anggaran Kegiatan</h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-file-excel mr-2" style="font-size:1.4rem; color:#001f3f;"></i>
                            <span class="text-truncate font-weight-bold" style="font-size:0.82rem;" title="{{ $anggaranName }}">{{ $anggaranName }}</span>
                        </div>
                        <a href="/public/storage/app/{{ $anggaranPath }}" target="_blank" class="btn btn-sm bg-navy text-white btn-block">
                            <i class="fas fa-download mr-1"></i> Unduh Anggaran
                        </a>
                    </div>
                </div>
            @endif

            <!-- Dokumen Pendukung -->
            @php
                $dokumens = $rencana_kegiatan->dokumen;
                if (is_string($dokumens)) $dokumens = json_decode($dokumens, true);
                $dokumens = is_array($dokumens) ? $dokumens : [];
            @endphp
            @if (count($dokumens) > 0)
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white py-2 px-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-file-alt mr-1"></i> Dokumen Pendukung</h6>
                    </div>
                    <div class="card-body py-2 px-3">
                        @foreach ($dokumens as $file)
                            @if ($file)
                                @php
                                    if (is_array($file)) {
                                        $filePath = $file['path'];
                                        $fileName = $file['original_name'];
                                    } else {
                                        $filePath = $file;
                                        $fileName = basename($file);
                                    }
                                @endphp
                                <a href="/public/storage/app/{{ $filePath }}" target="_blank"
                                   class="btn btn-sm bg-navy text-white btn-block text-left mb-1 text-truncate"
                                   title="{{ $fileName }}" style="font-size:0.78rem;">
                                    <i class="fas fa-file-pdf mr-1"></i> {{ $fileName }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

        </div> <!-- End of col-md-4 -->
    </div> <!-- End of first row -->
</div> <!-- End of d-print-none container -->

<!-- MODAL REVISI -->
<div class="modal fade" id="modal-revisi" tabindex="-1" role="dialog" aria-labelledby="modal-revisi-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('rencana_kegiatan.revisi', $rencana_kegiatan->uuid ?? $rencana_kegiatan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-white">
                    <h5 class="modal-title font-weight-bold text-dark" id="modal-revisi-label"><i class="fas fa-edit mr-2 text-warning"></i> Catatan Revisi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keterangan_status_revisi">Berikan alasan/catatan perbaikan untuk Anggota <span class="text-danger">*</span></label>
                        <textarea name="keterangan_status" id="keterangan_status_revisi" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary text-white" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Batal</button>
                    <button type="submit" class="btn btn-warning font-weight-bold"><i class="fas fa-paper-plane mr-1"></i> Kirim Permintaan Revisi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TOLAK -->
<div class="modal fade" id="modal-tolak" tabindex="-1" role="dialog" aria-labelledby="modal-tolak-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('rencana_kegiatan.tolak', $rencana_kegiatan->uuid ?? $rencana_kegiatan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-white">
                    <h5 class="modal-title font-weight-bold text-dark" id="modal-tolak-label"><i class="fas fa-ban mr-2 text-danger"></i> Tolak Rencana Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keterangan_status_tolak">Berikan alasan mengapa rencana kegiatan ini ditolak <span class="text-danger">*</span></label>
                        <textarea name="keterangan_status" id="keterangan_status_tolak" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary text-white" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-ban mr-1"></i> Tolak Rencana Kegiatan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function toggleMissingFields(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            var detailEl = document.getElementById('collapseMissingFields');
            var chevron = document.getElementById('icon-missing-chevron');
            var textEl = document.getElementById('text-missing-toggle');

            if (!detailEl) return;

            if (detailEl.style.display === 'none' || detailEl.style.display === '') {
                detailEl.style.display = 'block';
                if (chevron) chevron.className = 'fas fa-chevron-up mr-1';
                if (textEl) textEl.textContent = 'Sembunyikan';
            } else {
                detailEl.style.display = 'none';
                if (chevron) chevron.className = 'fas fa-chevron-down mr-1';
                if (textEl) textEl.textContent = 'Lihat Selengkapnya';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Ambil koordinat dari PHP
            const lat = {{ $rencana_kegiatan->lat ?: -0.0227 }};
            const lng = {{ $rencana_kegiatan->lng ?: 109.3323 }};
            
            // Inisialisasi Peta (Statis, tanpa zoom control dan drag)
            const map = L.map('map', {
                center: [lat, lng],
                zoom: 15,
                zoomControl: false,
                dragging: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                keyboard: false
            });
            
            // Tile Layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            
            // Tambahkan Marker
            L.marker([lat, lng]).addTo(map)
             .bindPopup("<div class='text-center'><b>Titik Lokasi</b><br>{{ $rencana_kegiatan->desa }}</div>").openPopup();

            // Accordion chevron rotation + active state
            var collapseEls = document.querySelectorAll('#accordionRencana .collapse');
            collapseEls.forEach(function (el) {
                el.addEventListener('show.bs.collapse', function () {
                    var header = document.querySelector('[data-target="#' + el.id + '"]');
                    if (header) {
                        header.classList.add('is-open');
                        var icon = header.querySelector('.accordion-toggle-icon');
                        if (icon) icon.style.transform = 'rotate(180deg)';
                    }
                });
                el.addEventListener('hide.bs.collapse', function () {
                    var header = document.querySelector('[data-target="#' + el.id + '"]');
                    if (header) {
                        header.classList.remove('is-open');
                        var icon = header.querySelector('.accordion-toggle-icon');
                        if (icon) icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        });
    </script>
@endpush
