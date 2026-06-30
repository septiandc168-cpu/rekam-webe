@extends('layouts.adminlte')
@section('content_title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Alert Status -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-navy">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h2 class="text-white mb-2">Selamat Datang di Rekam WeBe</h2>
                                <p class="text-white">Halo, <strong
                                        class="text-white">{{ ucwords(auth()->user()->name) }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Total User - Hanya untuk Supervisor -->
            @if (isset(auth()->user()->role->role_name) && auth()->user()->role->role_name === 'supervisor')
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ \App\Models\User::count() }}</h3>
                            <p>Total User</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Total Rencana Kegiatan -->
            <div class="@if(isset(auth()->user()->role->role_name) && auth()->user()->role->role_name === 'supervisor') col-lg-4 @else col-lg-6 @endif col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        @php
                            $user = auth()->user();
                            if ($user->role->role_name === 'supervisor') {
                                $totalRencana = \App\Models\RencanaKegiatan::count();
                            } else {
                                $totalRencana = \App\Models\RencanaKegiatan::where('user_id', $user->id)->count();
                            }
                        @endphp
                        <h3>{{ $totalRencana }}</h3>
                        <p>Total Rencana Kegiatan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <a href="{{ route('rencana_kegiatan.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Laporan Kegiatan -->
            @if (isset(auth()->user()->role->role_name) && in_array(auth()->user()->role->role_name, ['admin', 'supervisor']))
                <div class="@if(isset(auth()->user()->role->role_name) && auth()->user()->role->role_name === 'supervisor') col-lg-4 @else col-lg-6 @endif col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            @php
                                $user = auth()->user();
                                if ($user->role->role_name === 'supervisor') {
                                    $totalLaporan = \App\Models\LaporanKegiatan::count();
                                } else {
                                    $totalLaporan = \App\Models\LaporanKegiatan::where('user_id', $user->id)->count();
                                }
                            @endphp
                            <h3>{{ $totalLaporan }}</h3>
                            <p>Total Laporan Kegiatan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a href="{{ route('laporan_kegiatan.index') }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @else
                <!-- Placeholder untuk non-admin/supervisor -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>-</h3>
                            <p>Laporan Kegiatan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Terbatas <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Calendar Widget -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-navy">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Kalender Kegiatan
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool btn-sm" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="calendar" style="min-height: 450px;"></div>
                    </div>
                    <!-- Legend Warna Status -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <!-- Desktop: 1 baris, Mobile: 2 baris -->
                                <div class="d-none d-lg-flex flex-wrap gap-3 justify-content-center">
                                    <div class="d-flex align-items-center mr-3">
                                        <span class="badge mr-2" style="background-color: #007bff; width: 20px; height: 20px; display: inline-block;"></span>
                                        <span class="text-sm">Diajukan</span>
                                    </div>
                                    <div class="d-flex align-items-center mr-3">
                                        <span class="badge mr-2" style="background-color: #28a745; width: 20px; height: 20px; display: inline-block;"></span>
                                        <span class="text-sm">Disetujui</span>
                                    </div>
                                    <div class="d-flex align-items-center mr-3">
                                        <span class="badge mr-2" style="background-color: #ffc107; width: 20px; height: 20px; display: inline-block;"></span>
                                        <span class="text-sm">Revisi</span>
                                    </div>
                                    <div class="d-flex align-items-center mr-3">
                                        <span class="badge mr-2" style="background-color: #dc3545; width: 20px; height: 20px; display: inline-block;"></span>
                                        <span class="text-sm">Ditolak</span>
                                    </div>
                                    <div class="d-flex align-items-center mr-3">
                                        <span class="badge mr-2" style="background-color: #6c757d; width: 20px; height: 20px; display: inline-block;"></span>
                                        <span class="text-sm">Selesai</span>
                                    </div>
                                </div>
                                
                                <!-- Mobile: 2 baris -->
                                <div class="d-lg-none">
                                    <!-- Grid 2x2 untuk mobile -->
                                    <div class="row">
                                        <!-- Baris 1 -->
                                        <div class="col-6 d-flex align-items-center justify-content-start mb-2 pl-3">
                                            <span class="badge mr-2" style="background-color: #007bff; width: 20px; height: 20px; display: inline-block;"></span>
                                            <span class="text-sm">Diajukan</span>
                                        </div>
                                        <div class="col-6 d-flex align-items-center justify-content-start pl-3">
                                            <span class="badge mr-2" style="background-color: #28a745; width: 20px; height: 20px; display: inline-block;"></span>
                                            <span class="text-sm">Disetujui</span>
                                        </div>
                                        <!-- Baris 2 -->
                                        <div class="col-6 d-flex align-items-center justify-content-start pl-3 mb-2">
                                            <span class="badge mr-2" style="background-color: #dc3545; width: 20px; height: 20px; display: inline-block;"></span>
                                            <span class="text-sm">Ditolak</span>
                                        </div>
                                        <div class="col-6 d-flex align-items-center justify-content-start pl-3">
                                            <span class="badge mr-2" style="background-color: #6c757d; width: 20px; height: 20px; display: inline-block;"></span>
                                            <span class="text-sm">Selesai</span>
                                        </div>
                                        <div class="col-6 d-flex align-items-center justify-content-start pl-3">
                                            <span class="badge mr-2" style="background-color: #ffc107; width: 20px; height: 20px; display: inline-block;"></span>
                                            <span class="text-sm">Revisi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <!-- Rencana Kegiatan Terbaru -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-navy">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Rencana Kegiatan Terbaru
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('rencana_kegiatan.index') }}?filter_status=all" class="btn btn-tool btn-sm" onclick="setFilterStatus('all')">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Kegiatan</th>
                                        @if(auth()->user()->role->role_name === 'supervisor')
                                            <th>Penanggung Jawab</th>
                                        @else
                                            <th>Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $user = auth()->user();
                                    if ($user->role->role_name === 'supervisor') {
                                        $rencanaTerbaru = \App\Models\RencanaKegiatan::latest()->take(5)->get();
                                    } else {
                                        $rencanaTerbaru = \App\Models\RencanaKegiatan::where('user_id', $user->id)
                                            ->latest()
                                            ->take(5)
                                            ->get();
                                    }
                                @endphp
                                    @forelse($rencanaTerbaru as $rencana)
                                        <tr onclick="window.location.href='{{ route('rencana_kegiatan.show', $rencana->uuid) }}'" style="cursor: pointer;">
                                            <td>{{ \Carbon\Carbon::parse($rencana->tanggal_mulai)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ Str::limit($rencana->nama_kegiatan, 30) }}
                                            </td>
                                            @if(auth()->user()->role->role_name === 'supervisor')
                                                <td>
                                                    {{ Str::limit($rencana->penanggung_jawab, 30) }}
                                                </td>
                                            @else
                                                <td>
                                                    @switch($rencana->status)
                                                        @case('diajukan')
                                                            <span class="badge badge-primary">Diajukan</span>
                                                            @break

                                                        @case('disetujui')
                                                            <span class="badge badge-success">Disetujui</span>
                                                            @break

                                                        @case('revisi')
                                                            <span class="badge badge-warning text-dark">Revisi</span>
                                                            @break

                                                        @case('ditolak')
                                                            <span class="badge badge-danger">Ditolak</span>
                                                            @break

                                                        @case('selesai')
                                                            <span class="badge badge-secondary">Selesai</span>
                                                            @break

                                                        @default
                                                            <span class="badge badge-secondary">{{ $rencana->status }}</span>
                                                    @endswitch
                                                </td>
                                            @endif
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->role->role_name === 'supervisor' ? '3' : '3' }}" class="text-center text-muted">Belum ada data rencana
                                                    kegiatan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rencana Disetujui Table -->
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-navy">
                            <h3 class="card-title">
                                <i class="fas fa-check-circle mr-2"></i>
                                Rencana Kegiatan Disetujui
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('rencana_kegiatan.index') }}?filter_status=disetujui" class="btn btn-tool btn-sm" onclick="setFilterStatus('disetujui')">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Kegiatan</th>
                                            @if(auth()->user()->role->role_name === 'supervisor')
                                                <th>Penanggung Jawab</th>
                                            @else
                                                <th>Status</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $user = auth()->user();
                                        if ($user->role->role_name === 'supervisor') {
                                            $rencanaDisetujui = \App\Models\RencanaKegiatan::where('status', 'disetujui')
                                                ->latest()
                                                ->take(10)
                                                ->get();
                                        } else {
                                            $rencanaDisetujui = \App\Models\RencanaKegiatan::where('user_id', $user->id)
                                                ->where('status', 'disetujui')
                                                ->latest()
                                                ->take(10)
                                                ->get();
                                        }
                                    @endphp
                                        @forelse($rencanaDisetujui as $rencana)
                                            <tr onclick="window.location.href='{{ route('rencana_kegiatan.show', $rencana->uuid) }}'" style="cursor: pointer;">
                                                <td>{{ \Carbon\Carbon::parse($rencana->tanggal_mulai)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ Str::limit($rencana->nama_kegiatan, 30) }}
                                                </td>
                                                @if(auth()->user()->role->role_name === 'supervisor')
                                                    <td>
                                                        {{ Str::limit($rencana->penanggung_jawab, 30) }}
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="badge badge-success">Disetujui</span>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->role->role_name === 'supervisor' ? '3' : '3' }}" class="text-center text-muted">
                                                    Belum ada rencana kegiatan yang disetujui
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kegiatan Selesai Terbaru -->
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-navy">
                            <h3 class="card-title">
                                <i class="fas fa-check-double mr-2"></i>
                                Kegiatan Selesai Terbaru
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('rencana_kegiatan.index') }}?filter_status=selesai" class="btn btn-tool btn-sm" onclick="setFilterStatus('selesai')">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Kegiatan</th>
                                            @if(auth()->user()->role->role_name === 'supervisor')
                                                <th>Penanggung Jawab</th>
                                            @else
                                                <th>Status</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $user = auth()->user();
                                            if ($user->role->role_name === 'supervisor') {
                                                $kegiatanSelesai = \App\Models\RencanaKegiatan::where('status', 'selesai')
                                                    ->latest()
                                                    ->take(5)
                                                    ->get();
                                            } else {
                                                $kegiatanSelesai = \App\Models\RencanaKegiatan::where('user_id', $user->id)
                                                    ->where('status', 'selesai')
                                                    ->latest()
                                                    ->take(5)
                                                    ->get();
                                            }
                                        @endphp
                                        @forelse($kegiatanSelesai as $kegiatan)
                                            <tr onclick="window.location.href='{{ route('rencana_kegiatan.show', $kegiatan->uuid) }}'" style="cursor: pointer;">
                                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ Str::limit($kegiatan->nama_kegiatan, 30) }}
                                                </td>
                                                @if(auth()->user()->role->role_name === 'supervisor')
                                                    <td>
                                                        {{ Str::limit($kegiatan->penanggung_jawab, 30) }}
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="badge badge-secondary">Selesai</span>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->role->role_name === 'supervisor' ? '3' : '3' }}" class="text-center text-muted">Belum ada kegiatan yang
                                                    selesai</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rencana Kegiatan Ditolak -->
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-navy">
                            <h3 class="card-title">
                                <i class="fas fa-times-circle mr-2"></i>
                                Rencana Kegiatan Ditolak
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('rencana_kegiatan.index') }}?filter_status=ditolak" class="btn btn-tool btn-sm" onclick="setFilterStatus('ditolak')">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Kegiatan</th>
                                            @if(auth()->user()->role->role_name === 'supervisor')
                                                <th>Penanggung Jawab</th>
                                            @else
                                                <th>Status</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $user = auth()->user();
                                            if ($user->role->role_name === 'supervisor') {
                                                $kegiatanDitolak = \App\Models\RencanaKegiatan::where('status', 'ditolak')
                                                    ->latest()
                                                    ->take(5)
                                                    ->get();
                                            } else {
                                                $kegiatanDitolak = \App\Models\RencanaKegiatan::where('user_id', $user->id)
                                                    ->where('status', 'ditolak')
                                                    ->latest()
                                                    ->take(5)
                                                    ->get();
                                            }
                                        @endphp
                                        @forelse($kegiatanDitolak as $kegiatan)
                                            <tr onclick="window.location.href='{{ route('rencana_kegiatan.show', $kegiatan->uuid) }}'" style="cursor: pointer;">
                                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ Str::limit($kegiatan->nama_kegiatan, 30) }}
                                                </td>
                                                @if(auth()->user()->role->role_name === 'supervisor')
                                                    <td>
                                                        {{ Str::limit($kegiatan->penanggung_jawab, 30) }}
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->role->role_name === 'supervisor' ? '3' : '3' }}" class="text-center text-muted">Belum ada kegiatan yang
                                                    ditolak</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <!-- FullCalendar CSS -->
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <!-- FullCalendar JS -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/id.js"></script>
        
        <script>
            // Function to set filter status in localStorage
            function setFilterStatus(status) {
                localStorage.setItem('filterStatus', status);
            }

            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    aspectRatio: 1.8,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: '{{ route("dashboard.events") }}',
                    eventClick: function(info) {
                        // Buka detail kegiatan di tab yang sama
                        window.location.href = info.event.url;
                        return false; // Mencegah default behavior
                    },
                    eventDidMount: function(info) {
                        // Styling untuk event
                        info.el.style.cursor = 'pointer';
                        info.el.style.borderRadius = '4px';
                        info.el.style.margin = '2px';
                    },
                    displayEventTime: false, // Default sembunyikan waktu
                    eventDisplay: 'block', // Tampilkan event sebagai block
                    dayMaxEvents: true, // Tampilkan "more" link jika terlalu banyak event
                    moreLinkClick: 'popover', // Tampilkan event tambahan di popover
                    datesSet: function(dateInfo) {
                        // Tampilkan waktu hanya untuk week dan list view
                        if (dateInfo.view.type === 'timeGridWeek' || dateInfo.view.type === 'listWeek') {
                            calendar.setOption('displayEventTime', true);
                        } else {
                            calendar.setOption('displayEventTime', false);
                        }
                    },
                    buttonText: {
                        today: 'Hari Ini',
                        month: 'Bulan',
                        week: 'Minggu',
                        list: 'Daftar'
                    },
                    allDaySlot: false, // Menyembunyikan slot 'Sehari penuh'
                    noEventsText: 'Tidak ada kegiatan yang dijadwalkan',
                    loading: function(bool) {
                        if (bool) {
                            // Tampilkan loading indicator
                            calendarEl.style.opacity = '0.5';
                        } else {
                            calendarEl.style.opacity = '1';
                        }
                    }
                });
                
                calendar.render();
            });
        </script>
    @endpush
