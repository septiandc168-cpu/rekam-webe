@extends('layouts.adminlte')

@section('content_title', 'History Realisasi Kegiatan')

@section('content')
<div class="card shadow-sm elevation-1 text-sm">
    <div class="card-header bg-white">
        <h6 class="card-title fw-bold text-dark mt-1 mb-0" style="font-size: 0.95rem;">History Realisasi Kegiatan</h6>
    </div>

    {{-- Filter Section --}}
    <div class="card-body border-bottom bg-light py-2 px-3">
        <form action="{{ route('history_realisasi.index') }}" method="GET" class="mb-0 no-loader" id="filter-form">
            {{-- Desktop Layout --}}
            <div class="d-none d-lg-flex input-group input-group-sm align-items-center">
                <span class="mr-2 text-muted fw-bold"><i class="fas fa-filter mr-1"></i> Filter:</span>
                <select name="bulan" class="form-control mr-2 rounded" style="min-width: 120px;">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $bln)
                        <option value="{{ $idx + 1 }}" {{ request('bulan') == $idx + 1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
                <input type="number" name="tahun" class="form-control mr-2 rounded" placeholder="Tahun"
                       value="{{ request('tahun') }}" min="2020" max="2030" style="min-width: 80px;">
                <select name="status_laporan" class="form-control mr-2 rounded" style="min-width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status_laporan') === 'diajukan' ? 'selected' : '' }}>Laporan Diajukan</option>
                    <option value="revisi"   {{ request('status_laporan') === 'revisi'   ? 'selected' : '' }}>Laporan Revisi</option>
                    <option value="final"    {{ request('status_laporan') === 'final'    ? 'selected' : '' }}>Laporan Final</option>
                </select>
                <button type="submit" class="btn bg-navy text-white btn-sm flex-shrink-0 shadow-sm mr-1">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                @if(auth()->user()->role->role_name === 'admin')
                    <button type="submit" formaction="{{ route('rencana_kegiatan.export.csv') }}" class="btn bg-navy text-white btn-sm flex-shrink-0 shadow-sm mr-1" title="Export Rekap Realisasi (CSV)">
                        <i class="fas fa-file-csv mr-1"></i> Export Rekap
                    </button>
                @endif
                @if(request()->hasAny(['bulan','tahun','status_laporan']))
                    <a href="{{ route('history_realisasi.index') }}" class="btn bg-navy text-white btn-sm flex-shrink-0 shadow-sm" title="Reset Filter">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </a>
                @endif
            </div>

            {{-- Mobile Layout --}}
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
                    <div class="col-12 mt-2">
                        <select name="status_laporan" class="form-control form-control-sm rounded">
                            <option value="">Semua Status</option>
                            <option value="diajukan" {{ request('status_laporan') === 'diajukan' ? 'selected' : '' }}>Laporan Diajukan</option>
                            <option value="revisi"   {{ request('status_laporan') === 'revisi'   ? 'selected' : '' }}>Laporan Revisi</option>
                            <option value="final"    {{ request('status_laporan') === 'final'    ? 'selected' : '' }}>Laporan Final</option>
                        </select>
                    </div>
                    <div class="col-12 mt-2 d-flex" style="gap:8px;">
                        <button type="submit" class="btn bg-navy text-white btn-sm flex-grow-1 shadow-sm">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        @if(auth()->user()->role->role_name === 'admin')
                            <button type="submit" formaction="{{ route('rencana_kegiatan.export.csv') }}" class="btn bg-navy text-white btn-sm flex-grow-1 shadow-sm" title="Export Rekap Realisasi (CSV)">
                                <i class="fas fa-file-csv mr-1"></i> Export Rekap
                            </button>
                        @endif
                        @if(request()->hasAny(['bulan','tahun','status_laporan']))
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
                    const mobileFields  = form.querySelectorAll('.d-lg-none select, .d-lg-none input');
                    desktopFields.forEach((desktopField, index) => {
                        if (mobileFields[index]) {
                            desktopField.addEventListener('change', function() { mobileFields[index].value = this.value; });
                        }
                    });
                    mobileFields.forEach((mobileField, index) => {
                        if (desktopFields[index]) {
                            mobileField.addEventListener('change', function() { desktopFields[index].value = this.value; });
                        }
                    });
                    mobileFields.forEach(field => {
                        field.removeAttribute('name');
                    });
                });
            </script>
        </form>
    </div>

    {{-- Table Content --}}
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle w-100" id="table2">
                <thead class="bg-navy text-white text-nowrap">
                    <tr>
                        <th class="align-middle text-center" style="width: 50px;">No</th>
                        <th class="align-middle text-center" style="width: 100px;">Aksi</th>
                        <th class="align-middle" style="width: 28%;">Nama Kegiatan</th>
                        <th class="align-middle" style="width: 20%;">Lokasi</th>
                        <th class="align-middle text-nowrap" style="width: 18%;">Tgl Realisasi</th>
                        <th class="align-middle" style="width: 17%;">Penanggung Jawab</th>
                        <th class="align-middle text-center" style="width: 120px;">Status Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $i => $laporan)
                        @php
                            $rencana          = $laporan->rencanaKegiatan;
                            $isLaporanLangsung = is_null($laporan->rencana_kegiatan_id);

                            $namaKegiatan = $rencana
                                ? $rencana->nama_kegiatan
                                : ($laporan->judul_kegiatan ?: 'Laporan Langsung');

                            $jenisLabel = $rencana
                                ? $rencana->getJenisKegiatanLabel()
                                : 'Laporan Langsung';

                            $pjName = ($rencana && $rencana->penanggung_jawab)
                                ? $rencana->penanggung_jawab
                                : ($rencana && $rencana->user ? $rencana->user->name : ($laporan->user ? $laporan->user->name : '-'));

                            $lokasi = $laporan->lokasi_kegiatan
                                ?: ($rencana ? ($rencana->desa ?: '-') : '-');

                            $tglMulai   = $laporan->realisasi_tanggal_mulai
                                ?: ($rencana ? $rencana->tanggal_mulai : null);
                            $tglSelesai = $laporan->realisasi_tanggal_selesai
                                ?: ($rencana ? $rencana->tanggal_selesai : null);

                            $tglMulaiFmt   = $tglMulai   ? \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') : '-';
                            $tglSelesaiFmt = $tglSelesai ? \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') : null;

                            $statusStyles = [
                                'draft'    => 'background:#f1f3f5; color:#495057;',
                                'diajukan' => 'background:#e8f0fe; color:#1a56db;',
                                'revisi'   => 'background:#fff3cd; color:#856404;',
                                'final'    => 'background:#def7ec; color:#03543f;',
                            ];
                            $badgeStyle = $statusStyles[$laporan->status] ?? 'background:#f1f3f5; color:#495057;';
                            $statusLabel = [
                                'draft'    => 'Draft',
                                'diajukan' => 'Diajukan',
                                'revisi'   => 'Revisi',
                                'final'    => 'Final',
                            ][$laporan->status] ?? ucfirst($laporan->status);
                        @endphp
                        <tr class="border-bottom">
                            <td class="align-middle text-center text-muted">{{ $i + 1 }}</td>
                            <td class="align-middle text-center">
                                <div class="d-flex justify-content-center" style="gap: 4px;">
                                    {{-- Lihat Rencana --}}
                                    @if($rencana)
                                        <a href="{{ route('rencana_kegiatan.show', [$rencana->uuid ?? $rencana->id, 'from' => 'history']) }}"
                                           class="btn btn-sm bg-navy text-white shadow-sm rounded"
                                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                           title="Lihat Detail Rencana">
                                            <i class="fas fa-calendar" style="font-size: 12px;"></i>
                                        </a>
                                    @endif
                                    {{-- Lihat Laporan --}}
                                    <a href="{{ route('laporan_kegiatan.show', [$laporan->uuid ?? $laporan->id, 'from' => 'history']) }}"
                                       class="btn btn-sm bg-navy text-white shadow-sm rounded"
                                       style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                       title="Lihat Detail Laporan">
                                        <i class="fas fa-file-alt" style="font-size: 12px;"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="align-middle py-3">
                                <a href="{{ route('laporan_kegiatan.show', [$laporan->uuid ?? $laporan->id, 'from' => 'history']) }}"
                                   class="text-dark text-wrap d-block"
                                   style="max-width: 320px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-decoration: none;"
                                   title="{{ $namaKegiatan }}">
                                    <strong>{{ $namaKegiatan }}</strong>
                                </a>
                                @if($isLaporanLangsung)
                                    <span style="background:#e2e8f0; color:#334155; padding:2px 8px; border-radius:4px; font-size:0.72rem; font-weight:500;">
                                        Laporan Langsung
                                    </span>
                                @else
                                    <small class="text-muted text-truncate d-block" style="max-width: 260px;" title="{{ $jenisLabel }}">
                                        <i class="fas fa-tags mr-1"></i>{{ $jenisLabel }}
                                    </small>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="text-wrap"
                                    style="max-width: 200px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                    title="{{ $lokasi }}">
                                    {{ $lokasi }}
                                </div>
                            </td>
                            <td class="align-middle text-nowrap">
                                @if($tglMulaiFmt !== '-')
                                    <span class="text-dark">
                                        <i class="far fa-calendar-alt mr-1 text-muted"></i>{{ $tglMulaiFmt }}
                                    </span>
                                    @if($tglSelesaiFmt && $tglSelesaiFmt !== $tglMulaiFmt)
                                        <br><small class="text-muted">s/d {{ $tglSelesaiFmt }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <span class="text-truncate d-block" style="max-width: 170px;" title="{{ $pjName }}">
                                        {{ $pjName }}
                                    </span>
                                </div>
                            </td>
                            <td class="align-middle text-center">
                                <span style="{{ $badgeStyle }} padding: 3px 10px; border-radius: 4px; font-size: 0.78rem; font-weight: 600;">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                Tidak ada data realisasi kegiatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#table2')) {
                $('#table2').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "language": {
                        "search": "Cari:",
                        "lengthMenu": "Tampilkan _MENU_ data per halaman",
                        "zeroRecords": "Data tidak ditemukan",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                        "infoEmpty": "Tidak ada data yang tersedia",
                        "infoFiltered": "(difilter dari _MAX_ total data)",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "Selanjutnya",
                            "previous": "Sebelumnya"
                        }
                    }
                });
            }
        });
    </script>
@endpush
