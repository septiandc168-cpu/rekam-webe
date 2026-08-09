@extends('layouts.adminlte')

@section('content_title', 'History Realisasi Kegiatan')

@push('styles')
<style>
    .history-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        overflow: hidden;
    }
    .history-card:hover {
        box-shadow: 0 8px 24px rgba(0,31,63,0.10) !important;
        transform: translateY(-2px);
    }
    .history-card .card-header-custom {
        background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
        padding: 14px 18px;
        min-height: 105px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .history-card .title-text {
        font-size: 0.9rem;
        line-height: 1.4;
        min-height: 42px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .badge-jenis {
        font-size: 0.68rem;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 500;
        letter-spacing: 0.2px;
        background: rgba(255, 255, 255, 0.14);
        color: #e2e8f0;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .badge-jenis-konservasi,
    .badge-jenis-edukasi,
    .badge-jenis-usaha,
    .badge-jenis-lainnya {
        background: rgba(255, 255, 255, 0.14);
        color: #e2e8f0;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .badge-laporan-final      { background: rgba(255, 255, 255, 0.14); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.25); padding: 2px 8px; border-radius: 4px; font-size: 0.68rem; font-weight: 500; }
    .badge-laporan-diajukan   { background: rgba(255, 255, 255, 0.14); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.25); padding: 2px 8px; border-radius: 4px; font-size: 0.68rem; font-weight: 500; }
    .badge-laporan-revisi     { background: rgba(255, 255, 255, 0.14); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.25); padding: 2px 8px; border-radius: 4px; font-size: 0.68rem; font-weight: 500; }
    .badge-laporan-draft      { background: rgba(255, 255, 255, 0.14); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.25); padding: 2px 8px; border-radius: 4px; font-size: 0.68rem; font-weight: 500; }
    .badge-laporan-none       { background: rgba(255, 255, 255, 0.14); color: #e2e8f0; border: 1px solid rgba(255, 255, 255, 0.25); padding: 2px 8px; border-radius: 4px; font-size: 0.68rem; font-weight: 500; }
    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 5px;
        font-size: 0.82rem;
    }
    .info-row i {
        width: 16px;
        color: #64748b;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .info-row span {
        color: #334155;
    }
    .stat-mini {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        flex: 1;
        min-width: 0;
    }
    .stat-mini .stat-val {
        font-size: 1rem;
        font-weight: 700;
        color: #001f3f;
    }
    .stat-mini .stat-lbl {
        font-size: 0.68rem;
        color: #64748b;
        text-align: center;
    }
    .filter-section {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .summary-bar {
        background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
        border-radius: 10px;
        color: white;
        padding: 18px 24px;
    }
    .summary-bar .s-num { font-size: 1.6rem; font-weight: 700; }
    .summary-bar .s-lbl { font-size: 0.78rem; opacity: 0.8; }
</style>
@endpush

@section('content')
<div class="text-sm">

    {{-- Summary Stats Bar --}}
    <div class="summary-bar mb-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-5 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-history fa-2x mr-3" style="opacity:0.7;"></i>
                    <div>
                        <div style="font-size: 1.05rem; font-weight: 600;">History Realisasi Kegiatan</div>
                        <div style="opacity:0.75; font-size:0.8rem;">Semua realisasi kegiatan lapangan (Laporan Diajukan, Revisi, maupun Final)</div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="d-flex flex-wrap justify-content-end" style="gap: 12px;">
                    <div class="text-center" style="flex:1; max-width: 200px;">
                        <div class="s-num">{{ $totalSelesai }}</div>
                        <div class="s-lbl">Total Realisasi Kegiatan Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="filter-section shadow-sm mb-4 p-3">
        <form action="{{ route('history_realisasi.index') }}" method="GET" class="mb-0 no-loader" id="filter-form">
            <div class="d-none d-lg-flex input-group input-group-sm align-items-center">
                <span class="mr-2 text-muted fw-bold"><i class="fas fa-filter mr-1"></i> Filter:</span>
                <select name="bulan" class="form-control mr-2 rounded" style="min-width: 130px;">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $bln)
                        <option value="{{ $idx + 1 }}" {{ request('bulan') == $idx + 1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
                <input type="number" name="tahun" class="form-control mr-2 rounded" placeholder="Tahun"
                       value="{{ request('tahun') }}" min="2020" max="2030" style="min-width: 80px;">
                <select name="jenis" class="form-control mr-2 rounded" style="min-width: 150px;">
                    <option value="">Semua Jenis</option>
                    <option value="konservasi"       {{ request('jenis') === 'konservasi'       ? 'selected' : '' }}>Konservasi</option>
                    <option value="edukasi"          {{ request('jenis') === 'edukasi'          ? 'selected' : '' }}>Edukasi</option>
                    <option value="usaha masyarakat" {{ request('jenis') === 'usaha masyarakat' ? 'selected' : '' }}>Usaha Masyarakat</option>
                    <option value="lainnya"          {{ request('jenis') === 'lainnya'          ? 'selected' : '' }}>Lainnya</option>
                    <option value="langsung"         {{ request('jenis') === 'langsung'         ? 'selected' : '' }}>Laporan Langsung</option>
                </select>
                @if(auth()->user()->role->role_name === 'admin')
                <select name="user_id" class="form-control mr-2 rounded" style="min-width: 160px;">
                    <option value="">Semua Anggota</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
                @endif
                <button type="submit" class="btn bg-navy text-white btn-sm flex-shrink-0 shadow-sm mr-1">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                @if(auth()->user()->role->role_name === 'admin')
                    <button type="submit" formaction="{{ route('rencana_kegiatan.export.excel') }}" class="btn bg-navy text-white btn-sm flex-shrink-0 shadow-sm mr-1" title="Export Excel Rekap Realisasi">
                        <i class="fas fa-file-excel mr-1"></i> Export Rekap
                    </button>
                @endif
                @if(request()->hasAny(['bulan','tahun','jenis','user_id']))
                    <a href="{{ route('history_realisasi.index') }}" class="btn bg-navy text-white btn-sm flex-shrink-0 shadow-sm" title="Reset Filter">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </a>
                @endif
            </div>

            {{-- Mobile --}}
            <div class="d-lg-none">
                <div class="row g-2">
                    <div class="col-6 mt-2">
                        <select name="bulan" class="form-control form-control-sm rounded">
                            <option value="">Semua Bulan</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $bln)
                                <option value="{{ $idx + 1 }}" {{ request('bulan') == $idx + 1 ? 'selected' : '' }}>{{ $bln }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 mt-2">
                        <input type="number" name="tahun" class="form-control form-control-sm rounded" placeholder="Tahun"
                               value="{{ request('tahun') }}" min="2020" max="2030">
                    </div>
                    <div class="{{ auth()->user()->role->role_name === 'admin' ? 'col-6' : 'col-12' }} mt-2">
                        <select name="jenis" class="form-control form-control-sm rounded">
                            <option value="">Semua Jenis</option>
                            <option value="konservasi" {{ request('jenis') === 'konservasi' ? 'selected' : '' }}>Konservasi</option>
                            <option value="edukasi" {{ request('jenis') === 'edukasi' ? 'selected' : '' }}>Edukasi</option>
                            <option value="usaha masyarakat" {{ request('jenis') === 'usaha masyarakat' ? 'selected' : '' }}>Usaha Masyarakat</option>
                            <option value="lainnya" {{ request('jenis') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            <option value="langsung" {{ request('jenis') === 'langsung' ? 'selected' : '' }}>Laporan Langsung</option>
                        </select>
                    </div>
                    @if(auth()->user()->role->role_name === 'admin')
                    <div class="col-6 mt-2">
                        <select name="user_id" class="form-control form-control-sm rounded">
                            <option value="">Semua Anggota</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-12 mt-2 d-flex" style="gap:8px;">
                        <button type="submit" class="btn bg-navy text-white btn-sm flex-grow-1 shadow-sm">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        @if(auth()->user()->role->role_name === 'admin')
                            <button type="submit" formaction="{{ route('rencana_kegiatan.export.excel') }}" class="btn bg-navy text-white btn-sm flex-grow-1 shadow-sm">
                                <i class="fas fa-file-excel mr-1"></i> Export Rekap
                            </button>
                        @endif
                        @if(request()->hasAny(['bulan','tahun','jenis','user_id']))
                            <a href="{{ route('history_realisasi.index') }}" class="btn bg-navy text-white btn-sm flex-grow-1 shadow-sm text-center">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('filter-form');
                    if (!form) return;

                    const desktopFields = form.querySelectorAll('.d-none.d-lg-flex select, .d-none.d-lg-flex input');
                    const mobileFields = form.querySelectorAll('.d-lg-none select, .d-lg-none input');
                    
                    // Sync desktop to mobile
                    desktopFields.forEach((desktopField, index) => {
                        if (mobileFields[index]) {
                            desktopField.addEventListener('change', function() {
                                mobileFields[index].value = this.value;
                            });
                        }
                    });
                    
                    // Sync mobile to desktop
                    mobileFields.forEach((mobileField, index) => {
                        if (desktopFields[index]) {
                            mobileField.addEventListener('change', function() {
                                desktopFields[index].value = this.value;
                            });
                        }
                    });
                    
                    // Prevent duplicate name conflict on form submit
                    form.addEventListener('submit', function() {
                        mobileFields.forEach(field => {
                            field.removeAttribute('name');
                        });
                    });
                });
            </script>
        </form>
    </div>

    {{-- Content --}}
    @if($laporans->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="empty-state py-5">
                    <i class="fas fa-box-open text-muted" style="font-size:3.5rem;"></i>
                    <h5 class="mt-3 text-muted">Belum Ada Data</h5>
                    <p class="text-muted">Belum ada kegiatan yang berstatus <strong>Selesai</strong> atau Laporan <strong>Final</strong> sesuai filter yang dipilih.</p>
                </div>
            </div>
        </div>
    @else
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="fas fa-list mr-1"></i> Menampilkan <strong>{{ $laporans->firstItem() ?? 0 }} - {{ $laporans->lastItem() ?? 0 }}</strong> dari <strong>{{ $laporans->total() }}</strong> kegiatan
            </small>
        </div>
        <div class="row">
            @foreach($laporans as $laporan)
                @php
                    $rencana = $laporan->rencanaKegiatan;
                    $isLaporanLangsung = is_null($laporan->rencana_kegiatan_id);

                    $namaKegiatan = $rencana ? $rencana->nama_kegiatan : ($laporan->judul_kegiatan ?: 'Laporan Langsung');

                    if ($rencana) {
                        $jenisLabel = $rencana->getJenisKegiatanLabel();
                        $jenisBadge = match($rencana->jenis_kegiatan) {
                            'konservasi'       => 'badge-jenis-konservasi',
                            'edukasi'          => 'badge-jenis-edukasi',
                            'usaha masyarakat' => 'badge-jenis-usaha',
                            default            => 'badge-jenis-lainnya',
                        };
                    } else {
                        $jenisLabel = 'Laporan Langsung';
                        $jenisBadge = 'badge-jenis-lainnya';
                    }

                    $laporanBadgeClass = match($laporan->status) {
                        'final'    => 'badge-laporan-final',
                        'diajukan' => 'badge-laporan-diajukan',
                        'revisi'   => 'badge-laporan-revisi',
                        default    => 'badge-laporan-draft',
                    };
                    $laporanBadgeLabel = match($laporan->status) {
                        'final'    => 'Laporan Final',
                        'diajukan' => 'Laporan Diajukan',
                        'revisi'   => 'Laporan Revisi',
                        default    => 'Laporan Draft',
                    };

                    $userName = $laporan->user ? $laporan->user->name : ($rencana && $rencana->user ? $rencana->user->name : '-');
                    $lokasi = $laporan->lokasi_kegiatan ?: ($rencana ? ($rencana->desa ?: '-') : '-');

                    $tglMulaiRaw = $laporan->realisasi_tanggal_mulai ?: ($rencana ? $rencana->tanggal_mulai : $laporan->created_at);
                    $tglSelesaiRaw = $laporan->realisasi_tanggal_selesai ?: ($rencana ? $rencana->tanggal_selesai : null);

                    $tglMulaiFormatted = $tglMulaiRaw ? \Carbon\Carbon::parse($tglMulaiRaw)->translatedFormat('d M Y') : '-';
                    $tglSelesaiFormatted = $tglSelesaiRaw ? \Carbon\Carbon::parse($tglSelesaiRaw)->translatedFormat('d M Y') : null;

                    $targetPeserta = $laporan->target_peserta ?: ($rencana ? ($rencana->estimasi_peserta ?: '-') : '-');
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card history-card shadow-sm h-100">
                        {{-- Card Header --}}
                        <div class="history-card card-header-custom">
                            <h6 class="mb-2 text-white fw-bold title-text" title="{{ $namaKegiatan }}">
                                {{ $namaKegiatan }}
                            </h6>
                            <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                                <span class="badge-jenis {{ $jenisBadge }}">{{ $jenisLabel }}</span>
                                <span class="{{ $laporanBadgeClass }}">{{ $laporanBadgeLabel }}</span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body pb-2">
                            <div class="info-row">
                                <i class="fas fa-user"></i>
                                <span>{{ $userName }}</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ Str::limit($lokasi, 45) }}</span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-calendar-alt"></i>
                                <span>
                                    {{ $tglMulaiFormatted }}
                                    @if($tglSelesaiFormatted && $tglMulaiFormatted != $tglSelesaiFormatted)
                                        &ndash; {{ $tglSelesaiFormatted }}
                                    @endif
                                </span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-users"></i>
                                <span>Target: <strong>{{ $targetPeserta }} orang</strong></span>
                            </div>

                            {{-- Laporan Stats --}}
                            <hr class="my-2">
                            <div class="d-flex" style="gap:8px;">
                                <div class="stat-mini">
                                    <div class="stat-val">{{ $laporan->realisasi_peserta ?? '-' }}</div>
                                    <div class="stat-lbl">Realisasi Peserta</div>
                                </div>
                                <div class="stat-mini">
                                    <div class="stat-val" style="font-size:0.8rem;">
                                        {{ $laporan->realisasi_tanggal_mulai ? \Carbon\Carbon::parse($laporan->realisasi_tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                    <div class="stat-lbl">Tgl Realisasi</div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
                            <div class="d-flex" style="gap:8px;">
                                @if($rencana)
                                    <a href="{{ route('rencana_kegiatan.show', [$rencana->uuid ?? $rencana->id, 'from' => 'history']) }}"
                                       class="btn btn-sm bg-navy text-white flex-fill shadow-sm">
                                        <i class="fas fa-calendar-check mr-1"></i> Lihat Rencana
                                    </a>
                                @endif
                                <a href="{{ route('laporan_kegiatan.show', $laporan->uuid ?? $laporan->id) }}"
                                   class="btn btn-sm bg-navy text-white flex-fill shadow-sm">
                                    <i class="fas fa-file-alt mr-1"></i> Lihat Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Navigasi Pagination --}}
        @if (method_exists($laporans, 'links'))
            <div class="d-flex justify-content-center mt-4 mb-3">
                {{ $laporans->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
