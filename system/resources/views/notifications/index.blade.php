@extends('layouts.adminlte')

@section('content_title', 'Notifikasi')

@section('content')
    <div class="container-fluid pb-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-lg">
                    <!-- Header Card -->
                    <div class="card-header bg-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center" style="border-bottom: 1px solid #edf2f7;">
                        <h5 class="card-title font-weight-bold text-dark mb-2 mb-md-0 d-flex align-items-center">
                            <i class="fas fa-bell text-navy mr-2 style-icon"></i> Pusat Notifikasi
                        </h5>

                        <div class="d-flex align-items-center flex-wrap ml-auto" style="gap: 8px;">
                            @if ($unreadCount > 0)
                                <form action="{{ route('notifications.readAll') }}" method="POST" class="mb-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm bg-navy text-white shadow-sm rounded px-3 font-weight-bold">
                                        <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
                                    </button>
                                </form>
                            @endif

                            @if ($totalCount > 0)
                                <form action="{{ route('notifications.deleteAll') }}" method="POST" class="mb-0" data-confirm-delete="true">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger text-white shadow-sm rounded px-3 font-weight-bold border-0">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus Semua Notifikasi
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="card-body bg-light py-2 px-4 border-bottom">
                        <ul class="nav nav-pills custom-nav-pills" style="gap: 6px;">
                            <li class="nav-item">
                                <a href="{{ route('notifications.index', ['filter' => 'all']) }}" 
                                   class="nav-link px-3 py-1 font-weight-bold {{ $filter === 'all' ? 'active bg-navy text-white' : 'text-dark bg-white border' }}" style="border-radius: 20px; font-size: 0.85rem;">
                                    <i class="fas fa-inbox mr-1 {{ $filter === 'all' ? 'text-white' : 'text-navy' }}"></i> Semua <span class="badge badge-pill bg-secondary text-white ml-1" style="background-color: #6c757d !important;">{{ $totalCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                                   class="nav-link px-3 py-1 font-weight-bold {{ $filter === 'unread' ? 'active bg-navy text-white' : 'text-dark bg-white border' }}" style="border-radius: 20px; font-size: 0.85rem;">
                                    <i class="fas fa-envelope mr-1 {{ $filter === 'unread' ? 'text-white' : 'text-navy' }}"></i> Belum Dibaca <span class="badge badge-pill bg-secondary text-white ml-1" style="background-color: #6c757d !important;">{{ $unreadCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                                   class="nav-link px-3 py-1 font-weight-bold {{ $filter === 'read' ? 'active bg-navy text-white' : 'text-dark bg-white border' }}" style="border-radius: 20px; font-size: 0.85rem;">
                                    <i class="fas fa-envelope-open mr-1 {{ $filter === 'read' ? 'text-white' : 'text-navy' }}"></i> Sudah Dibaca <span class="badge badge-pill bg-secondary text-white ml-1" style="background-color: #6c757d !important;">{{ $readCount }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Notification Body -->
                    <div class="card-body p-3 p-md-4">
                        @if ($notifications->count() > 0)
                            <div class="d-flex flex-column" style="gap: 12px;">
                                @foreach($notifications as $notification)
                                    @php
                                        $data = $notification->data;
                                        $isUnread = is_null($notification->read_at);
                                        $kegiatanUuid = $data['id_kegiatan'] ?? null;
                                        $laporanUuid = $data['id_laporan'] ?? null;
                                        $notificationType = $data['type'] ?? null;
                                        $message = $data['message'] ?? 'Notifikasi Kegiatan';
                                        $keterangan = $data['keterangan'] ?? null;

                                        // Determinasi tipe & warna badge berbasis pesan
                                        $msgLower = strtolower($message);
                                        $badgeBg = '#001f3f';
                                        $badgeColor = '#ffffff';
                                        $badgeText = 'Notifikasi';
                                        $iconClass = 'fa-bell';
                                        $iconBg = '#e2e8f0';
                                        $iconColor = '#001f3f';

                                        if (str_contains($msgLower, 'revisi')) {
                                            $badgeBg = '#fff3cd';
                                            $badgeColor = '#856404';
                                            $badgeText = 'Perlu Revisi';
                                            $iconClass = 'fa-exclamation-triangle';
                                            $iconBg = '#fef3c7';
                                            $iconColor = '#d97706';
                                        } elseif (str_contains($msgLower, 'disetujui')) {
                                            $badgeBg = '#def7ec';
                                            $badgeColor = '#03543f';
                                            $badgeText = 'Disetujui';
                                            $iconClass = 'fa-check-circle';
                                            $iconBg = '#d1fae5';
                                            $iconColor = '#059669';
                                        } elseif (str_contains($msgLower, 'diajukan')) {
                                            $badgeBg = '#e8f0fe';
                                            $badgeColor = '#1a56db';
                                            $badgeText = 'Diajukan';
                                            $iconClass = 'fa-paper-plane';
                                            $iconBg = '#dbeafe';
                                            $iconColor = '#2563eb';
                                        } elseif (str_contains($msgLower, 'ditolak')) {
                                            $badgeBg = '#fde8e8';
                                            $badgeColor = '#9b1c1c';
                                            $badgeText = 'Ditolak';
                                            $iconClass = 'fa-times-circle';
                                            $iconBg = '#fee2e2';
                                            $iconColor = '#dc2626';
                                        } elseif (str_contains($msgLower, 'selesai') || str_contains($msgLower, 'final')) {
                                            $badgeBg = '#e6f4ea';
                                            $badgeColor = '#137333';
                                            $badgeText = 'Selesai';
                                            $iconClass = 'fa-check-double';
                                            $iconBg = '#d1fae5';
                                            $iconColor = '#059669';
                                        }

                                        // Waktu relatif indonesia
                                        $diffForHumans = $notification->created_at->diffForHumans();
                                        $indonesianTime = str_replace([
                                            ' seconds ago', ' second ago',
                                            ' minutes ago', ' minute ago', 
                                            ' hours ago', ' hour ago',
                                            ' days ago', ' day ago',
                                            ' weeks ago', ' week ago',
                                            ' months ago', ' month ago',
                                            ' years ago', ' year ago',
                                        ], [
                                            ' detik lalu', ' detik lalu',
                                            ' menit lalu', ' menit lalu',
                                            ' jam lalu', ' jam lalu',
                                            ' hari lalu', ' hari lalu',
                                            ' minggu lalu', ' minggu lalu',
                                            ' bulan lalu', ' bulan lalu',
                                            ' tahun lalu', ' tahun lalu',
                                        ], $diffForHumans);
                                    @endphp

                                    <div class="card border-0 shadow-sm p-3 rounded position-relative transition-all" 
                                         style="background-color: {{ $isUnread ? '#f8fafc' : '#ffffff' }}; border-left: 4px solid {{ $isUnread ? '#001f3f' : '#cbd5e1' }} !important; border: 1px solid #e2e8f0;">
                                        <div class="d-flex align-items-start">
                                            <!-- Icon Wrapper -->
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 mt-1 shadow-sm" 
                                                 style="width: 40px; height: 40px; background-color: {{ $iconBg }}; color: {{ $iconColor }}; flex-shrink: 0;">
                                                <i class="fas {{ $iconClass }}" style="font-size: 1.1rem;"></i>
                                            </div>

                                            <!-- Content Wrapper -->
                                            <div class="flex-grow-1">
                                                <!-- Header Row: Badges di Kiri, Waktu di Kanan Atas -->
                                                <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">
                                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                                        @if($isUnread)
                                                            <span class="badge badge-danger font-weight-bold px-2 py-1" style="font-size: 0.72rem; border-radius: 4px;">Baru</span>
                                                        @endif
                                                        <span class="badge font-weight-bold px-2 py-1" style="background-color: {{ $badgeBg }}; color: {{ $badgeColor }}; font-size: 0.72rem; border-radius: 4px;">
                                                            {{ $badgeText }}
                                                        </span>
                                                    </div>
                                                    <small class="text-muted d-flex align-items-center ml-auto" style="font-size: 0.8rem;">
                                                        <i class="far fa-clock mr-1"></i> {{ $indonesianTime }} ({{ $notification->created_at->translatedFormat('d M Y H:i') }})
                                                    </small>
                                                </div>

                                                <!-- Message Body -->
                                                <h6 class="font-weight-bold text-dark mt-2 mb-1" style="font-size: 0.95rem; line-height: 1.4;">
                                                    {{ $message }}
                                                </h6>

                                                @if(!empty($keterangan))
                                                    <div class="p-2 rounded bg-white border mt-2 text-muted" style="font-size: 0.88rem; border-color: #e2e8f0 !important;">
                                                        <i class="fas fa-info-circle text-navy mr-1"></i> {{ $keterangan }}
                                                    </div>
                                                @endif

                                                <!-- Footer Row: Tombol Lihat Detail di Kanan Bawah -->
                                                <div class="d-flex justify-content-end mt-3">
                                                    <a href="{{ route('notifications.read', $notification->id) }}" 
                                                       class="btn btn-sm bg-navy text-white font-weight-bold shadow-sm rounded-pill px-3 py-1 text-nowrap">
                                                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                                    <i class="fas fa-bell-slash fa-2x text-muted"></i>
                                </div>
                                <h5 class="text-dark font-weight-bold">Tidak Ada Notifikasi</h5>
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    @if($filter === 'unread')
                                        Tidak ada notifikasi yang belum dibaca.
                                    @elseif($filter === 'read')
                                        Belum ada notifikasi yang sudah dibaca.
                                    @else
                                        Belum ada notifikasi untuk ditampilkan saat ini.
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .style-icon {
            font-size: 1.1rem;
        }
        .custom-nav-pills .nav-link {
            transition: all 0.2s ease-in-out;
        }
        .custom-nav-pills .nav-link:hover:not(.active) {
            background-color: #e2e8f0 !important;
        }
        .transition-all {
            transition: all 0.2s ease-in-out;
        }
        .transition-all:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
            transform: translateY(-1px);
        }
    </style>
@endpush
