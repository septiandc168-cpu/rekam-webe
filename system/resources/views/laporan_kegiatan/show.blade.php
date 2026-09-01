@extends('layouts.adminlte')

@section('content_title', 'Detail Laporan Kegiatan')

@php
    $formatTextList = function ($text) {
        if (empty($text)) return '-';
        
        if (strip_tags($text) !== $text) {
            return $text;
        }

        $pattern = '/(?:\r?\n|\s)*(?=\d+[\.\)]\s+)/';
        $items = preg_split($pattern, trim($text), -1, PREG_SPLIT_NO_EMPTY);

        if (count($items) > 1) {
            $html = '<ol class="mb-0 pl-3 text-justify" style="line-height: 1.6;">';
            foreach ($items as $item) {
                $cleanItem = preg_replace('/^\d+[\.\)]\s*/', '', trim($item));
                if (!empty($cleanItem)) {
                    $html .= '<li class="mb-1">' . e($cleanItem) . '</li>';
                }
            }
            $html .= '</ol>';
            return $html;
        }

        $lines = array_filter(array_map('trim', explode("\n", trim($text))));
        if (count($lines) > 1) {
            $html = '<ul class="mb-0 pl-3 text-justify" style="line-height: 1.6;">';
            foreach ($lines as $line) {
                $html .= '<li class="mb-1">' . e($line) . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }

        return '<div class="text-justify" style="line-height: 1.6;">' . nl2br(e($text)) . '</div>';
    };
@endphp

@push('styles')
    <style>
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
        .timeline-step.rejected .timeline-label { color: #d39e00; }

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
    </style>
@endpush

@section('content')
<div class="container-fluid text-sm">
    <!-- Header & Action Bar -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 pb-3 border-bottom">
        <div class="d-flex flex-column mb-3 mb-lg-0 pr-lg-3" style="flex: 1; min-width: 0;">
            <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 8px;">
                @if($laporanKegiatan->isDarurat())
                    <span class="d-inline-flex align-items-center" style="background:#f1f5f9; color:#334155; padding:3px 10px; border-radius:4px; font-size:0.75rem; font-weight:600; border:1px solid #e2e8f0; white-space:nowrap; flex-shrink:0;">
                        Laporan Langsung
                    </span>
                @endif
                <div class="flex-shrink-0 d-inline-flex align-items-center">
                    {!! $laporanKegiatan->status_badge !!}
                </div>
            </div>
            <h4 class="mb-0 fw-bold text-dark" style="line-height: 1.4;">
                {{ $laporanKegiatan->isDarurat() ? $laporanKegiatan->judul_kegiatan : $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}
            </h4>
        </div>

        @php
            $missingFieldsShow = [];
            if ($laporanKegiatan->status === \App\Models\LaporanKegiatan::STATUS_DRAFT && auth()->user()->role->role_name === 'anggota' && $laporanKegiatan->user_id == auth()->id()) {
                if ($laporanKegiatan->isDarurat()) {
                    if (empty(trim($laporanKegiatan->judul_kegiatan ?? ''))) $missingFieldsShow[] = 'Judul Kegiatan';
                    if (empty(trim($laporanKegiatan->lokasi_kegiatan ?? ''))) $missingFieldsShow[] = 'Lokasi Kegiatan';
                }
                if (empty($laporanKegiatan->realisasi_tanggal_mulai)) $missingFieldsShow[] = 'Realisasi Tanggal Mulai';
                if (empty($laporanKegiatan->realisasi_tanggal_selesai)) $missingFieldsShow[] = 'Realisasi Tanggal Selesai';
                if (empty(trim(strip_tags($laporanKegiatan->rangkaian_kegiatan ?? '')))) $missingFieldsShow[] = 'Rangkaian Kegiatan';
                if (empty($laporanKegiatan->realisasi_peserta)) $missingFieldsShow[] = 'Realisasi Jumlah Peserta';
                if (empty(trim(strip_tags($laporanKegiatan->profil_peserta ?? '')))) $missingFieldsShow[] = 'Profil Peserta';
                if (empty(trim(strip_tags($laporanKegiatan->hasil_dicapai ?? '')))) $missingFieldsShow[] = 'Hasil yang Dicapai';
                if (empty(trim(strip_tags($laporanKegiatan->output_nyata ?? '')))) $missingFieldsShow[] = 'Output Nyata';
                if (empty(trim(strip_tags($laporanKegiatan->dampak_awal ?? '')))) $missingFieldsShow[] = 'Dampak Awal';
                if (empty(trim(strip_tags($laporanKegiatan->kendala ?? '')))) $missingFieldsShow[] = 'Kendala yang Dihadapi';
                if (empty(trim(strip_tags($laporanKegiatan->solusi ?? '')))) $missingFieldsShow[] = 'Solusi yang Dilakukan';
                if (empty(trim(strip_tags($laporanKegiatan->evaluasi_rekomendasi ?? '')))) $missingFieldsShow[] = 'Catatan Evaluasi & Rekomendasi';
            }
        @endphp

        <div class="d-flex align-items-center flex-wrap flex-shrink-0">
            @if ($laporanKegiatan->status === \App\Models\LaporanKegiatan::STATUS_DIAJUKAN && auth()->user()->role->role_name === 'admin')
                <!-- Tombol Terima & Finalisasi -->
                <form action="{{ route('laporan_kegiatan.terima', $laporanKegiatan->uuid) }}" method="POST" class="d-inline mr-2">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success btn-sm font-weight-bold" onclick="return confirm('Terima & Finalisasi Laporan ini?')">
                        <i class="fas fa-check-circle mr-1"></i> Terima & Finalisasi
                    </button>
                </form>

                <!-- Tombol Buka Modal Revisi -->
                <button type="button" class="btn btn-sm btn-warning font-weight-bold mr-2 text-dark" data-toggle="modal" data-target="#modal-revisi">
                    <i class="fas fa-edit mr-1"></i> Minta Revisi
                </button>
            @endif

            @if ($laporanKegiatan->status === \App\Models\LaporanKegiatan::STATUS_FINAL)
                @can('print', $laporanKegiatan)
                    <button type="button" onclick="printLaporan('{{ route('laporan_kegiatan.print', $laporanKegiatan) }}')" class="btn btn-sm bg-navy text-white mr-2 shadow-sm">
                        <i class="fas fa-print mr-1"></i> Cetak Laporan
                    </button>
                @endcan
            @endif

            @if (in_array($laporanKegiatan->status, [\App\Models\LaporanKegiatan::STATUS_DRAFT, \App\Models\LaporanKegiatan::STATUS_REVISI]))
                @can('update', $laporanKegiatan)
                    @if(empty($missingFieldsShow))
                        <form action="{{ route('laporan_kegiatan.ajukan', $laporanKegiatan->uuid ?? $laporanKegiatan->id) }}" method="POST" class="d-inline mr-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn bg-navy text-white btn-sm shadow-sm fw-bold">
                                <i class="fas fa-paper-plane mr-1"></i> {{ $laporanKegiatan->status === \App\Models\LaporanKegiatan::STATUS_REVISI ? 'Ajukan Revisi Sekarang' : 'Ajukan Sekarang' }}
                            </button>
                        </form>
                    @else
                        <button type="button" class="btn bg-navy text-white btn-sm shadow-sm fw-bold mr-2" style="opacity: 0.8;" onclick="Swal.fire({icon: 'error', title: 'Data Belum Lengkap!', text: 'Laporan kegiatan ini belum dapat diajukan karena ada {{ count($missingFieldsShow ?? []) }} data wajib yang belum terisi. Silakan lengkapi data terlebih dahulu.', confirmButtonText: 'Mengerti', confirmButtonColor: '#001f3f'})">
                            <i class="fas fa-paper-plane mr-1"></i> {{ $laporanKegiatan->status === \App\Models\LaporanKegiatan::STATUS_REVISI ? 'Ajukan Revisi Sekarang' : 'Ajukan Sekarang' }}
                        </button>
                    @endif
                    <a href="{{ route('laporan_kegiatan.edit', $laporanKegiatan) }}" class="btn btn-warning btn-sm mr-2 shadow-sm text-dark">
                        <i class="fas fa-edit mr-1"></i> Edit Laporan
                    </a>
                @endcan
            @endif

            @if ($laporanKegiatan->status === \App\Models\LaporanKegiatan::STATUS_DRAFT)
                @can('delete', $laporanKegiatan)
                    <form action="{{ route('laporan_kegiatan.destroy', $laporanKegiatan) }}" method="POST" class="d-inline mr-2" data-confirm-delete="true">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                @endcan
            @endif

            @php
                $backRoute = route('laporan_kegiatan.index');
                if (request('from') === 'history' || $laporanKegiatan->status === \App\Models\LaporanKegiatan::STATUS_FINAL) {
                    $backRoute = route('history_realisasi.index');
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
                        <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 0.75rem;">{{ count($missingFieldsShow ?? []) }} Data Wajib Belum Terisi</span>
                    </div>
                    <p class="text-muted mb-0 mt-1" style="font-size: 0.88rem;">
                        Lengkapi data laporan kegiatan ini agar dapat diajukan ke admin. 
                        <a href="javascript:void(0);" onclick="toggleMissingFieldsLaporan(event)" class="text-primary font-weight-bold ml-1" style="text-decoration: underline;">
                            <i class="fas fa-chevron-down mr-1" id="icon-missing-chevron-lap"></i><span id="text-missing-toggle-lap">Lihat Selengkapnya</span>
                        </a>
                    </p>
                    <div class="mt-2" id="collapseMissingFieldsLap" style="display: none;">
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

    @if(!empty($laporanKegiatan->catatan_evaluasi))
        @php
            $lapSt = $laporanKegiatan->status;
            $alertClass = match($lapSt) {
                'final' => 'alert-success',
                'revisi' => 'alert-warning',
                default => 'alert-info'
            };
            $iconClass = match($lapSt) {
                'final' => 'fa-check-circle',
                'revisi' => 'fa-exclamation-triangle',
                default => 'fa-info-circle'
            };
            $titleText = match($lapSt) {
                'revisi' => 'Catatan Revisi:',
                'final' => 'Catatan Verifikasi Laporan:',
                default => 'Catatan Evaluasi / Catatan Admin:'
            };
        @endphp
        <div class="alert {{ $alertClass }} mb-4 shadow-sm border-0">
            <i class="fas {{ $iconClass }} mr-1"></i> <strong>{{ $titleText }}</strong><br>
            <span class="d-block mt-1">{!! nl2br(e($laporanKegiatan->catatan_evaluasi)) !!}</span>
        </div>
    @endif

    <!-- Visual Progress Tracker -->
    @php
        $lapSt = $laporanKegiatan->status;
        $isDiajukan = in_array($lapSt, ['diajukan', 'revisi', 'final']);
        $isRevisi = $lapSt === 'revisi';
        $isFinal = $lapSt === 'final';
    @endphp
    <div class="card shadow-sm mb-4 border-0 rounded">
        <div class="card-body py-4">
            <div class="timeline-tracker">
                <div class="timeline-step {{ 'completed' }}">
                    <div class="timeline-icon"><i class="fas fa-pen"></i></div>
                    <div class="timeline-label">Draft Laporan</div>
                </div>
                <div class="timeline-step {{ $isDiajukan ? ($isFinal ? 'completed' : ($isRevisi ? 'rejected' : 'active')) : '' }}">
                    <div class="timeline-icon"><i class="fas {{ $isRevisi ? 'fa-exclamation-triangle' : 'fa-paper-plane' }}"></i></div>
                    <div class="timeline-label">{{ $isRevisi ? 'Perlu Revisi' : 'Menunggu Verifikasi' }}</div>
                </div>
                <div class="timeline-step {{ $isFinal ? 'completed' : '' }}">
                    <div class="timeline-icon"><i class="fas fa-check-double"></i></div>
                    <div class="timeline-label">Final / Diterima</div>
                </div>
            </div>
        </div>
    </div>

    @if(!$laporanKegiatan->isDarurat())
        <!-- CALLOUT: Konteks Rencana Kegiatan -->
        <div class="card shadow-sm mb-4 bg-white p-3" style="border: 1px solid #e2e8f0; border-radius: 8px;">
            <h6 class="text-dark fw-bold mb-3"><i class="fas fa-info-circle mr-1 text-secondary"></i> Konteks Rencana Kegiatan</h6>
            <div class="row">
                <div class="col-md-4">
                    <strong>Nama Kegiatan:</strong><br>
                    <span class="text-dark">{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</span>
                </div>
                <div class="col-md-4">
                    <strong>Waktu & Lokasi Rencana:</strong><br>
                    <span class="text-dark">
                        {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_selesai)->translatedFormat('d M Y') }}<br>
                        Di {{ $laporanKegiatan->rencanaKegiatan->desa }}
                    </span>
                </div>
                <div class="col-md-4">
                    <strong>Target Peserta:</strong><br>
                    <span class="text-dark">{{ $laporanKegiatan->rencanaKegiatan->estimasi_peserta ?? '-' }} Orang</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Grid Layout 8-4 -->
    <div class="row">
        <!-- Kolom Kiri: Informasi Evaluasi Utama -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title fw-bold text-dark"><i class="fas fa-info-circle mr-1"></i> Detail Realisasi & Evaluasi</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-primary"><i class="fas fa-calendar-check"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Tanggal Aktual</small>
                                        <strong>{{ $laporanKegiatan->getFormattedRealisasiTanggalPelaksanaan() }}</strong>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-success"><i class="fas fa-users"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Realisasi Peserta</small>
                                        <strong>{{ $laporanKegiatan->realisasi_peserta }} Orang</strong>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-warning"><i class="fas fa-map-marker-alt"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Lokasi (Desa)</small>
                                        <strong>{{ $laporanKegiatan->isDarurat() ? $laporanKegiatan->lokasi_kegiatan : ($laporanKegiatan->rencanaKegiatan->desa ?: '-') }}</strong>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="info-box-icon bg-light text-danger"><i class="fas fa-user-tie"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Penanggung Jawab</small>
                                        <strong>{{ $laporanKegiatan->isDarurat() ? ($laporanKegiatan->user->name ?? '-') : $laporanKegiatan->rencanaKegiatan->penanggung_jawab }}</strong>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Narasi Laporan (Accordion) --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex align-items-center">
                    <h3 class="card-title fw-bold text-dark mb-0"><i class="fas fa-file-alt mr-2"></i>Narasi Laporan</h3>
                    <small class="text-muted ml-2">— klik bagian untuk membuka</small>
                </div>
                <div class="card-body p-3">

                    {{-- ACCORDION untuk semua seksi konten panjang --}}
                    <div class="accordion" id="accordionLaporan">

                        {{-- Rangkaian Kegiatan --}}
                        <div class="rounded mb-2 overflow-hidden" style="border: 1px solid #e2e8f0;">
                            <div class="accordion-item-header card-header bg-white py-2 px-3" id="headRangkaian"
                                 style="cursor:pointer;" data-toggle="collapse" data-target="#collapseRangkaian"
                                 aria-expanded="false" aria-controls="collapseRangkaian">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-list-ul mr-2" style="color:#1a365d;"></i>Rangkaian Kegiatan / Alur Acara</h6>
                                    <i class="fas fa-chevron-down text-muted toggle-icon accordion-toggle-icon" style="font-size:0.75rem; transition: transform 0.3s;"></i>
                                </div>
                            </div>
                            <div id="collapseRangkaian" class="collapse" aria-labelledby="headRangkaian" data-parent="#accordionLaporan">
                                <div class="card-body text-justify" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                                    {!! $formatTextList($laporanKegiatan->rangkaian_kegiatan) !!}
                                </div>
                            </div>
                        </div>

                        {{-- Profil Peserta --}}
                        <div class="rounded mb-2 overflow-hidden" style="border: 1px solid #e2e8f0;">
                            <div class="accordion-item-header card-header bg-white py-2 px-3" id="headProfil"
                                 style="cursor:pointer;" data-toggle="collapse" data-target="#collapseProfil"
                                 aria-expanded="false" aria-controls="collapseProfil">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-users mr-2" style="color:#1a365d;"></i>Profil Peserta</h6>
                                    <i class="fas fa-chevron-down text-muted toggle-icon accordion-toggle-icon" style="font-size:0.75rem; transition: transform 0.3s;"></i>
                                </div>
                            </div>
                            <div id="collapseProfil" class="collapse" aria-labelledby="headProfil" data-parent="#accordionLaporan">
                                <div class="card-body text-justify" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                                    {!! $formatTextList($laporanKegiatan->profil_peserta) !!}
                                </div>
                            </div>
                        </div>

                        {{-- Hasil & Output --}}
                        <div class="rounded mb-2 overflow-hidden" style="border: 1px solid #e2e8f0;">
                            <div class="accordion-item-header card-header bg-white py-2 px-3" id="headHasil"
                                 style="cursor:pointer;" data-toggle="collapse" data-target="#collapseHasil"
                                 aria-expanded="false" aria-controls="collapseHasil">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-line mr-2" style="color:#1a365d;"></i>Hasil yang Dicapai & Output Nyata</h6>
                                    <i class="fas fa-chevron-down text-muted toggle-icon accordion-toggle-icon" style="font-size:0.75rem; transition: transform 0.3s;"></i>
                                </div>
                            </div>
                            <div id="collapseHasil" class="collapse" aria-labelledby="headHasil" data-parent="#accordionLaporan">
                                <div class="card-body text-justify" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                                    <div class="mb-3">
                                        <strong class="text-dark d-block mb-1">Hasil yang Dicapai:</strong>
                                        {!! $formatTextList($laporanKegiatan->hasil_dicapai) !!}
                                    </div>
                                    <hr>
                                    <div class="mb-3 mt-3">
                                        <strong class="text-dark d-block mb-1">Output Nyata:</strong>
                                        {!! $formatTextList($laporanKegiatan->output_nyata) !!}
                                    </div>
                                    <hr>
                                    <div class="mt-3">
                                        <strong class="text-dark d-block mb-1">Dampak Awal:</strong>
                                        {!! $formatTextList($laporanKegiatan->dampak_awal) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kendala, Solusi & Evaluasi --}}
                        <div class="rounded mb-0 overflow-hidden" style="border: 1px solid #e2e8f0;">
                            <div class="accordion-item-header card-header bg-white py-2 px-3" id="headKendala"
                                 style="cursor:pointer;" data-toggle="collapse" data-target="#collapseKendala"
                                 aria-expanded="false" aria-controls="collapseKendala">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-exclamation-triangle mr-2" style="color:#1a365d;"></i>Kendala, Solusi & Evaluasi</h6>
                                    <i class="fas fa-chevron-down text-muted toggle-icon accordion-toggle-icon" style="font-size:0.75rem; transition: transform 0.3s;"></i>
                                </div>
                            </div>
                            <div id="collapseKendala" class="collapse" aria-labelledby="headKendala" data-parent="#accordionLaporan">
                                <div class="card-body text-justify" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                                    <div class="mb-4">
                                        <strong class="text-dark d-block mb-2"><i class="fas fa-exclamation-triangle mr-1 text-dark"></i> Kendala yang Dihadapi:</strong>
                                        <div class="text-dark">
                                            {!! $laporanKegiatan->kendala ? $formatTextList($laporanKegiatan->kendala) : '<em class="text-dark">Tidak ada kendala.</em>' !!}
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <strong class="text-dark d-block mb-2"><i class="fas fa-check-circle mr-1 text-dark"></i> Solusi yang Dilakukan:</strong>
                                        <div class="text-dark">
                                            {!! $laporanKegiatan->solusi ? $formatTextList($laporanKegiatan->solusi) : '<em class="text-dark">Tidak ada solusi yang dicatat.</em>' !!}
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark d-block mb-2"><i class="fas fa-lightbulb mr-1 text-dark"></i> Evaluasi & Rekomendasi:</strong>
                                        <div class="text-dark">
                                            {!! $laporanKegiatan->evaluasi_rekomendasi ? $formatTextList($laporanKegiatan->evaluasi_rekomendasi) : '<em class="text-dark">Tidak ada evaluasi.</em>' !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- End accordion --}}
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Dokumen & Foto -->
        <div class="col-md-4">

            <!-- Foto Kegiatan -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-2 px-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-images mr-1"></i> Foto Kegiatan</h6>
                </div>
                <div class="card-body p-2">
                    @if (!empty($laporanKegiatan->foto_kegiatan))
                        <div class="row">
                            @foreach ($laporanKegiatan->foto_kegiatan as $foto)
                                @php
                                    $filePath = is_array($foto) ? $foto['path'] : $foto;
                                @endphp
                                <div class="col-6 mb-2 px-2">
                                    <a href="/public/storage/app/{{ $filePath }}" target="_blank">
                                        <img src="/public/storage/app/{{ $filePath }}" class="gallery-img border shadow-sm" style="height: 120px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-3">Tidak ada foto kegiatan</div>
                    @endif
                </div>
            </div>

            <!-- Dokumen Lampiran -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-2 px-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-file-alt mr-1"></i> Dokumen Lampiran</h6>
                </div>
                <div class="card-body py-2 px-3">
                    @php
                        $renderFileItem = function($files, $title, $iconClass) {
                            if(empty($files)) return '';
                            $html = "<div class='text-muted font-weight-bold mb-1' style='font-size:0.75rem;'>{$title}</div>";
                            foreach($files as $file) {
                                $path = is_array($file) ? $file['path'] : $file;
                                $name = is_array($file) ? $file['original_name'] : basename($file);
                                $url = asset('public/storage/app/' . $path);
                                $html .= "<a href='{$url}' target='_blank' class='btn btn-sm bg-navy text-white btn-block text-left mb-2 text-truncate' title='{$name}' style='font-size:0.78rem;'><i class='{$iconClass} mr-1'></i> {$name}</a>";
                            }
                            return $html;
                        };

                        $hasFiles = !empty($laporanKegiatan->daftar_hadir) || !empty($laporanKegiatan->notulen) || !empty($laporanKegiatan->materi) || !empty($laporanKegiatan->berita_acara);
                    @endphp

                    @if($hasFiles)
                        {!! $renderFileItem($laporanKegiatan->daftar_hadir, 'Daftar Hadir', 'fas fa-file-pdf') !!}
                        {!! $renderFileItem($laporanKegiatan->notulen, 'Notulen', 'fas fa-file-word') !!}
                        {!! $renderFileItem($laporanKegiatan->materi, 'Materi', 'fas fa-file-powerpoint') !!}
                        {!! $renderFileItem($laporanKegiatan->berita_acara, 'Berita Acara', 'fas fa-file-pdf') !!}
                    @else
                        <div class="text-center text-muted py-3">Belum ada dokumen yang dilampirkan</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Minta Revisi -->
@if ($laporanKegiatan->status === \App\Models\LaporanKegiatan::STATUS_DIAJUKAN && auth()->user()->role->role_name === 'admin')
<div class="modal fade" id="modal-revisi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('laporan_kegiatan.revisi', $laporanKegiatan->uuid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit mr-2 text-navy"></i>Minta Revisi Laporan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea name="catatan_evaluasi" class="form-control" rows="4" required placeholder="Tuliskan catatan revisi untuk pelaksana..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary text-white" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Batal</button>
                    <button type="submit" class="btn bg-navy text-white font-weight-bold"><i class="fas fa-paper-plane mr-1"></i> Kirim Permintaan Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Accordion chevron rotation + active state
    document.addEventListener('DOMContentLoaded', function () {
        var collapseEls = document.querySelectorAll('#accordionLaporan .collapse');
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

    @push('scripts')
        <script>
            function toggleMissingFieldsLaporan(event) {
                if (event) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                var detailEl = document.getElementById('collapseMissingFieldsLap');
                var chevron = document.getElementById('icon-missing-chevron-lap');
                var textEl = document.getElementById('text-missing-toggle-lap');

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

            function printLaporan(url) {
                // Membuka URL print di iframe tersembunyi
                let iframe = document.createElement('iframe');
                iframe.style.position = 'absolute';
                iframe.style.width = '0px';
                iframe.style.height = '0px';
                iframe.style.border = 'none';
                iframe.src = url;

                document.body.appendChild(iframe);

                // Memastikan iframe sudah ter-load sebelum memicu print
                iframe.onload = function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();

                    // Menghapus iframe setelah beberapa saat (memberi waktu jendela print tertutup)
                    setTimeout(function() {
                        document.body.removeChild(iframe);
                    }, 5000);
                };
            }
        </script>
    @endpush
@endsection
