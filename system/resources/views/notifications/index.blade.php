@extends('layouts.adminlte')

@section('content_title', 'Notifikasi')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm elevation-1">
                <div class="card-header bg-white py-3 px-4">
                    <h3 class="card-title font-weight-bold text-dark mt-1">
                        <i class="fas fa-bell text-navy mr-2"></i> Daftar Notifikasi
                    </h3>

                    <div class="card-tools d-flex align-items-center">
                        @if (auth()->user()->unreadNotifications()->count() > 0)
                            <form action="{{ route('notifications.readAll') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-sm bg-navy text-white shadow-sm rounded-pill px-3"
                                     title="Tandai Semua Dibaca">
                                    <i class="fas fa-check-double"></i>
                                    <span class="d-none d-sm-inline ml-1"> Tandai Semua Dibaca</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if ($notifications->count() > 0)
                        <div class="timeline timeline-inverse">
                            @forelse($notifications as $notification)
                            @php
                                $data = $notification->data;
                                $isUnread = is_null($notification->read_at);
                                $kegiatanUuid = $data['id_kegiatan'] ?? null;
                                $laporanUuid = $data['id_laporan'] ?? null;
                                $notificationType = $data['type'] ?? null;
                                $hasValidLink = !empty($laporanUuid) || !empty($kegiatanUuid);
                            @endphp

                            <div class="time-label">
                                <span class="bg-{{ $isUnread ? 'warning' : 'secondary' }}">
                                    {{ $notification->created_at->translatedFormat('d M Y H:i') }}
                                </span>
                            </div>

                            <div>
                                <i class="fas fa-bell {{ $isUnread ? 'bg-navy text-white' : 'bg-secondary text-white' }} shadow-sm"></i>

                                <div class="timeline-item shadow-sm border-0 border-left-{{ $isUnread ? 'navy' : 'secondary' }}">
                                    <h3 class="timeline-header border-0 font-weight-bold text-dark pt-3 pb-1">
                                        @if($isUnread)
                                            <span class="badge badge-danger mr-1 badge-pill px-2">Baru</span>
                                        @endif
                                        {{ $data['message'] ?? 'Notifikasi' }}
                                    </h3>

                                    @if(!empty($data['keterangan']))
                                        <div class="timeline-body bg-light rounded px-3 py-2 text-muted mx-3 mb-2">
                                            <i class="fas fa-info-circle mr-1 text-navy"></i> {{ $data['keterangan'] }}
                                        </div>
                                    @endif

                                    <div class="timeline-footer">
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            @php
                                                $diffForHumans = $notification->created_at->diffForHumans();
                                                $indonesianTime = str_replace([
                                                    ' seconds ago', ' second ago',
                                                    ' minutes ago', ' minute ago', 
                                                    ' hours ago', ' hour ago',
                                                    ' days ago', ' day ago',
                                                    ' weeks ago', ' week ago',
                                                    ' months ago', ' month ago',
                                                    ' years ago', ' year ago',
                                                    ' seconds from now', ' second from now',
                                                    ' minutes from now', ' minute from now',
                                                    ' hours from now', ' hour from now',
                                                    ' days from now', ' day from now',
                                                    ' weeks from now', ' week from now',
                                                    ' months from now', ' month from now',
                                                    ' years from now', ' year from now'
                                                ], [
                                                    ' detik yang lalu', ' detik yang lalu',
                                                    ' menit yang lalu', ' menit yang lalu',
                                                    ' jam yang lalu', ' jam yang lalu',
                                                    ' hari yang lalu', ' hari yang lalu',
                                                    ' minggu yang lalu', ' minggu yang lalu',
                                                    ' bulan yang lalu', ' bulan yang lalu',
                                                    ' tahun yang lalu', ' tahun yang lalu',
                                                    ' detik dari sekarang', ' detik dari sekarang',
                                                    ' menit dari sekarang', ' menit dari sekarang',
                                                    ' jam dari sekarang', ' jam dari sekarang',
                                                    ' hari dari sekarang', ' hari dari sekarang',
                                                    ' minggu dari sekarang', ' minggu dari sekarang',
                                                    ' bulan dari sekarang', ' bulan dari sekarang',
                                                    ' tahun dari sekarang', ' tahun dari sekarang'
                                                ], $diffForHumans);
                                            @endphp
                                            {{ $indonesianTime }}
                                        </small>

                                        @if($isUnread && $hasValidLink)
                                            <a href="{{ route('notifications.read', $notification->id) }}" 
                                               class="btn btn-sm bg-navy text-white shadow-sm rounded-pill px-3 mt-2 mt-md-0">
                                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                                            </a>
                                        @elseif($notificationType === 'laporan_kegiatan' && $laporanUuid)
                                            <a href="{{ route('laporan_kegiatan.show', ['laporan_kegiatan' => $laporanUuid]) }}" 
                                               class="btn btn-sm bg-navy text-white shadow-sm rounded-pill px-3 mt-2 mt-md-0">
                                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                                            </a>
                                        @elseif($kegiatanUuid)
                                            <a href="{{ route('rencana_kegiatan.show', ['rencana_kegiatan' => $kegiatanUuid]) }}" 
                                               class="btn btn-sm bg-navy text-white shadow-sm rounded-pill px-3 mt-2 mt-md-0">
                                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Tidak Ada Notifikasi</h4>
                            <p class="text-muted">Belum ada notifikasi untuk ditampilkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline-inverse {
            color: #000;
        }

        .timeline-item {
            background: #f8f9fa;
            border-left: 3px solid #adb5bd;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 0 5px 5px 0;
        }

        .border-left-blue, .border-left-navy {
            border-left-color: #001f3f !important;
        }

        .text-navy {
            color: #001f3f !important;
        }

        .bg-navy {
            background-color: #001f3f !important;
            color: #ffffff !important;
        }

        .timeline-header {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .timeline-body {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }

        .timeline-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .time-label span {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 20px;
        }

        .bg-blue {
            background-color: #007bff !important;
        }

        .bg-gray {
            background-color: #6c757d !important;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
            font-size: 10px;
            padding: 3px 6px;
        }
    </style>
@endpush
