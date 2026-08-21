@php
    $user = auth()->user();
    $unreadCount = $user->unreadNotifications()->count();
    $notifications = $user->notifications()->orderBy('created_at', 'desc')->take(5)->get();
@endphp

<!-- Notifications Dropdown -->
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" id="notificationDropdown">
        <i class="far fa-bell"></i>
        @if ($unreadCount > 0)
            <span class="badge badge-warning navbar-badge">{{ $unreadCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 shadow-lg border-0" style="min-width: 310px;">
        <!-- Header Sticky -->
        <div class="px-3 py-2 bg-light border-bottom text-center">
            <span class="font-weight-bold text-dark text-sm">{{ $unreadCount }} Notifikasi Baru</span>
        </div>

        <!-- Scrollable Notifications Body -->
        <div class="notification-scroll-body" style="max-height: 280px; overflow-y: auto;">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                    $kegiatanUuid = $data['id_kegiatan'] ?? null;
                    $laporanUuid = $data['id_laporan'] ?? null;
                    $notificationType = $data['type'] ?? null;
                    $hasValidLink = !empty($laporanUuid) || !empty($kegiatanUuid);
                @endphp

                <a href="{{ $hasValidLink ? route('notifications.read', $notification->id) : '#' }}"
                    class="dropdown-item border-bottom {{ $isUnread ? 'notification-unread' : '' }}"
                    style="{{ $isUnread ? 'background-color: #f8f9fa; border-left: 3px solid #001f3f !important;' : '' }} padding: 10px 14px; white-space: normal; word-wrap: break-word;">

                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1 mr-2">
                            <p class="text-sm mb-1 text-dark" style="margin: 0; font-weight: {{ $isUnread ? '600' : '400' }}; line-height: 1.35;">
                                {{ $data['message'] ?? 'Notifikasi' }}
                            </p>
                            @if (!empty($data['keterangan']))
                                <p class="text-xs text-muted mb-1" style="margin: 0; line-height: 1.3;">
                                    {{ Str::limit($data['keterangan'], 55) }}
                                </p>
                            @endif
                            <p class="text-xs text-muted" style="margin: 0; font-size: 0.72rem;">
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
                                <i class="far fa-clock mr-1"></i>{{ $indonesianTime }}
                            </p>
                        </div>
                        @if ($isUnread)
                            <div>
                                <span class="badge bg-navy text-white px-2 py-1" style="font-size: 0.65rem;">Baru</span>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-3 py-4 text-center text-muted">
                    <i class="far fa-bell-slash d-block mb-2" style="font-size: 1.5rem; opacity: 0.5;"></i>
                    <span class="text-sm">Tidak ada notifikasi</span>
                </div>
            @endforelse
        </div>

        <!-- Footer Sticky -->
        <div class="border-top bg-light p-2 text-center">
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-link text-navy font-weight-bold p-1 d-block mb-1" style="text-decoration: none; font-size: 0.82rem;">
                <i class="fas fa-list-ul mr-1"></i> Lihat Semua Notifikasi
            </a>

            @if ($unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-secondary p-0 border-0"
                        style="text-decoration: none; font-size: 0.78rem; cursor: pointer;">
                        <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>
</li>

<style>
    .notification-scroll-body::-webkit-scrollbar {
        width: 5px;
    }
    .notification-scroll-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .notification-scroll-body::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
    .notification-scroll-body::-webkit-scrollbar-thumb:hover {
        background: #001f3f;
    }
    .notification-unread:hover {
        background-color: #e9ecef !important;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .navbar-badge {
        position: absolute;
        top: 0px;
        right: 0px;
        font-size: 0.6rem;
        padding: 2px 5px;
        border-radius: 10px;
    }
    .dropdown-item:active,
    .dropdown-item:focus,
    .dropdown-item.active {
        background-color: #001f3f !important;
        color: #ffffff !important;
    }
</style>
