@extends('layouts.adminlte')

@section('content_title', 'Edit Rencana Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Form Rencana Kegiatan</h5>
        </div>

        <form id="rencana-kegiatan-form" action="{{ auth()->user()->role->role_name === 'supervisor' ? route('rencana_kegiatan.updateStatus', $rencana_kegiatan) : route('rencana_kegiatan.update', $rencana_kegiatan) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if (auth()->user()->role->role_name === 'supervisor')
                @method('POST')
            @else
                @method('PUT')
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if (auth()->user()->role->role_name === 'supervisor')
            <!-- Supervisor Form - Hanya Status dan Keterangan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i>
                        Ubah Status Rencana Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Nama Kegiatan</label>
                                <div class="form-control bg-light">{{ $rencana_kegiatan->nama_kegiatan }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required id="status-select">
                            <option value="">-- Pilih Status --</option>
                            @php
                                $currentStatus = old('status', $rencana_kegiatan->status ?? '');
                            @endphp
                            <option value="diajukan" {{ $currentStatus == 'diajukan' ? 'selected' : '' }}>
                                Diajukan
                            </option>
                            <option value="disetujui" {{ $currentStatus == 'disetujui' ? 'selected' : '' }}>
                                Disetujui
                            </option>
                            <option value="revisi" {{ $currentStatus == 'revisi' ? 'selected' : '' }}>
                                Revisi
                            </option>
                            <option value="ditolak" {{ $currentStatus == 'ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                            <option value="selesai" {{ $currentStatus == 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>
                        </select>
                        <small class="form-text text-muted">Pilih status kegiatan</small>
                    </div>
                        
                    <div class="mb-3 keterangan-status-container" id="keterangan-container" style="display: none;">
                        <label class="form-label" id="keterangan-label">Catatan</label>
                        <textarea name="keterangan_status" class="form-control" rows="3" id="keterangan-textarea"
                            placeholder="Tambahkan catatan..." id="keterangan-textarea">{{ old('keterangan_status', $rencana_kegiatan->keterangan_status) }}</textarea>
                        <small class="form-text text-muted" id="keterangan-help">Tambahkan catatan</small>
                    </div>
                </div>
            </div>
            <!-- Tombol Aksi -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary"
                            style="height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary"
                            style="height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-save mr-1"></i>Update Status
                        </button>
                    </div>
                </div>
            </div>
        </form>
            @else
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Lokasi Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Koordinat Lokasi</label>
                        <div class="input-group">
                            <input type="text" id="location_lat" name="lat" class="form-control"
                                placeholder="Latitude" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }} required value="{{ old('lat', $rencana_kegiatan->lat) }}">
                            <input type="text" id="location_lng" name="lng" class="form-control"
                                placeholder="Longitude" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }} required value="{{ old('lng', $rencana_kegiatan->lng) }}">
                            @if (auth()->user()->role->role_name !== 'supervisor')
                                <button type="button" id="use-location" class="btn btn-outline-secondary">Gunakan Lokasi
                                    Saya</button>
                            @endif
                        </div>
                        <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Pilih lokasi dengan mengklik pada peta atau gunakan lokasi Anda saat ini @endif</small>
                    </div>

                    <div class="mb-3" id="map-create"
                        style="width:100%; height:70vh; border:1px solid #ddd; border-radius:4px;"></div>

                    <div class="mb-3">
                        <label class="form-label">Desa / Wilayah</label>
                        <input type="text" name="desa" class="form-control" placeholder="Nama desa atau wilayah"
                            value="{{ old('desa', $rencana_kegiatan->desa) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }}>
                        <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Tuliskan nama desa atau wilayah lokasi kegiatan @endif</small>
                    </div>
                </div>
            </div>

            <!-- Informasi Dasar Kegiatan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi Dasar Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" class="form-control" placeholder="Nama kegiatan"
                                    value="{{ old('nama_kegiatan', $rencana_kegiatan->nama_kegiatan) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }} required>
                                <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Tuliskan nama kegiatan yang akan dilaksanakan @endif</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estimasi Jumlah Peserta</label>
                                <input type="number" name="estimasi_peserta" class="form-control" min="0"
                                    value="{{ old('estimasi_peserta', $rencana_kegiatan->estimasi_peserta) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }}>
                                <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Perkirakan jumlah peserta yang akan hadir @endif</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control"
                                        value="{{ old('tanggal_mulai', $rencana_kegiatan->tanggal_mulai ? \Carbon\Carbon::parse($rencana_kegiatan->tanggal_mulai)->format('Y-m-d') : null) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }}>
                                    <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Pilih tanggal mulai kegiatan @endif</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control"
                                        value="{{ old('tanggal_selesai', $rencana_kegiatan->tanggal_selesai ? \Carbon\Carbon::parse($rencana_kegiatan->tanggal_selesai)->format('Y-m-d') : null) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }}>
                                    <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Pilih tanggal selesai kegiatan @endif</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Mulai</label>
                                    <input type="time" name="waktu_mulai" class="form-control"
                                        value="{{ old('waktu_mulai', $rencana_kegiatan->waktu_mulai ? \Carbon\Carbon::parse($rencana_kegiatan->waktu_mulai)->format('H:i') : null) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }}>
                                    <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Pilih waktu mulai kegiatan @endif</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Selesai</label>
                                    <input type="time" name="waktu_selesai" class="form-control"
                                        value="{{ old('waktu_selesai', $rencana_kegiatan->waktu_selesai ? \Carbon\Carbon::parse($rencana_kegiatan->waktu_selesai)->format('H:i') : null) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }}>
                                    <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Pilih waktu selesai kegiatan @endif</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Penanggung Jawab</label>
                                <input type="text" name="penanggung_jawab" class="form-control"
                                    placeholder="Nama Penanggung Jawab"
                                    value="{{ old('penanggung_jawab', $rencana_kegiatan->penanggung_jawab) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }}>
                                <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Tuliskan nama penanggung jawab kegiatan @endif</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kelompok / Komunitas Pelaksana</label>
                                <input type="text" name="kelompok" class="form-control" placeholder="Nama kelompok"
                                    value="{{ old('kelompok', $rencana_kegiatan->kelompok) }}" {{ auth()->user()->role->role_name === 'supervisor' ? 'readonly' : '' }}>
                                <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Tuliskan nama kelompok atau komunitas pelaksana @endif</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                @if (auth()->user()->role->role_name === 'supervisor')
                                    <div class="form-control bg-light">
                                        @if($rencana_kegiatan->jenis_kegiatan === 'lainnya' && $rencana_kegiatan->jenis_kegiatan_lainnya)
                                            {{ $rencana_kegiatan->jenis_kegiatan_lainnya }}
                                        @else
                                            {{ \App\Models\RencanaKegiatan::getJenisKegiatanOptions()[$rencana_kegiatan->jenis_kegiatan] ?? $rencana_kegiatan->jenis_kegiatan ?? '-' }}
                                        @endif
                                    </div>
                                    <input type="hidden" name="jenis_kegiatan" value="{{ $rencana_kegiatan->jenis_kegiatan ?? '' }}">
                                    @if($rencana_kegiatan->jenis_kegiatan === 'lainnya')
                                        <input type="hidden" name="jenis_kegiatan_lainnya" value="{{ $rencana_kegiatan->jenis_kegiatan_lainnya ?? '' }}">
                                    @endif
                                @else
                                <select name="jenis_kegiatan" class="form-select" required>
                                    <option value="">-- Pilih Jenis Kegiatan --</option>
                                    @foreach(\App\Models\RencanaKegiatan::getJenisKegiatanOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('jenis_kegiatan') == $value ? 'selected' : ($rencana_kegiatan->jenis_kegiatan == $value ? 'selected' : '') }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @endif
                                <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else Pilih jenis kegiatan yang sesuai @endif</small>
                            </div>

                            @if (auth()->user()->role->role_name !== 'supervisor')
                            <div class="mb-3" id="jenis_kegiatan_lainnya_row" @if(old('jenis_kegiatan') !== 'lainnya' && $rencana_kegiatan->jenis_kegiatan !== 'lainnya') style="display: none;" @endif>
                                <label class="form-label">Deskripsi Jenis Kegiatan Lainnya <span class="text-danger">*</span></label>
                                <input type="text" name="jenis_kegiatan_lainnya" class="form-control" 
                                    placeholder="Jelaskan jenis kegiatan lainnya..."
                                    value="{{ old('jenis_kegiatan_lainnya', $rencana_kegiatan->jenis_kegiatan_lainnya ?? '') }}"
                                    @if(old('jenis_kegiatan') === 'lainnya' || $rencana_kegiatan->jenis_kegiatan === 'lainnya') required @endif>
                                <small class="form-text text-muted">Jelaskan jenis kegiatan lainnya</small>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                @if (auth()->user()->role->role_name === 'supervisor')
                                    <div class="form-control bg-light" style="min-height: 100px;">{!! $rencana_kegiatan->deskripsi ?? '-' !!}</div>
                                    <input type="hidden" name="deskripsi" value="{{ $rencana_kegiatan->deskripsi ?? '' }}">
                                @else
                                    <textarea name="deskripsi" class="form-control" id="summernote-deskripsi" rows="3" placeholder="Deskripsikan kegiatan yang akan dilaksanakan...">{!! old('deskripsi', $rencana_kegiatan->deskripsi) !!}</textarea>
                                @endif
                                <small class="form-text text-muted">
                                Jelaskan secara singkat gambaran kegiatan yang akan dilaksanakan.
                                <br>
                                Tuliskan latar belakang kegiatan, bentuk kegiatan yang akan dilakukan, serta siapa saja yang akan terlibat.
                                <br>
                                Deskripsi dapat ditulis dalam 1–3 paragraf singkat agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Kegiatan pelatihan pembuatan pupuk organik bagi masyarakat desa untuk meningkatkan pemanfaatan limbah rumah tangga menjadi produk yang bermanfaat bagi pertanian.</li>
                                <li>Kegiatan identifikasi hama tanaman yang melibatkan masyarakat dan kelompok tani untuk meningkatkan pemahaman dalam pengendalian hama secara alami.</li>
                                </ul>
                                </small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Tujuan</label>
                                @if (auth()->user()->role->role_name === 'supervisor')
                                    <div class="form-control bg-light" style="min-height: 80px;">{!! $rencana_kegiatan->tujuan ?? '-' !!}</div>
                                    <input type="hidden" name="tujuan" value="{{ $rencana_kegiatan->tujuan ?? '' }}">
                                @else
                                    <textarea name="tujuan" class="form-control" id="summernote-tujuan" rows="2" placeholder="Tuliskan tujuan kegiatan yang akan dilaksanakan...">{!! old('tujuan', $rencana_kegiatan->tujuan) !!}</textarea>
                                @endif
                                <small class="form-text text-muted">
                                Tuliskan tujuan utama dari kegiatan yang akan dilaksanakan.
                                <br>
                                Tujuan dapat berupa peningkatan pengetahuan, keterampilan, kesadaran, atau pencapaian tertentu yang diharapkan dari kegiatan ini.
                                <br>
                                Tuliskan secara singkat dan jelas dalam 1–2 kalimat.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Meningkatkan pemahaman masyarakat tentang pengelolaan limbah organik menjadi pupuk yang bermanfaat.</li>
                                <li>Meningkatkan keterampilan peserta dalam mengidentifikasi hama tanaman secara mandiri.</li>
                                <li>Mendorong partisipasi masyarakat dalam kegiatan pelestarian lingkungan.</li>
                                </ul>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status dan Detail Kebutuhan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-1"></i>
                        Status dan Detail Kebutuhan Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                @if (auth()->user()->role->role_name === 'supervisor')
                                    <select name="status" class="form-select" required>
                                        <option value="">-- Pilih Status --</option>
                                        @php
                                            $currentStatus = old('status', $rencana_kegiatan->status ?? '');
                                        @endphp
                                        <option value="diajukan" {{ $currentStatus == 'diajukan' ? 'selected' : '' }}>
                                            Diajukan
                                        </option>
                                        <option value="disetujui" {{ $currentStatus == 'disetujui' ? 'selected' : '' }}>
                                            Disetujui
                                        </option>
                                        <option value="revisi" {{ $currentStatus == 'revisi' ? 'selected' : '' }}>
                                            Revisi
                                        </option>
                                        <option value="ditolak" {{ $currentStatus == 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
                                        </option>
                                        <option value="selesai" {{ $currentStatus == 'selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>
                                    </select>
                                @else
                                    <input type="hidden" name="status"
                                        value="{{ old('status', $rencana_kegiatan->status ?? '') }}">
                                    <div class="form-control bg-light" readonly>
                                        {{ \App\Models\RencanaKegiatan::getStatusOptions()[old('status', $rencana_kegiatan->status ?? 'diajukan')] ?? 'Diajukan' }}
                                    </div>
                                @endif
                                <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') Pilih status kegiatan @else Status kegiatan hanya bisa diubah oleh admin @endif</small>
                            </div>

                            @if (auth()->user()->role->role_name === 'supervisor')
                                @php
                                    $currentStatus = old('status', $rencana_kegiatan->status ?? '');
                                    $showKeterangan = in_array($currentStatus, ['disetujui', 'revisi', 'ditolak', 'selesai']);
                                @endphp
                                <div class="keterangan-status-container"
                                    style="{{ $showKeterangan ? 'display: block;' : 'display: none;' }}">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            {{ $currentStatus == 'disetujui' ? 'Catatan Persetujuan' : ($currentStatus == 'ditolak' ? 'Catatan Penolakan' : ($currentStatus == 'revisi' ? 'Catatan Revisi' : 'Catatan Penyelesaian')) }}
                                        </label>
                                        <textarea name="keterangan_status" class="form-control" rows="3"
                                            placeholder="{{ $currentStatus == 'disetujui' ? 'Tambahkan catatan persetujuan...' : ($currentStatus == 'ditolak' ? 'Jelaskan catatan penolakan...' : ($currentStatus == 'revisi' ? 'Jelaskan catatan revisi...' : 'Tambahkan catatan penyelesaian kegiatan...')) }}"
                                            {{ $showKeterangan ? 'required' : '' }}>{{ old('keterangan_status', $rencana_kegiatan->keterangan_status) }}</textarea>
                                        <small class="form-text text-muted">@if (auth()->user()->role->role_name === 'supervisor') @else {{ $currentStatus == 'disetujui' ? 'Tambahkan catatan persetujuan kegiatan' : ($currentStatus == 'ditolak' ? 'Jelaskan catatan penolakan kegiatan' : ($currentStatus == 'revisi' ? 'Jelaskan catatan revisi kegiatan' : 'Tambahkan catatan penyelesaian kegiatan')) }} @endif</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Rincian Kebutuhan</label>
                                @if (auth()->user()->role->role_name === 'supervisor')
                                    <div class="form-control bg-light" style="min-height: 100px;">{!! $rencana_kegiatan->rincian_kebutuhan ?? '-' !!}</div>
                                    <input type="hidden" name="rincian_kebutuhan" value="{{ $rencana_kegiatan->rincian_kebutuhan ?? '' }}">
                                @else
                                    <textarea type="text" name="rincian_kebutuhan" class="form-control" id="summernote">{!! old('rincian_kebutuhan', $rencana_kegiatan->rincian_kebutuhan ?? '') !!}</textarea>
                                @endif
                                <small class="form-text text-muted">
                                Tuliskan kebutuhan yang diperlukan untuk mendukung pelaksanaan kegiatan beserta perkiraan biayanya.
                                <br>
                                Kebutuhan dapat berupa perlengkapan, bahan kegiatan, konsumsi, transportasi, atau kebutuhan lainnya.
                                <br>
                                Tuliskan secara rinci agar memudahkan perencanaan anggaran kegiatan.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Konsumsi peserta (30 orang) – Rp450.000</li>
                                <li>Bahan praktik pembuatan pupuk organik – Rp300.000</li>
                                <li>Alat tulis dan kertas – Rp100.000</li>
                                <li>Transportasi tim pelaksana – Rp200.000</li>
                                </ul>
                                </small>
                            </div>
                        {{-- ANGGARAN KEGIATAN --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Unggah Anggaran Kegiatan <span class="text-danger">*</span></label>
                                    <input type="file" name="anggaran_kegiatan" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                                    @error('anggaran_kegiatan')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Unggah dokumen anggaran kegiatan<br>
                                    Ukuran maksimal 5MB<br>
                                    Format: PDF, DOC, DOCX, XLS, XLSX</small>
                                </div>

                                {{-- ANGGARAN KEGIATAN LAMA --}}
                                @if (!empty($rencana_kegiatan->anggaran_kegiatan))
                                    @php
                                        // Handle format string atau array
                                        $anggaranFile = $rencana_kegiatan->anggaran_kegiatan;
                                        $anggaranPath = is_array($anggaranFile) ? ($anggaranFile['path'] ?? $anggaranFile) : $anggaranFile;
                                        $anggaranName = is_array($anggaranFile) ? ($anggaranFile['original_name'] ?? basename($anggaranPath)) : basename($anggaranPath);
                                    @endphp
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Anggaran Kegiatan Saat Ini</label>
                                        <div class="border rounded p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-excel text-success me-2"></i>
                                                <div class="flex-grow-1">
                                                    <small class="text-muted">{{ $anggaranName }}</small>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="remove_anggaran_kegiatan" value="1"
                                                        id="remove_anggaran_kegiatan">
                                                    <label class="form-check-label"
                                                        for="remove_anggaran_kegiatan">
                                                        <small>Hapus</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Centang untuk menghapus anggaran kegiatan saat ini</small>
                                    </div>
                                @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-1"></i>
                        Media Pendukung Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    @if (auth()->user()->role->role_name === 'admin')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Unggah Media Publikasi</label>
                                    <input type="file" name="foto[]" class="form-control" accept="image/*" multiple>
                                    @error('foto')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Unggah foto media publikasi kegiatan (banner, poster, flyer dll)<br>
                                    Maksimal 5 foto dengan ukuran maksimal 2MB per foto<br>
                                    Format: JPG, JPEG, PNG</small>
                                </div>

                                {{-- FOTO LAMA --}}
                                @if (!empty($rencana_kegiatan->foto))
                                    @php
                                        // Decode JSON string to array if it's a string
$fotos = is_string($rencana_kegiatan->foto)
    ? json_decode($rencana_kegiatan->foto, true)
    : $rencana_kegiatan->foto;
// Ensure it's an array
                                        if (!is_array($fotos)) {
                                            $fotos = [];
                                        }
                                    @endphp
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Media Publikasi Saat Ini</label>
                                        <div class="row">
                                            @foreach ($fotos as $index => $foto)
                                                @if ($foto)
                                                    @php
                                                        // Handle format baru (array dengan path dan original_name)
                                                        if (is_array($foto)) {
                                                            $fotoPath = $foto['path'];
                                                            $fotoName = $foto['original_name'];
                                                        } else {
                                                            // Handle format lama (string path)
                                                            $fotoPath = $foto;
                                                            $fotoName = 'Foto ' . ($index + 1);
                                                        }
                                                    @endphp
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card">
                                                            <img src="{{ asset('public/storage/app/' . $fotoPath) }}"
                                                                class="card-img-top"
                                                                style="height: 150px; object-fit: cover; width: 100%;"
                                                                alt="{{ $fotoName }}">

                                                            <div class="card-body p-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="remove_foto[]" value="{{ $fotoPath }}"
                                                                        id="remove_foto_{{ $index }}">
                                                                    <label class="form-check-label"
                                                                        for="remove_foto_{{ $index }}">
                                                                        <small>Hapus</small>
                                                                    </label>
                                                                </div>
                                                                <small class="text-muted">{{ $fotoName }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <small class="form-text text-muted">Centang gambar yang ingin dihapus</small>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Unggah Dokumen Pendukung Kegiatan</label>
                                    <input type="file" name="dokumen[]" class="form-control" accept=".pdf,.doc,.docx" multiple>
                                    @error('dokumen')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Unggah dokumen pendukung kegiatan (undangan, absensi, dll)<br>
                                    Maksimal 5 dokumen dengan ukuran maksimal 5MB per dokumen<br>
                                    Format: PDF, DOC, DOCX</small>
                                </div>

                                {{-- DOKUMEN LAMA --}}
                                @if (!empty($rencana_kegiatan->dokumen))
                                    @php
                                        // Decode JSON string to array if it's a string
$dokumens = is_string($rencana_kegiatan->dokumen)
    ? json_decode($rencana_kegiatan->dokumen, true)
    : $rencana_kegiatan->dokumen;
// Ensure it's an array
                                        if (!is_array($dokumens)) {
                                            $dokumens = [];
                                        }
                                    @endphp
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Dokumen Pendukung Kegiatan Saat Ini</label>
                                        <div class="row">
                                            @foreach ($dokumens as $index => $file)
                                                @if ($file)
                                                    @php
                                                        // Handle format baru (array dengan path dan original_name)
                                                        if (is_array($file)) {
                                                            $filePath = $file['path'];
                                                            $fileName = $file['original_name'];
                                                        } else {
                                                            // Handle format lama (string path)
                                                            $filePath = $file;
                                                            $fileName = basename($file);
                                                        }
                                                    @endphp
                                                    <div class="col-md-4 mb-3">
                                                        <div class="card">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="fas fa-file-alt text-secondary me-2"></i>
                                                                    <small class="text-truncate">{{ $fileName }}</small>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="remove_dokumen[]" value="{{ $filePath }}"
                                                                        id="remove_dokumen_{{ $index }}">
                                                                    <label class="form-check-label"
                                                                        for="remove_dokumen_{{ $index }}">
                                                                        <small>Hapus</small>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <small class="form-text text-muted">Centang dokumen yang ingin dihapus</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Supervisor tidak bisa upload/hapus file -->
                        <div class="row">
                            @if (!empty($rencana_kegiatan->foto))
                                @php
                                    $fotos = is_string($rencana_kegiatan->foto)
                                        ? json_decode($rencana_kegiatan->foto, true)
                                        : $rencana_kegiatan->foto;
                                    if (!is_array($fotos)) {
                                        $fotos = [];
                                    }
                                @endphp
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Media Publikasi</label>
                                        <div class="row">
                                            @foreach ($fotos as $index => $foto)
                                                @if ($foto)
                                                    @php
                                                        if (is_array($foto)) {
                                                            $fotoPath = $foto['path'];
                                                            $fotoName = $foto['original_name'];
                                                        } else {
                                                            $fotoPath = $foto;
                                                            $fotoName = 'Foto ' . ($index + 1);
                                                        }
                                                    @endphp
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card">
                                                            <img src="{{ asset('public/storage/app/' . $fotoPath) }}"
                                                                class="card-img-top"
                                                                style="height: 150px; object-fit: cover; width: 100%;"
                                                                alt="{{ $fotoName }}">
                                                            <div class="card-body p-2">
                                                                <small class="text-muted">{{ $fotoName }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($rencana_kegiatan->dokumen))
                                @php
                                    $dokumens = is_string($rencana_kegiatan->dokumen)
                                        ? json_decode($rencana_kegiatan->dokumen, true)
                                        : $rencana_kegiatan->dokumen;
                                    if (!is_array($dokumens)) {
                                        $dokumens = [];
                                    }
                                @endphp
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Dokumen Pendukung Kegiatan</label>
                                        <div class="row">
                                            @foreach ($dokumens as $index => $file)
                                                @if ($file)
                                                    @php
                                                        if (is_array($file)) {
                                                            $filePath = $file['path'];
                                                            $fileName = $file['original_name'];
                                                        } else {
                                                            $filePath = $file;
                                                            $fileName = basename($file);
                                                        }
                                                    @endphp
                                                    <div class="col-md-4 mb-3">
                                                        <div class="card">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-file-alt text-secondary me-2"></i>
                                                                    <small class="text-truncate">{{ $fileName }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                        style="height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm"
                        style="height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-save mr-1"></i>Perbarui
                    </button>
                </div>
            </div>
    </div>
    </form>
    @endif

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #map-create {
                background: #f7fafc;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-resize textareas
                const textareas = document.querySelectorAll('textarea');
                textareas.forEach(textarea => {
                    textarea.addEventListener('input', function() {
                        this.style.height = 'auto';
                        this.style.height = this.scrollHeight + 'px';
                    });
                });

                // File validation untuk foto
                const fotoInput = document.querySelector('input[name="foto[]"]');
                const existingFotos = document.querySelectorAll('input[name="remove_foto[]"]');

                if (fotoInput) {
                    fotoInput.addEventListener('change', function() {
                        const files = this.files;
                        const existingCount = existingFotos.length;
                        const maxFiles = 5;
                        const maxSize = 4 * 1024 * 1024; // 4MB
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                        if (existingCount + files.length > maxFiles) {
                            alert(`Maksimal ${maxFiles} file foto. Saat ini ada ${existingCount} file.`);
                            this.value = '';
                            return;
                        }

                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];

                            if (file.size > maxSize) {
                                alert(`File ${file.name} terlalu besar. Maksimal 4MB per file.`);
                                this.value = '';
                                return;
                            }

                            if (!allowedTypes.includes(file.type)) {
                                alert(`File ${file.name} format tidak didukung. Gunakan format JPG atau PNG.`);
                                this.value = '';
                                return;
                            }
                        }
                    });
                }

                // File validation untuk dokumen
                const dokumenInput = document.querySelector('input[name="dokumen[]"]');
                const existingDokumens = document.querySelectorAll('input[name="remove_dokumen[]"]');

                if (dokumenInput) {
                    dokumenInput.addEventListener('change', function() {
                        const files = this.files;
                        const existingCount = existingDokumens.length;
                        const maxFiles = 5;
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        const allowedTypes = ['application/pdf', 'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        ];

                        if (existingCount + files.length > maxFiles) {
                            alert(`Maksimal ${maxFiles} file dokumen. Saat ini ada ${existingCount} file.`);
                            this.value = '';
                            return;
                        }

                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];

                            if (file.size > maxSize) {
                                alert(`File ${file.name} terlalu besar. Maksimal 5MB per file.`);
                                this.value = '';
                                return;
                            }

                            if (!allowedTypes.includes(file.type)) {
                                alert(`File ${file.name} format tidak didukung. Gunakan format PDF atau Word.`);
                                this.value = '';
                                return;
                            }
                        }
                    });
                }

                // Confirm delete
                const deleteBtn = document.querySelector('button[type="submit"]');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function(e) {
                        const removeFotos = document.querySelectorAll('input[name="remove_foto[]"]:checked');
                        const removeDokumens = document.querySelectorAll(
                            'input[name="remove_dokumen[]"]:checked');

                        let message = '';
                        if (removeFotos.length > 0) {
                            message += `${removeFotos.length} foto`;
                        }
                        if (removeDokumens.length > 0) {
                            if (message) message += ' dan ';
                            message += `${removeDokumens.length} dokumen`;
                        }

                        if (message) {
                            if (!confirm(`Anda akan menghapus ${message}. Lanjutkan?`)) {
                                e.preventDefault();
                            }
                        }
                    });
                }

                // Initialize map
                const initialLat = parseFloat('{{ $rencana_kegiatan->lat ?? -6.2 }}');
                const initialLng = parseFloat('{{ $rencana_kegiatan->lng ?? 106.816666 }}');
                const map = L.map('map-create').setView([initialLat, initialLng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // marker user pick
                let pickMarker = null;

                if (!isNaN(initialLat) && !isNaN(initialLng)) {
                    pickMarker = L.marker([initialLat, initialLng]).addTo(map);
                }

                @if (auth()->user()->role->role_name !== 'supervisor')
                // Enable map interaction for admin
                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    if (pickMarker) pickMarker.setLatLng(e.latlng);
                    else pickMarker = L.marker(e.latlng).addTo(map);
                    document.getElementById('location_lat').value = lat.toFixed(6);
                    document.getElementById('location_lng').value = lng.toFixed(6);
                });

                // geolocation button
                const useLocationBtn = document.getElementById('use-location');
                if (useLocationBtn) {
                    useLocationBtn.addEventListener('click', function() {
                        if (!navigator.geolocation) return alert('Geolocation tidak didukung pada browser ini');
                        useLocationBtn.disabled = true;
                        useLocationBtn.textContent = 'Mencari...';
                        navigator.geolocation.getCurrentPosition(function(pos) {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            const ll = L.latLng(lat, lng);
                            map.setView(ll, 15);
                            if (pickMarker) pickMarker.setLatLng(ll);
                            else pickMarker = L.marker(ll).addTo(map);
                            document.getElementById('location_lat').value = lat.toFixed(6);
                            document.getElementById('location_lng').value = lng.toFixed(6);
                            useLocationBtn.disabled = false;
                            useLocationBtn.textContent = 'Gunakan Lokasi Saya';
                        }, function(err) {
                            alert('Gagal: ' + err.message);
                            useLocationBtn.disabled = false;
                            useLocationBtn.textContent = 'Gunakan Lokasi Saya';
                        }, {
                            enableHighAccuracy: true
                        });
                    });
                }
                @else
                // Disable map interaction for supervisor - only show current location
                map.dragging.disable();
                map.touchZoom.disable();
                map.doubleClickZoom.disable();
                map.scrollWheelZoom.disable();
                map.boxZoom.disable();
                map.keyboard.disable();
                if (map.tap) map.tap.disable();
                @endif

                // ensure proper rendering
                setTimeout(function() {
                    map.invalidateSize();
                }, 250);
                // client-side date check with optional auto-swap
                const form = document.getElementById('rencana-kegiatan-form');
                form.addEventListener('submit', function(e) {
                    const startEl = document.querySelector('input[name="tanggal_mulai"]');
                    const endEl = document.querySelector('input[name="tanggal_selesai"]');
                    const s = startEl ? startEl.value : '';
                    const t = endEl ? endEl.value : '';
                    if (s && t) {
                        const sd = new Date(s);
                        const ed = new Date(t);
                        if (ed < sd) {
                            e.preventDefault();
                            if (confirm('Tanggal selesai lebih awal dari tanggal mulai. Tukar otomatis?')) {
                                // swap values and submit
                                startEl.value = t;
                                endEl.value = s;
                                form.submit();
                            } else {
                                alert('Silakan koreksi tanggal sebelum mengirim.');
                            }
                        }
                    }
                });
            });

            // Handle status change for supervisor - show/hide keterangan status
            @if (auth()->user()->role->role_name === 'supervisor')
            const statusSelect = document.querySelector('#status-select');
            const keteranganContainer = document.querySelector('#keterangan-container');
            const keteranganTextarea = document.querySelector('#keterangan-textarea');
            const keteranganLabel = document.querySelector('#keterangan-label');
            const keteranganHelp = document.querySelector('#keterangan-help');
            
            if (statusSelect && keteranganContainer) {
                statusSelect.addEventListener('change', function() {
                    const selectedStatus = this.value;
                    const showKeterangan = ['disetujui', 'revisi', 'ditolak', 'selesai'].includes(selectedStatus);
                    
                    // Show/hide container
                    keteranganContainer.style.display = showKeterangan ? 'block' : 'none';
                    
                    // Update label and placeholder based on status
                    if (showKeterangan) {
                        let labelText, placeholderText, helpText;
                        
                        if (selectedStatus === 'disetujui') {
                            labelText = 'Catatan Persetujuan';
                            placeholderText = 'Tambahkan catatan persetujuan...';
                            helpText = 'Tambahkan catatan persetujuan kegiatan';
                        } else if (selectedStatus === 'revisi') {
                            labelText = 'Catatan Revisi';
                            placeholderText = 'Jelaskan catatan revisi...';
                            helpText = 'Jelaskan catatan revisi kegiatan';
                        } else if (selectedStatus === 'ditolak') {
                            labelText = 'Catatan Penolakan';
                            placeholderText = 'Jelaskan catatan penolakan...';
                            helpText = 'Jelaskan catatan penolakan kegiatan';
                        } else if (selectedStatus === 'selesai') {
                            labelText = 'Catatan Penyelesaian';
                            placeholderText = 'Tambahkan catatan penyelesaian kegiatan...';
                            helpText = 'Tambahkan catatan penyelesaian kegiatan';
                        }
                        
                        keteranganLabel.textContent = labelText;
                        keteranganTextarea.placeholder = placeholderText;
                        keteranganTextarea.required = true;
                        keteranganHelp.textContent = helpText;
                    } else {
                        keteranganTextarea.required = false;
                        keteranganTextarea.value = '';
                    }
                });
                
                // Trigger change event on page load to set initial state
                statusSelect.dispatchEvent(new Event('change'));
            }
            @endif
        </script>
    @endpush

    @if (auth()->user()->role->role_name !== 'supervisor')
    <script>
        // Toggle jenis kegiatan lainnya field
        document.addEventListener('DOMContentLoaded', function() {
            const jenisKegiatanSelect = document.querySelector('select[name="jenis_kegiatan"]');
            if (jenisKegiatanSelect) {
                jenisKegiatanSelect.addEventListener('change', function() {
                    const jenisKegiatanLainnyaRow = document.getElementById('jenis_kegiatan_lainnya_row');
                    const jenisKegiatanLainnyaInput = document.querySelector('input[name="jenis_kegiatan_lainnya"]');
                    
                    if (this.value === 'lainnya') {
                        jenisKegiatanLainnyaRow.style.display = 'block';
                        jenisKegiatanLainnyaInput.required = true;
                    } else {
                        jenisKegiatanLainnyaRow.style.display = 'none';
                        jenisKegiatanLainnyaInput.required = false;
                        jenisKegiatanLainnyaInput.value = '';
                    }
                });
                
                // Trigger change event on page load to set initial state
                jenisKegiatanSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
    @endif

    @push('js')
        <script>
            // Wait for jQuery to be available for Summernote
            function waitForJQuery() {
                if (typeof $ !== 'undefined') {
                    console.log('jQuery loaded:', typeof $ !== 'undefined');
                    initializeSummernote();
                } else {
                    setTimeout(waitForJQuery, 100);
                }
            }

            function initializeSummernote() {
                // Initialize Summernote editors
                $(document).ready(function() {
                    console.log('Document ready, initializing Summernote...');
                    
                    // Check if elements exist
                    console.log('Element rincian:', $('#summernote').length);
                    console.log('Element deskripsi:', $('#summernote-deskripsi').length);
                    console.log('Element tujuan:', $('#summernote-tujuan').length);
                    
                    // Load Summernote from CDN
                    $.getScript('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js', function() {
                        console.log('Summernote loaded:', typeof $.summernote !== 'undefined');
                        
                        try {
                            // Summernote untuk rincian kebutuhan (hanya untuk admin)
                            if ($('#summernote').length) {
                                $('#summernote').summernote({
                                    toolbar: [
                                        ['style', ['style']],
                                        ['font', ['bold', 'underline', 'clear']],
                                        ['fontname', ['fontname']],
                                        ['color', ['color']],
                                        ['para', ['ul', 'ol', 'paragraph']]
                                    ],
                                    height: 120
                                });
                                console.log('Summernote rincian initialized');
                            }

                            // Summernote untuk deskripsi (hanya untuk admin)
                            if ($('#summernote-deskripsi').length) {
                                $('#summernote-deskripsi').summernote({
                                    toolbar: [
                                        ['style', ['style']],
                                        ['font', ['bold', 'underline', 'clear']],
                                        ['fontname', ['fontname']],
                                        ['color', ['color']],
                                        ['para', ['ul', 'ol', 'paragraph']]
                                    ],
                                    height: 100
                                });
                                console.log('Summernote deskripsi initialized');
                            }

                            // Summernote untuk tujuan (hanya untuk admin)
                            if ($('#summernote-tujuan').length) {
                                $('#summernote-tujuan').summernote({
                                    toolbar: [
                                        ['style', ['style']],
                                        ['font', ['bold', 'underline', 'clear']],
                                        ['fontname', ['fontname']],
                                        ['color', ['color']],
                                        ['para', ['ul', 'ol', 'paragraph']]
                                    ],
                                    height: 80
                                });
                                console.log('Summernote tujuan initialized');
                            }
                            
                        } catch (error) {
                            console.error('Error initializing Summernote:', error);
                        }
                    }).fail(function() {
                        console.error('Failed to load Summernote from CDN');
                    });
                });
            }

            // Start waiting for jQuery
            waitForJQuery();
        </script>
    @endpush

    @push('css')
        <!-- Include Summernote CSS -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    @endpush
@endsection
