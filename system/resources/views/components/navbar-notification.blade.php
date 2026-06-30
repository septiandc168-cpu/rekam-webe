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
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width: 280px;">
        <span class="dropdown-item dropdown-header">{{ $unreadCount }} Notifikasi Baru</span>

        <div class="dropdown-divider"></div>

        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $isUnread = is_null($notification->read_at);
                $kegiatanUuid = $data['id_kegiatan'] ?? null;
                $laporanUuid = $data['id_laporan'] ?? null;
                $notificationType = $data['type'] ?? null;
                $hasValidLink = ($notificationType === 'laporan_kegiatan' && $laporanUuid) || ($kegiatanUuid && $notificationType !== 'laporan_kegiatan');
            @endphp

            <a href="{{ $hasValidLink ? route('notifications.read', $notification->id) : '#' }}"
                class="dropdown-item {{ $isUnread ? 'notification-unread' : '' }}"
                style="{{ $isUnread ? 'background-color: #f8f9fa; border-left: 3px solid #007bff;' : '' }} padding: 12px 16px;">

                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-sm mb-1" style="margin: 0; font-weight: {{ $isUnread ? '600' : '400' }};">
                            {{ $data['message'] ?? 'Notifikasi' }}
                        </p>
                        @if ($data['keterangan'])
                            <p class="text-xs text-muted mb-1" style="margin: 0;">
                                {{ Str::limit($data['keterangan'], 50) }}
                            </p>
                        @endif
                        <p class="text-xs text-muted" style="margin: 0;">
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
                        </p>
                    </div>
                    @if ($isUnread)
                        <div class="ml-2">
                            <span class="badge badge-primary">Baru</span>
                        </div>
                    @endif
                </div>
            </a>

            <div class="dropdown-divider"></div>
        @empty
            <a href="#" class="dropdown-item">
                <span class="text-muted text-center d-block">Tidak ada notifikasi</span>
            </a>
            <div class="dropdown-divider"></div>
        @endforelse

        <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">
            Lihat Semua Notifikasi
        </a>

        @if ($unreadCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="dropdown-item dropdown-footer text-center"
                    style="border: none; background: none; width: 100%; text-align: center; cursor: pointer;">
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>
</li>

<style>
    .notification-unread:hover {
        background-color: #e9ecef !important;
    }

    .dropdown-menu-lg {
        max-height: 400px;
        overflow-y: auto;
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

    .dropdown-item {
        white-space: normal;
        word-wrap: break-word;
    }

    .dropdown-footer {
        text-align: center;
        font-weight: 500;
        color: #007bff !important;
    }

    .dropdown-footer:hover {
        background-color: #f8f9fa !important;
        color: #0056b3 !important;
    }
</style>
