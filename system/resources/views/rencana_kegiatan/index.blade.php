@extends('layouts.adminlte')

@section('content_title', 'Daftar Rencana Kegiatan')

@section('content')
    <div class="card shadow-sm elevation-1 text-sm">
        <div class="card-header bg-white">
            <h6 class="card-title fw-bold text-dark mt-1 mb-0" style="font-size: 0.95rem;">Data Rencana Kegiatan</h6>
            <div class="card-tools">
                @can('create', \App\Models\RencanaKegiatan::class)
                    <a href="{{ route('rencana_kegiatan.create') }}" class="btn bg-navy text-white btn-sm shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Rencana Kegiatan
                    </a>
                @endcan
            </div>
        </div>

            <div class="card-body border-bottom bg-light py-2 px-3">
                <form action="{{ route('rencana_kegiatan.index') }}" method="GET" class="mb-0 no-loader" id="filter-form">
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
                                @if(auth()->user()->role->role_name !== 'admin')
                                <option value="draft" {{ (request('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                                @endif
                                <option value="diajukan" {{ (request('status') == 'diajukan') ? 'selected' : '' }}>Diajukan</option>
                                @if(auth()->user()->role->role_name === 'admin')
                                    <option value="disetujui" {{ (request('status') == 'disetujui') ? 'selected' : '' }}>Disetujui</option>
                                @endif
                                <option value="revisi" {{ (request('status') == 'revisi') ? 'selected' : '' }}>Revisi</option>
                                <option value="ditolak" {{ (request('status') == 'ditolak') ? 'selected' : '' }}>Ditolak</option>
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
                            @if(request()->hasAny(['bulan','tahun','status','user_id','filter_status']))
                                <a href="{{ route('rencana_kegiatan.index') }}" class="btn bg-navy text-white btn-sm flex-shrink-0 shadow-sm" title="Reset Filter">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </a>
                            @endif
                        </div>
                        
                        <!-- Mobile Layout -->
                        <div class="d-lg-none">
                            <div class="row g-2">
                                <div class="col-6 mt-2">
                                    <select name="bulan" class="form-control form-control-sm rounded">
                                        <option value="">Semua Bulan</option>
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
                                        @if(auth()->user()->role->role_name !== 'admin')
                                        <option value="draft" {{ (request('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                                        @endif
                                        <option value="diajukan" {{ (request('status') == 'diajukan') ? 'selected' : '' }}>Diajukan</option>
                                        @if(auth()->user()->role->role_name === 'admin')
                                            <option value="disetujui" {{ (request('status') == 'disetujui') ? 'selected' : '' }}>Disetujui</option>
                                        @endif
                                        <option value="revisi" {{ (request('status') == 'revisi') ? 'selected' : '' }}>Revisi</option>
                                        <option value="ditolak" {{ (request('status') == 'ditolak') ? 'selected' : '' }}>Ditolak</option>
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
                                <div class="col-12 mt-2 d-flex" style="gap:8px;">
                                    <button type="submit" class="btn bg-navy text-white btn-sm flex-grow-1 shadow-sm">
                                        <i class="fas fa-filter mr-1"></i> Filter
                                    </button>
                                    @if(request()->hasAny(['bulan','tahun','status','user_id','filter_status']))
                                        <a href="{{ route('rencana_kegiatan.index') }}" class="btn bg-navy text-white btn-sm flex-grow-1 shadow-sm text-center">
                                            <i class="fas fa-undo mr-1"></i> Reset
                                        </a>
                                    @endif
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
            </div>
        <div class="card-body">
            <table class="table table-hover table-borderless align-middle w-100" id="table2">
                <thead class="bg-navy text-white text-nowrap">
                    <tr>
                        <th class="align-middle text-center" style="width: 50px;">No</th>
                        <th class="align-middle text-center" style="width: 100px;">Aksi</th>
                        <th class="align-middle" style="width: 35%;">Judul Kegiatan</th>
                        <th class="align-middle text-nowrap" style="width: 20%;">Tanggal Pelaksanaan</th>
                        <th class="align-middle" style="width: 20%;">Penanggung Jawab</th>
                        <th class="align-middle text-center" style="width: 120px;">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($rencanaKegiatans as $i => $rencanaKegiatan)
                            <tr class="border-bottom">
                                <td class="text-center text-muted">{{ $i + 1 }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm bg-navy text-white shadow-sm rounded"
                                        style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                        href="{{ route('rencana_kegiatan.show', $rencanaKegiatan) }}"
                                        title="Lihat Detail">
                                        <i class="fas fa-info" style="font-size: 12px;"></i>
                                    </a>
                                </td>
                                <td class="py-3">
                                    <a href="{{ route('rencana_kegiatan.show', $rencanaKegiatan) }}" class="text-dark text-wrap d-block" style="max-width: 300px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-decoration: none;" title="{{ $rencanaKegiatan->nama_kegiatan ?? ($rencanaKegiatan->judul ?? '-') }}">
                                        <strong>{{ $rencanaKegiatan->nama_kegiatan ?? ($rencanaKegiatan->judul ?? '-') }}</strong>
                                    </a>
                                    <small class="text-muted text-truncate d-block" style="max-width: 250px;" title="{{ $rencanaKegiatan->getJenisKegiatanLabel() }}"><i class="fas fa-tags mr-1"></i> {{ $rencanaKegiatan->getJenisKegiatanLabel() }}</small>
                                </td>
                                <td>
                                    @if ($rencanaKegiatan->tanggal_mulai)
                                        <span class="text-dark"><i class="far fa-calendar-alt mr-1 text-muted"></i> {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                                        @if ($rencanaKegiatan->tanggal_selesai && $rencanaKegiatan->tanggal_selesai != $rencanaKegiatan->tanggal_mulai)
                                            <br><small class="text-muted">s/d {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_selesai)->translatedFormat('d M Y') }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($rencanaKegiatan->penanggung_jawab)
                                            <span class="text-dark text-truncate" style="max-width: 170px;" title="{{ $rencanaKegiatan->penanggung_jawab }}">{{ $rencanaKegiatan->penanggung_jawab }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    {!! $rencanaKegiatan->status_badge !!}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                    Tidak ada data rencana kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
            </table>
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
@endsection
