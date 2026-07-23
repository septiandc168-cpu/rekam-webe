@extends('layouts.adminlte')

@section('content_title', 'Daftar Rencana Kegiatan')

@section('content')
    <div class="card text-sm">
        <div class="p-2 d-flex align-items-center flex-wrap flex-lg-nowrap border">
            <h4 class="h5 mb-0 d-flex align-items-center {{ auth()->user()->role->role_name === 'admin' ? 'w-100 w-lg-auto mb-2 mb-lg-0' : '' }}">
                Data Rencana Kegiatan
            </h4>

            <div class="d-flex align-items-center flex-nowrap flex-lg-wrap ml-auto">
                <!-- Export Excel Form (Supervisor only) -->
                @if(auth()->user()->role->role_name === 'admin')
                    <form action="{{ route('rencana_kegiatan.export.excel') }}" method="GET" class="mb-2 mb-lg-0 mr-lg-2 flex-grow-1" id="export-form">
                        @csrf
                        <div class="export-form-container">
                            <!-- Desktop Layout -->
                            <div class="d-none d-lg-flex input-group input-group-sm">
                                <select name="bulan" class="form-control mr-2" required style="min-width: 120px;">
                                    <option value="">Pilih Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ (request('bulan') == $i) ? 'selected' : '' }}>
                                            {{ ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$i - 1] }}
                                        </option>
                                    @endfor
                                </select>
                                <input type="number" name="tahun" class="form-control mr-2" placeholder="Tahun" 
                                       value="{{ request('tahun', date('Y')) }}" min="2020" max="2030" required style="min-width: 80px;">
                                <select name="status" class="form-control mr-2" style="min-width: 130px;">
                                    <option value="">Semua Status</option>
                                    <option value="diajukan" {{ (request('status') == 'diajukan') ? 'selected' : '' }}>Diajukan</option>
                                    <option value="disetujui" {{ (request('status') == 'disetujui') ? 'selected' : '' }}>Disetujui</option>
                                    <option value="ditolak" {{ (request('status') == 'ditolak') ? 'selected' : '' }}>Ditolak</option>
                                    <option value="selesai" {{ (request('status') == 'selesai') ? 'selected' : '' }}>Selesai</option>
                                </select>
                                <select name="user_id" class="form-control mr-2" style="min-width: 150px;">
                                    <option value="">Semua User</option>
                                    @if(isset($users))
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ (request('user_id') == $user->id) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <button type="submit" class="btn bg-navy text-white btn-sm flex-shrink-0 mr-2" title="Export Excel">
                                    <i class="fas fa-file-excel mr-1"></i> Export
                                </button>
                            </div>
                            
                            <!-- Mobile Layout -->
                            <div class="d-lg-none">
                                <div class="row g-2">
                                    <div class="col-6 mt-2">
                                        <select name="bulan" class="form-control form-control-sm" required>
                                            <option value="">Bulan</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ (request('bulan') == $i) ? 'selected' : '' }}>
                                                    {{ ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$i - 1] }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <input type="number" name="tahun" class="form-control form-control-sm" placeholder="Tahun" 
                                               value="{{ request('tahun', date('Y')) }}" min="2020" max="2030" required>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="">Status</option>
                                            <option value="diajukan" {{ (request('status') == 'diajukan') ? 'selected' : '' }}>Diajukan</option>
                                            <option value="disetujui" {{ (request('status') == 'disetujui') ? 'selected' : '' }}>Disetujui</option>
                                            <option value="ditolak" {{ (request('status') == 'ditolak') ? 'selected' : '' }}>Ditolak</option>
                                            <option value="selesai" {{ (request('status') == 'selesai') ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-6 mt-2">
                                        <select name="user_id" class="form-control form-control-sm">
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
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn bg-navy text-white btn-sm btn-block w-100">
                                            <i class="fas fa-file-excel mr-1"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('export-form');
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
                            
                            // Remove name attribute from mobile fields to prevent form submission conflicts
                            mobileFields.forEach(field => {
                                field.removeAttribute('name');
                            });
                        });
                    </script>
                @endif

                @can('create', \App\Models\RencanaKegiatan::class)
                    <div class="mb-0 mb-lg-0 ml-auto">
                        <a href="{{ route('rencana_kegiatan.create') }}" class="btn bg-navy text-white btn-sm"
                            style="height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah
                        </a>
                    </div>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="table2">
                    <thead class="bg-navy text-white">
                        <tr>
                            <th class="align-middle" style=" padding-left: 17px; height: 35px; width: 35px">No</th>
                            <th class="align-middle" style=" padding-left: 17px; height: 35px; width: 110px">Aksi</th>
                            <th class="align-middle" style=" padding-left: 18px; height: 35px;">Nama Kegiatan</th>
                            <th class="align-middle text-nowrap" style=" padding-left: 18px; height: 35px; width: 165px">Penanggung Jawab</th>
                            <!-- <th class="align-middle" style=" padding-left: 18px; height: 35px;">Desa</th> -->
                            <th class="align-middle" style=" padding-left: 18px; height: 35px;">Tanggal</th>
                            <th class="align-middle" style=" padding-left: 18px; height: 35px; width: 120px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rencanaKegiatans as $i => $rencanaKegiatan)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn bg-navy text-white btn-sm"
                                            style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                            href="{{ route('rencana_kegiatan.show', $rencanaKegiatan) }}"
                                            title="Detail Rencana Kegiatan">
                                            <i class="fas fa-info"></i>
                                        </a>

                                        {{-- Tombol Laporan Kegiatan --}}
                                        @if ($rencanaKegiatan->status === \App\Models\RencanaKegiatan::STATUS_DISETUJUI)
                                            @if (!$rencanaKegiatan->hasLaporan())
                                                @can('create', \App\Models\LaporanKegiatan::class)
                                                    <a class="btn btn-success btn-sm"
                                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                        href="{{ route('laporan_kegiatan.create', ['rencana_kegiatan_id' => $rencanaKegiatan->uuid]) }}"
                                                        title="Buat Laporan">
                                                        <i class="fas fa-file-medical"></i>
                                                    </a>
                                                @endcan
                                            @endif
                                        @elseif ($rencanaKegiatan->status === \App\Models\RencanaKegiatan::STATUS_SELESAI)
                                            @if ($rencanaKegiatan->hasLaporan() && $rencanaKegiatan->laporanKegiatan)
                                                <a class="btn btn-sm btn-outline-info"
                                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                    href="{{ route('laporan_kegiatan.show', $rencanaKegiatan->laporanKegiatan) }}"
                                                    title="Lihat Laporan">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            @endif
                                        @endif

                                        @can('update', $rencanaKegiatan)
                                            @if(auth()->user()->role->role_name !== 'admin')
                                                <a class="btn btn-sm btn-outline-warning"
                                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                    href="{{ route('rencana_kegiatan.edit', $rencanaKegiatan) }}"
                                                    title="Edit Rencana Kegiatan">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('delete', $rencanaKegiatan)
                                            <a class="btn btn-danger btn-sm"
                                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                href="{{ route('rencana_kegiatan.destroy', $rencanaKegiatan) }}"
                                                title="Hapus Rencana Kegiatan" data-confirm-delete="true">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                                <td>{{ $rencanaKegiatan->nama_kegiatan ?? ($rencanaKegiatan->judul ?? '-') }}
                                    <br>
                                    <small
                                        class="text-muted">{{ $rencanaKegiatan->getJenisKegiatanLabel() }}</small>
                                </td>
                                <td>{{ $rencanaKegiatan->penanggung_jawab ?? '-' }}</td>
                                <!-- <td>{{ $rencanaKegiatan->desa ?? '-' }}</td> -->
                                <td class="text-nowrap">
                                    @if ($rencanaKegiatan->tanggal_mulai)
                                        {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_mulai)->format('d/m/Y') }}
                                        @if ($rencanaKegiatan->tanggal_selesai)
                                            -
                                            {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_selesai)->format('d/m/Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusStyles = [
                                            'diajukan'            => 'background:#e8f0fe; color:#1a56db;',
                                            'disetujui'           => 'background:#def7ec; color:#03543f;',
                                            'revisi'              => 'background:#fff3cd; color:#856404;',
                                            'ditolak'             => 'background:#fde8e8; color:#c81e1e;',
                                            'menunggu_verifikasi' => 'background:#e8f0fe; color:#1a56db;',
                                            'selesai'             => 'background:#e2e8f0; color:#334155;',
                                        ];
                                        $badgeStyle = $statusStyles[$rencanaKegiatan->status] ?? 'background:#f1f3f5; color:#495057;';
                                        $statusLabel = str_replace('_', ' ', ucfirst($rencanaKegiatan->status));
                                    @endphp
                                    <span style="{{ $badgeStyle }} padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">{{ $statusLabel }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data rencana kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        /* .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        } */

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }

        /* Export Form Responsive Styles */
        .export-form-container {
            width: 100%;
        }

        /* Desktop: Ensure proper spacing and prevent text truncation */
        @media (min-width: 992px) {
            .export-form-container .input-group {
                flex-wrap: nowrap;
                max-width: 100%;
            }
            
            .export-form-container .form-control {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            .export-form-container select.form-control {
                padding-right: 25px; /* Space for dropdown arrow */
                appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right 8px center;
                background-size: 12px;
            }
            
            .export-form-container select.form-control:focus {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230ea5e9' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
            }
        }

        /* Mobile: Optimize for touch and smaller screens */
        @media (max-width: 991.98px) {
            .export-form-container .form-control,
            .export-form-container .form-control-sm {
                font-size: 14px;
                padding: 8px 12px;
                min-height: 38px;
            }
            
            .export-form-container select.form-control,
            .export-form-container select.form-control-sm {
                padding-right: 30px; /* More space for dropdown arrow on mobile */
                appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right 10px center;
                background-size: 14px;
            }
            
            .export-form-container .btn {
                min-height: 38px;
                font-size: 14px;
            }
        }

        /* Prevent text truncation in dropdown options */
        .export-form-container select option {
            white-space: nowrap;
            padding: 8px 12px;
            font-size: 14px;
        }

        /* Ensure proper spacing on very small screens */
        @media (max-width: 576px) {
            .export-form-container .row.g-2 > .col-6 {
                padding-left: 8px;
                padding-right: 8px;
            }
            
            .export-form-container .form-control,
            .export-form-container .form-control-sm {
                font-size: 13px;
                min-height: 36px;
            }
        }

        /* Header responsive adjustments */
        @media (max-width: 768px) {
            .d-flex.align-items-center.justify-content-between {
                flex-direction: column;
                align-items: stretch !important;
            }
            
            .d-flex.align-items-center.justify-content-between > div:first-child {
                margin-bottom: 1rem;
            }
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for filter_status parameter and set it in the select
            const urlParams = new URLSearchParams(window.location.search);
            const filterStatus = urlParams.get('filter_status') || localStorage.getItem('filterStatus');
            
            if (filterStatus && filterStatus !== 'all') {
                // Find the status select element and set its value
                const statusSelect = document.querySelector('select[name="status"]');
                if (statusSelect) {
                    statusSelect.value = filterStatus;
                    // Trigger change event to apply filter
                    statusSelect.dispatchEvent(new Event('change'));
                }
            }
            
            // Clear the stored filter after applying
            localStorage.removeItem('filterStatus');
        });
    </script>

    {{-- Deletion is handled via SweetAlert using data-confirm-delete attribute. --}}

@endsection
