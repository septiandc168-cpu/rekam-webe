@extends('layouts.adminlte')

@section('content_title', 'Daftar Laporan Kegiatan')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- === SEKSI: Rencana Kegiatan Disetujui yang Siap Dibuatkan Laporan (Hanya Anggota) === --}}
    @if(isset($rencanaDisetujui) && $rencanaDisetujui->count() > 0)
    <div class="card shadow-sm mb-4" style="border: 2px solid #22c55e; border-radius: 10px; overflow: hidden;">
        <div class="card-header d-flex align-items-center justify-content-between py-2 px-3"
             style="background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);">
            <div class="d-flex align-items-center">
                <i class="fas fa-clipboard-check text-white mr-2"></i>
                <h6 class="mb-0 text-white fw-bold" style="font-size: 0.9rem;">
                    Rencana Kegiatan Siap Laporan
                    <span class="badge badge-light text-success ml-1" style="font-size: 0.78rem;">
                        {{ $rencanaDisetujui->count() }}
                    </span>
                </h6>
            </div>
            <small class="text-white d-none d-md-block" style="opacity:0.85; font-size:0.78rem;">
                <i class="fas fa-info-circle mr-1"></i> Rencana kegiatan yang sudah disetujui dan belum ada laporannya
            </small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-borderless mb-0 text-sm align-middle">
                    <thead style="background:#f0fdf4;">
                        <tr>
                            <th class="align-middle text-center pl-3" style="width:40px;">No</th>
                            <th class="align-middle" style="width:30%;">Nama Kegiatan</th>
                            <th class="align-middle d-none d-md-table-cell" style="width:15%;">Jenis</th>
                            <th class="align-middle d-none d-lg-table-cell" style="width:20%;">Lokasi</th>
                            <th class="align-middle d-none d-md-table-cell" style="width:15%;">Tanggal Pelaksanaan</th>
                            <th class="align-middle text-center" style="width:130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rencanaDisetujui as $idx => $rencana)
                        <tr>
                            <td class="align-middle text-center pl-3">{{ $idx + 1 }}</td>
                            <td class="align-middle">
                                <div class="fw-bold text-dark text-wrap"
                                     style="max-width:260px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"
                                     title="{{ $rencana->nama_kegiatan }}">
                                    {{ $rencana->nama_kegiatan }}
                                </div>
                                @if($rencana->penanggung_jawab)
                                    <small class="text-muted"><i class="fas fa-user mr-1"></i>{{ $rencana->penanggung_jawab }}</small>
                                @endif
                            </td>
                            <td class="align-middle d-none d-md-table-cell">
                                @php
                                    $jenisStyles = [
                                        'konservasi'       => 'background:#d1fae5; color:#065f46;',
                                        'edukasi'          => 'background:#dbeafe; color:#1e40af;',
                                        'usaha masyarakat' => 'background:#fef3c7; color:#92400e;',
                                    ];
                                    $jenisStyle = $jenisStyles[$rencana->jenis_kegiatan] ?? 'background:#f3e8ff; color:#6b21a8;';
                                @endphp
                                <span style="{{ $jenisStyle }} padding:2px 9px; border-radius:20px; font-size:0.73rem; font-weight:600;">
                                    {{ $rencana->getJenisKegiatanLabel() }}
                                </span>
                            </td>
                            <td class="align-middle text-muted d-none d-lg-table-cell" style="font-size:0.82rem;">
                                <i class="fas fa-map-marker-alt mr-1 text-muted"></i>
                                {{ Str::limit($rencana->desa ?? '-', 35) }}
                            </td>
                            <td class="align-middle d-none d-md-table-cell" style="font-size:0.82rem; white-space:nowrap;">
                                <i class="fas fa-calendar-alt mr-1 text-muted"></i>
                                {{ \Carbon\Carbon::parse($rencana->tanggal_mulai)->translatedFormat('d M Y') }}
                                @if($rencana->tanggal_selesai && $rencana->tanggal_mulai != $rencana->tanggal_selesai)
                                    <br><span class="text-muted">&ndash; {{ \Carbon\Carbon::parse($rencana->tanggal_selesai)->translatedFormat('d M Y') }}</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <a href="{{ route('laporan_kegiatan.create', ['rencana_kegiatan_id' => $rencana->uuid]) }}"
                                   class="btn btn-sm btn-success shadow-sm"
                                   title="Buat Laporan untuk Kegiatan Ini">
                                    <i class="fas fa-file-alt mr-1"></i> Buat Laporan
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="card shadow-sm elevation-1 text-sm">
        <div class="card-header bg-white">
            <h6 class="card-title fw-bold text-dark mt-1 mb-0" style="font-size: 0.95rem;">Data Laporan Kegiatan</h6>
            <div class="card-tools">
                @can('create', \App\Models\LaporanKegiatan::class)
                    <a href="{{ route('laporan_kegiatan.create', ['jenis' => 'langsung']) }}" class="btn bg-navy text-white btn-sm shadow-sm">
                        <i class="fas fa-paper-plane mr-1"></i> Buat Laporan Langsung
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body border-bottom bg-light py-2 px-3">
            <form action="{{ route('laporan_kegiatan.index') }}" method="GET" class="mb-0 no-loader" id="filter-form">
                <div class="export-form-container">
                    <!-- Desktop Layout -->
                    <div class="d-none d-lg-flex input-group input-group-sm align-items-center">
                        <span class="mr-2 text-muted fw-bold"><i class="fas fa-filter mr-1"></i> Filter:</span>
                        <select name="bulan" class="form-control mr-2 rounded" style="min-width: 120px;">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ (request('bulan') == $i) ? 'selected' : '' }}>
                                    {{ ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$i - 1] }}
                                </option>
                            @endfor
                        </select>
                        <input type="number" name="tahun" class="form-control mr-2 rounded" placeholder="Tahun" 
                               value="{{ request('tahun') }}" min="2020" max="2030" style="min-width: 80px;">
                        <select name="status" class="form-control mr-2 rounded" style="min-width: 130px;">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ (request('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                            <option value="diajukan" {{ (request('status') == 'diajukan') ? 'selected' : '' }}>Diajukan</option>
                            <option value="revisi" {{ (request('status') == 'revisi') ? 'selected' : '' }}>Revisi</option>
                            <option value="final" {{ (request('status') == 'final') ? 'selected' : '' }}>Final</option>
                        </select>
                        @if(auth()->user()->role->role_name === 'admin')
                            <select name="user_id" class="form-control mr-2 rounded" style="min-width: 150px;">
                                <option value="">Semua User</option>
                                @if(isset($users))
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (request('user_id') == $user->id) ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        @endif
                        <button type="submit" class="btn bg-navy text-white btn-sm flex-shrink-0 shadow-sm mr-1" title="Filter Tabel">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                    
                    <!-- Mobile Layout -->
                    <div class="d-lg-none">
                        <div class="row g-2">
                            <div class="col-6 mt-2">
                                <select name="bulan" class="form-control form-control-sm rounded">
                                    <option value="">Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ (request('bulan') == $i) ? 'selected' : '' }}>
                                            {{ ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$i - 1] }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-6 mt-2">
                                <input type="number" name="tahun" class="form-control form-control-sm rounded" placeholder="Tahun" 
                                       value="{{ request('tahun') }}" min="2020" max="2030">
                            </div>
                            <div class="col-6 mt-2">
                                <select name="status" class="form-control form-control-sm rounded">
                                    <option value="">Status</option>
                                    <option value="draft" {{ (request('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                                    <option value="diajukan" {{ (request('status') == 'diajukan') ? 'selected' : '' }}>Diajukan</option>
                                    <option value="revisi" {{ (request('status') == 'revisi') ? 'selected' : '' }}>Revisi</option>
                                    <option value="final" {{ (request('status') == 'final') ? 'selected' : '' }}>Final</option>
                                </select>
                            </div>
                            @if(auth()->user()->role->role_name === 'admin')
                                <div class="col-6 mt-2">
                                    <select name="user_id" class="form-control form-control-sm rounded">
                                        <option value="">User</option>
                                        @if(isset($users))
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ (request('user_id') == $user->id) ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif
                            <div class="{{ auth()->user()->role->role_name === 'admin' ? 'col-12' : 'col-6' }} mt-2">
                                <button type="submit" class="btn bg-navy text-white btn-sm btn-block w-100 shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('filter-form');
                    const desktopFields = form.querySelectorAll('.d-none.d-lg-flex select, .d-none.d-lg-flex input');
                    const mobileFields = form.querySelectorAll('.d-lg-none select, .d-lg-none input');
                    
                    desktopFields.forEach((desktopField, index) => {
                        if (mobileFields[index]) {
                            desktopField.addEventListener('change', function() {
                                mobileFields[index].value = this.value;
                            });
                        }
                    });
                    
                    mobileFields.forEach((mobileField, index) => {
                        if (desktopFields[index]) {
                            mobileField.addEventListener('change', function() {
                                desktopFields[index].value = this.value;
                            });
                        }
                    });
                    
                    mobileFields.forEach(field => {
                        field.removeAttribute('name');
                    });
                });
            </script>
        </div>
        <div class="card-body">
            @if ($laporans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle w-100" id="table2">
                        <thead class="bg-navy text-white text-nowrap">
                            <tr>
                                <th class="align-middle text-center" style="width: 50px;">No</th>
                                <th class="align-middle text-center" style="width: 80px;">Aksi</th>
                                <th class="align-middle" style="width: 25%;">Nama Kegiatan</th>
                                <th class="align-middle" style="width: 15%;">Penanggung Jawab</th>
                                <th class="align-middle" style="width: 20%;">Lokasi</th>
                                <th class="align-middle" style="width: 15%;">Tanggal Laporan</th>
                                <th class="align-middle text-center" style="width: 100px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporans as $index => $laporan)
                                <tr>
                                    <td class="align-middle text-center">{{ $index + 1 }}</td>
                                    <td class="align-middle text-center">
                                        <a class="btn btn-sm bg-navy text-white shadow-sm rounded"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                            href="{{ route('laporan_kegiatan.show', $laporan) }}"
                                            title="Lihat Detail & Verifikasi">
                                            <i class="fas fa-info" style="font-size: 12px;"></i>
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-wrap" style="max-width: 250px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $laporan->isDarurat() ? $laporan->judul_kegiatan : $laporan->rencanaKegiatan->nama_kegiatan }}">
                                            {{ $laporan->isDarurat() ? $laporan->judul_kegiatan : $laporan->rencanaKegiatan->nama_kegiatan }}
                                        </div>
                                        @if ($laporan->isDarurat())
                                            <span style="background:#e2e8f0; color:#334155; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;"><i class="fas fa-bolt mr-1"></i> Laporan Langsung</span>
                                        @else
                                            <small class="text-muted d-block text-truncate" style="max-width: 250px;" title="{{ $laporan->rencanaKegiatan->getJenisKegiatanLabel() }}">{{ $laporan->rencanaKegiatan->getJenisKegiatanLabel() }}</small>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $pjName = $laporan->rencanaKegiatan ? $laporan->rencanaKegiatan->penanggung_jawab : ($laporan->user->name ?? '-');
                                            @endphp
                                            <div class="text-truncate" style="max-width: 150px;" title="{{ $pjName }}">
                                                {{ $pjName }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-wrap" style="max-width: 200px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $laporan->isDarurat() ? $laporan->lokasi_kegiatan : ($laporan->rencanaKegiatan->desa ?: '-') }}">
                                            {{ $laporan->isDarurat() ? $laporan->lokasi_kegiatan : ($laporan->rencanaKegiatan->desa ?: '-') }}
                                        </div>
                                    </td>
                                    <td class="align-middle">{{ $laporan->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="align-middle text-center">
                                        @php
                                            $statusStyles = [
                                                'draft'    => 'background:#f1f3f5; color:#495057;',
                                                'diajukan' => 'background:#e8f0fe; color:#1a56db;',
                                                'revisi'   => 'background:#fff3cd; color:#856404;',
                                                'final'    => 'background:#def7ec; color:#03543f;',
                                            ];
                                            $badgeStyle = $statusStyles[$laporan->status] ?? 'background:#f1f3f5; color:#495057;';
                                        @endphp
                                        <span style="{{ $badgeStyle }} padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">{{ ucfirst($laporan->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination jika diperlukan -->
                @if (method_exists($laporans, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $laporans->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada laporan kegiatan</h5>
                    <p class="text-muted">
                        @can('create', \App\Models\LaporanKegiatan::class)
                            Buat laporan kegiatan untuk rencana kegiatan yang telah selesai.
                        @else
                            Hubungi admin untuk membuat laporan kegiatan.
                        @endcan
                    </p>
                    @can('create', \App\Models\LaporanKegiatan::class)
                        <a href="{{ route('rencana_kegiatan.index') }}" class="btn bg-navy text-white">
                            <i class="fas fa-plus mr-1"></i>Buat Laporan Baru
                        </a>
                    @endcan
                </div>
            @endif
        </div>

    </div>
@endsection
