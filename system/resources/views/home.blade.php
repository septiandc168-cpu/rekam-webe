@extends('layouts.adminlte')
@section('content_title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    /* FullCalendar custom event styling */
    .fc-event {
        border-radius: 4px !important;
        margin: 2px !important;
        border: none !important;
        overflow: hidden !important;
    }
    .fc-event-main {
        padding: 2px 2px !important;
        text-align: center !important;
        font-size: 0.65rem !important;
        font-weight: 700 !important;
        letter-spacing: -0.3px !important;
    }
    /* Fix Bootstrap Tooltip sizing and layout */
    .tooltip {
        font-size: 0.75rem !important;
        z-index: 1060 !important;
    }
    .tooltip-inner {
        max-width: 280px !important;
        font-size: 0.75rem !important;
        padding: 6px 10px !important;
        background-color: rgba(0, 0, 0, 0.9) !important;
        text-align: left !important;
        line-height: 1.4 !important;
    }
    /* Mengubah warna teks hari dan tanggal menjadi hitam (Month & List View) */
    .fc-col-header-cell-cushion, 
    .fc-daygrid-day-number, 
    .fc-day-header, 
    .fc-day-number,
    .fc-list-day-text,
    .fc-list-day-side-text,
    .fc-list-heading-main,
    .fc-list-heading-alt,
    .fc-list-day-cushion a,
    .fc-list-heading a {
        color: #000 !important;
        text-decoration: none !important;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid text-sm">
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
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-1">
                                    Selamat Datang, {{ ucwords(auth()->user()->name) }}!
                                </h4>
                                <p class="text-white mb-0" style="opacity: 0.8;">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    Peran: <strong>{{ ucfirst(auth()->user()->role->role_name) }}</strong>
                                </p>
                            </div>
                            <div class="col-md-4 text-right d-none d-md-block">
                                <i class="fas fa-tachometer-alt" style="font-size: 3rem; opacity: 0.15; color: white;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Widgets Row -->
        <div class="row mb-4">
            <div class="col-lg col-6 mb-3 mb-lg-0">
                <div class="small-box bg-white shadow-sm h-100 mb-0" style="border-top: 4px solid #007bff; padding-bottom: 30px;">
                    <div class="inner">
                        <h3 class="text-dark">{{ $totalRencana }}</h3>
                        <p class="text-muted">Total Rencana Kegiatan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt" style="color: #007bff; opacity: 0.15;"></i>
                    </div>
                    <a href="{{ route('rencana_kegiatan.index') }}" class="small-box-footer bg-light text-dark" style="position: absolute; bottom: 0; width: 100%;">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg col-6 mb-3 mb-lg-0">
                <div class="small-box bg-white shadow-sm h-100 mb-0" style="border-top: 4px solid #ffc107; padding-bottom: 30px;">
                    <div class="inner">
                        <h3 class="text-dark">{{ $totalDiajukan }}</h3>
                        <p class="text-muted">Menunggu Persetujuan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock" style="color: #ffc107; opacity: 0.15;"></i>
                    </div>
                    <a href="{{ route('rencana_kegiatan.index') }}?filter_status=diajukan" class="small-box-footer bg-light text-dark" style="position: absolute; bottom: 0; width: 100%;">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg col-6 mb-3 mb-lg-0">
                <div class="small-box bg-white shadow-sm h-100 mb-0" style="border-top: 4px solid #28a745; padding-bottom: 30px;">
                    <div class="inner">
                        <h3 class="text-dark">{{ $totalDisetujui }}</h3>
                        <p class="text-muted">Kegiatan Disetujui</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle" style="color: #28a745; opacity: 0.15;"></i>
                    </div>
                    <a href="{{ route('rencana_kegiatan.index') }}?filter_status=disetujui" class="small-box-footer bg-light text-dark" style="position: absolute; bottom: 0; width: 100%;">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg col-6 mb-3 mb-lg-0">
                <div class="small-box bg-white shadow-sm h-100 mb-0" style="border-top: 4px solid #17a2b8; padding-bottom: 30px;">
                    <div class="inner">
                        <h3 class="text-dark">{{ $totalLaporan }}</h3>
                        <p class="text-muted">{{ $isAdmin ? 'Total Laporan Masuk' : 'Laporan Saya' }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-folder-open" style="color: #17a2b8; opacity: 0.15;"></i>
                    </div>
                    <a href="{{ route('laporan_kegiatan.index') }}" class="small-box-footer bg-light text-dark" style="position: absolute; bottom: 0; width: 100%;">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Admin Only: Total Users Widget -->
            @if ($isAdmin)
                <div class="col-lg col-6 mb-3 mb-lg-0">
                    <div class="small-box bg-white shadow-sm h-100 mb-0" style="border-top: 4px solid #6c757d; padding-bottom: 30px;">
                        <div class="inner">
                            <h3 class="text-dark">{{ $totalUsers }}</h3>
                            <p class="text-muted">Total Pengguna Terdaftar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users" style="color: #6c757d; opacity: 0.15;"></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer bg-light text-dark" style="position: absolute; bottom: 0; width: 100%;">
                            Kelola Pengguna <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Chart & Calendar Row -->
        <div class="row d-flex align-items-stretch">
            <!-- Chart: Statistik Kegiatan per Bulan -->
            <div class="col-md-7 mb-4 d-flex">
                <div class="card w-100 h-100 d-flex flex-column">
                    <div class="card-header bg-navy">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Statistik Kegiatan per Bulan
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body flex-grow-1" style="position: relative; min-height: 350px;">
                        <div style="position: absolute; top: 1.25rem; left: 1.25rem; right: 1.25rem; bottom: 1.25rem;">
                            <canvas id="chartKegiatan"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="col-md-5 mb-4 d-flex">
                <div class="card w-100 h-100 d-flex flex-column">
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
                        <div id="calendar" style="min-height: 350px;"></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Peta Geotagging Row -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-navy">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt mr-2"></i>
                            Peta Sebaran Kegiatan
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
                        <div id="leafletMap" style="min-height: 400px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <!-- Leaflet MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
    <style>
        .small-box .inner h3 {
            font-size: 2.2rem;
            font-weight: 700;
        }
        .small-box .icon i {
            font-size: 70px;
            top: 10px;
        }
        #calendar {
            padding: 8px;
        }
        .fc .fc-toolbar-title {
            font-size: 1rem !important;
        }
        .fc .fc-button {
            padding: 0.2em 0.5em !important;
            font-size: 0.8rem !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/id.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Leaflet MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <!-- Leaflet Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Resolve tooltip conflict between jQuery UI and Bootstrap
            if (window.jQuery && $.fn.tooltip && $.fn.tooltip.noConflict) {
                var bootstrapTooltip = $.fn.tooltip.noConflict();
                $.fn.bootstrapTooltip = bootstrapTooltip;
            }

            // === Chart.js: Bar Chart Kegiatan per Bulan ===
            var ctx = document.getElementById('chartKegiatan').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Jumlah Kegiatan',
                        data: @json($chartValues),
                        backgroundColor: 'rgba(108, 117, 125, 0.7)',
                        borderColor: 'rgba(108, 117, 125, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        maxBarThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#001f3f',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 10,
                            cornerRadius: 6,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // === FullCalendar ===
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                aspectRatio: 1.35,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                events: '{{ route("dashboard.events") }}',
                eventClick: function(info) {
                    window.location.href = info.event.url;
                    return false;
                },
                eventDidMount: function(info) {
                    info.el.style.cursor = 'pointer';
                    
                    // Jangan tampilkan tooltip di tampilan list, karena nama sudah terlihat jelas
                    if (info.view.type === 'listWeek' || info.view.type === 'listDay' || info.view.type === 'listMonth') {
                        // Apply soft background and text colors to the row
                        var tds = info.el.querySelectorAll('td');
                        tds.forEach(function(td) {
                            td.style.setProperty('background-color', info.event.backgroundColor, 'important');
                        });
                        
                        var titleLink = info.el.querySelector('.fc-list-event-title a');
                        if (titleLink) {
                            titleLink.style.setProperty('color', info.event.textColor, 'important');
                            titleLink.style.setProperty('font-weight', '600', 'important');
                        }
                        
                        var timeEl = info.el.querySelector('.fc-list-event-time');
                        if (timeEl) {
                            timeEl.style.setProperty('color', info.event.textColor, 'important');
                        }
                        return;
                    }
                    
                    // Gunakan Bootstrap Tooltip untuk memunculkan pop-up saat hover
                    var isBootstrapTooltip = typeof $.fn.bootstrapTooltip === 'function';
                    if (window.jQuery && (isBootstrapTooltip || $.fn.tooltip)) {
                        var tooltipContent = '<strong>' + (info.event.extendedProps.nama_kegiatan || info.event.title) + '</strong>';
                        if (info.event.extendedProps && info.event.extendedProps.description) {
                            tooltipContent += '<br/><small>' + info.event.extendedProps.description + '</small>';
                        }
                        
                        var el = $(info.el);
                        var tooltipOpts = {
                            title: tooltipContent,
                            html: true,
                            placement: 'top',
                            trigger: 'hover',
                            container: 'body'
                        };
                        
                        if (isBootstrapTooltip) {
                            el.bootstrapTooltip(tooltipOpts);
                        } else {
                            el.tooltip(tooltipOpts);
                        }
                    } else {
                        // Fallback title native
                        info.el.setAttribute('title', info.event.title);
                    }
                },
                displayEventTime: false,
                eventDisplay: 'block',
                dayMaxEvents: 2,
                moreLinkClick: 'popover',
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    list: 'Daftar'
                },
                noEventsText: 'Tidak ada kegiatan',
                loading: function(bool) {
                    calendarEl.style.opacity = bool ? '0.5' : '1';
                }
            });

            calendar.render();
            // === Leaflet.js: Peta Geotagging ===
            var mapData = @json($mapData);
            
            if (mapData.length > 0) {
                // Initialize map centered at first coordinate
                var map = L.map('leafletMap').setView([mapData[0].lat, mapData[0].lng], 9);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Setup custom marker icons
                function getMarkerIcon(color) {
                    return new L.Icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-' + color + '.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });
                }

                var icons = {
                    'disetujui': getMarkerIcon('green'),
                    'diajukan': getMarkerIcon('blue'),
                    'revisi': getMarkerIcon('gold'),
                    'ditolak': getMarkerIcon('red'),
                    'selesai': getMarkerIcon('grey'),
                    'draft': getMarkerIcon('black')
                };

                // Add markers
                var bounds = [];
                // Inisialisasi Marker Cluster Group
                var clusterGroup = L.markerClusterGroup({
                    spiderfyOnMaxZoom: true,
                    showCoverageOnHover: false,
                    zoomToBoundsOnClick: true
                });

                mapData.forEach(function(item) {
                    // Determine icon and color based on status
                    var markerIcon = icons[item.status] || getMarkerIcon('blue');
                    
                    var marker = L.marker([item.lat, item.lng], {icon: markerIcon}); // Hapus .addTo(map)
                    
                    var statusStyles = {
                        'draft': 'background:#f1f3f5; color:#495057;',
                        'diajukan': 'background:#e8f0fe; color:#1a56db;',
                        'revisi': 'background:#fff3cd; color:#856404;',
                        'ditolak': 'background:#fde8e8; color:#c81e1e;',
                        'disetujui': 'background:#def7ec; color:#03543f;',
                        'selesai': 'background:#e2e8f0; color:#334155;'
                    };
                    var activeStyle = statusStyles[item.status] || 'background:#f1f3f5; color:#495057;';
                                        var popupContent = `
                        <div style="min-width: 220px; padding-top: 5px; font-size: 0.8rem;">
                            <h6 class="font-weight-bold mb-1 text-dark" style="line-height: 1.3; font-size: 0.88rem;">${item.nama}</h6>
                            <div class="mb-2">
                                <span style="${activeStyle} padding: 2px 8px; border-radius: 4px; font-size: 0.68rem; font-weight: 600; display: inline-block; text-transform: capitalize;">
                                    ${item.status.replace('_', ' ')}
                                </span>
                            </div>
                            
                            <ul class="list-unstyled text-muted mb-3" style="font-size: 0.78rem;">
                                <li class="mb-1"><i class="fas fa-map-marker-alt text-danger mr-2" style="width: 15px; text-align: center;"></i> ${item.desa}</li>
                                <li class="mb-1"><i class="far fa-calendar-alt text-primary mr-2" style="width: 15px; text-align: center;"></i> ${item.tanggal}</li>
                                <li><i class="fas fa-user-circle text-info mr-2" style="width: 15px; text-align: center;"></i> ${item.person}</li>
                            </ul>
 
                            <a href="${item.url}" class="btn btn-sm bg-navy text-white btn-block shadow-sm" style="border-radius: 20px; font-size: 0.75rem;">
                                <i class="fas fa-search mr-1"></i> Lihat Detail
                            </a>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                    bounds.push([item.lat, item.lng]);
                    
                    // Simpan objek marker di dalam item untuk keperluan filter
                    item.markerObj = marker;
                    
                    // Masukkan ke cluster group
                    clusterGroup.addLayer(marker);
                });
                
                // Tambahkan cluster group ke peta utama
                map.addLayer(clusterGroup);

                // Setup Kontrol Pencarian dengan Rekomendasi (Geocoder)
                var geocoderControl = L.Control.geocoder({
                    defaultMarkGeocode: false,
                    placeholder: "Cari kegiatan, lokasi, desa...",
                    geocoder: L.Control.Geocoder.nominatim({
                        geocodingQueryParams: { countrycodes: 'id' }
                    })
                })
                .on('markgeocode', function(e) {
                    map.fitBounds(e.geocode.bbox);
                })
                .addTo(map);

                // Ambil elemen input dari geocoder untuk filter marker lokal (Real-time)
                var geocoderInput = geocoderControl.getContainer().querySelector('input');
                geocoderInput.addEventListener('keyup', function(e) {
                    var text = e.target.value.toLowerCase();
                    clusterGroup.clearLayers();
                    
                    var newBounds = [];
                    var matchedMarkers = [];
                    
                    mapData.forEach(function(item) {
                        var nama = (item.nama || '').toLowerCase();
                        var desa = (item.desa || '').toLowerCase();
                        var person = (item.person || '').toLowerCase();
                        
                        if(nama.includes(text) || desa.includes(text) || person.includes(text)) {
                            matchedMarkers.push(item.markerObj);
                            newBounds.push([item.lat, item.lng]);
                        }
                    });

                    // Tambahkan marker yang cocok kembali ke map
                    if (matchedMarkers.length > 0) {
                        clusterGroup.addLayers(matchedMarkers);
                    }

                    // Zoom ke marker lokal jika ada
                    if (text !== '' && newBounds.length > 0) {
                        map.fitBounds(newBounds, {maxZoom: 15});
                    } else if (text === '' && bounds.length > 1) {
                        map.fitBounds(bounds);
                    }
                });

                // Fit map to markers if more than 1
                if (bounds.length > 1) {
                    map.fitBounds(bounds);
                }
            } else {
                document.getElementById('leafletMap').innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-muted">Tidak ada data koordinat kegiatan.</div>';
            }
        });
    </script>
@endpush
