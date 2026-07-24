@extends('layouts.adminlte')

@section('content_title', 'Edit Rencana Kegiatan')

@section('content')
    <div class="container text-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Form Rencana Kegiatan</h5>
        </div>

        <form id="rencana-kegiatan-form" action="{{ route('rencana_kegiatan.update', $rencana_kegiatan->uuid ?? $rencana_kegiatan->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- ALERT ERROR VALIDASI -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle mr-1"></i> Gagal Menyimpan!</strong> Silakan periksa kembali isian form Anda.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- WIZARD PROGRESS BAR -->
            <div class="row mb-5 mt-2">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <div class="progress position-absolute" style="height: 4px; top: 20px; left: 10%; right: 10%; z-index: 1;">
                            <div class="progress-bar bg-success" id="wizard-progress" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="step-indicator active text-center position-relative" style="z-index: 2; width: 33%;" id="indicator-1">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle" style="width: 40px; height: 40px; border: 4px solid #fff; font-weight: bold;">1</div>
                            <span class="fw-bold d-block step-text text-primary">Detail Kegiatan</span>
                        </div>
                        <div class="step-indicator text-center position-relative" style="z-index: 2; width: 33%;" id="indicator-2">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle" style="width: 40px; height: 40px; border: 4px solid #fff; font-weight: bold;">2</div>
                            <span class="fw-bold text-muted d-block step-text">Waktu & Lokasi</span>
                        </div>
                        <div class="step-indicator text-center position-relative" style="z-index: 2; width: 33%;" id="indicator-3">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle" style="width: 40px; height: 40px; border: 4px solid #fff; font-weight: bold;">3</div>
                            <span class="fw-bold text-muted d-block step-text">Dokumen Pendukung</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 1: Detail Kegiatan -->
            <div class="wizard-step" id="step-1">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark"><i class="fas fa-info-circle mr-1"></i> Informasi Dasar Kegiatan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kegiatan" class="form-control" placeholder="Contoh: Penanaman 1000 Bibit Mangrove" value="{{ old('nama_kegiatan', $rencana_kegiatan->nama_kegiatan) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Estimasi Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="estimasi_peserta" class="form-control" min="0" placeholder="Contoh: 50" value="{{ old('estimasi_peserta', $rencana_kegiatan->estimasi_peserta) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                                <input type="text" name="penanggung_jawab" class="form-control bg-light" value="{{ old('penanggung_jawab', $rencana_kegiatan->penanggung_jawab) }}" readonly required>
                                <small class="text-muted">Terisi otomatis berdasarkan akun login Anda.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelompok / Komunitas Pelaksana <span class="text-danger">*</span></label>
                                <input type="text" name="kelompok" class="form-control" placeholder="Contoh: Kelompok Tani Harapan Jaya" value="{{ old('kelompok', $rencana_kegiatan->kelompok) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                <select name="jenis_kegiatan" class="form-select" id="jenis_kegiatan_select" required>
                                    <option value="">-- Pilih Jenis Kegiatan --</option>
                                    @foreach(\App\Models\RencanaKegiatan::getJenisKegiatanOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('jenis_kegiatan', $rencana_kegiatan->jenis_kegiatan) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="jenis_kegiatan_lainnya_row" style="display: {{ old('jenis_kegiatan', $rencana_kegiatan->jenis_kegiatan) === 'lainnya' ? 'block' : 'none' }};">
                                <label class="form-label">Deskripsi Jenis Kegiatan Lainnya <span class="text-danger">*</span></label>
                                <input type="text" name="jenis_kegiatan_lainnya" class="form-control" placeholder="Jelaskan jenis kegiatan lainnya..." value="{{ old('jenis_kegiatan_lainnya', $rencana_kegiatan->jenis_kegiatan_lainnya) }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control" id="summernote-deskripsi" rows="3" placeholder="Contoh: Kegiatan ini difokuskan pada perbaikan ekosistem...">{!! old('deskripsi', $rencana_kegiatan->deskripsi) !!}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tujuan Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="tujuan" class="form-control" id="summernote-tujuan" rows="2" placeholder="Contoh: 1. Mencegah abrasi; 2. Membuka lahan baru...">{!! old('tujuan', $rencana_kegiatan->tujuan) !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <a href="{{ route('rencana_kegiatan.show', $rencana_kegiatan) }}" class="btn btn-secondary text-white float-left"><i class="fas fa-times mr-1"></i> Batal</a>
                        <button type="button" class="btn bg-navy text-white btn-next float-right" data-next="step-2">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Waktu & Lokasi -->
            <div class="wizard-step" id="step-2" style="display: none;">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark"><i class="fas fa-calendar-alt mr-1"></i> Waktu Pelaksanaan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $rencana_kegiatan->tanggal_mulai ? \Carbon\Carbon::parse($rencana_kegiatan->tanggal_mulai)->format('Y-m-d') : '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $rencana_kegiatan->tanggal_selesai ? \Carbon\Carbon::parse($rencana_kegiatan->tanggal_selesai)->format('Y-m-d') : '') }}" required>
                                <small class="text-muted" id="tanggal-help">Otomatis dibatasi minimal sama dengan Tanggal Mulai.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_mulai" class="form-control" value="{{ old('waktu_mulai', $rencana_kegiatan->waktu_mulai ? \Carbon\Carbon::parse($rencana_kegiatan->waktu_mulai)->format('H:i') : '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_selesai" class="form-control" value="{{ old('waktu_selesai', $rencana_kegiatan->waktu_selesai ? \Carbon\Carbon::parse($rencana_kegiatan->waktu_selesai)->format('H:i') : '') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi Kegiatan</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Desa / Wilayah <span class="text-danger">*</span></label>
                            <input type="text" id="lokasi" name="desa" class="form-control bg-white" placeholder="Contoh: Desa Suka Maju, Kecamatan Raya" value="{{ old('desa', $rencana_kegiatan->desa) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Koordinat Lokasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="location_lat" name="lat" class="form-control" placeholder="Latitude" value="{{ old('lat', $rencana_kegiatan->lat) }}" readonly required>
                                <input type="text" id="location_lng" name="lng" class="form-control" placeholder="Longitude" value="{{ old('lng', $rencana_kegiatan->lng) }}" readonly required>
                            </div>
                        </div>
                        <div class="alert alert-info py-2 mb-3">
                            <i class="fas fa-info-circle mr-1"></i> <strong>Petunjuk:</strong> Gunakan kotak pencarian di dalam peta atau klik langsung pada peta untuk mendapatkan titik presisi kegiatan.
                        </div>
                        <div class="mb-3" id="map-create" style="width:100%; height:400px; border:1px solid #ddd; border-radius:4px;"></div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <button type="button" class="btn btn-secondary text-white btn-prev float-left" data-prev="step-1"><i class="fas fa-arrow-left mr-1"></i> Sebelumnya</button>
                        <button type="button" class="btn bg-navy text-white btn-next float-right" data-next="step-3">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Kebutuhan & Dokumen -->
            <div class="wizard-step" id="step-3" style="display: none;">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark"><i class="fas fa-list-alt mr-1"></i> Rincian Kebutuhan & Anggaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Rincian Kebutuhan <span class="text-danger">*</span></label>
                            <textarea name="rincian_kebutuhan" class="form-control" id="summernote-rincian">{!! old('rincian_kebutuhan', $rencana_kegiatan->rincian_kebutuhan) !!}</textarea>
                            <small class="text-muted">Tuliskan kebutuhan logistik, konsumsi, atau perlengkapan secara rinci.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File Anggaran Kegiatan</label>
                            @if($rencana_kegiatan->anggaran_kegiatan)
                                @php
                                    $anggaran = is_string($rencana_kegiatan->anggaran_kegiatan) ? json_decode($rencana_kegiatan->anggaran_kegiatan, true) : $rencana_kegiatan->anggaran_kegiatan;
                                    $anggaranPath = is_array($anggaran) ? ($anggaran['path'] ?? '') : $anggaran;
                                @endphp
                                @if($anggaranPath)
                                    <div class="mb-2">
                                        <a href="{{ Storage::disk('public')->url($anggaranPath) }}" target="_blank" class="badge bg-navy text-white p-2"><i class="fas fa-file-download mr-1"></i> Lihat File Saat Ini</a>
                                    </div>
                                @endif
                            @endif
                            <div class="custom-file mb-1">
                                <input type="file" id="anggaranKegiatanInput" name="anggaran_kegiatan" class="custom-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx">
                                <label class="custom-file-label" for="anggaranKegiatanInput">Pilih file anggaran (kosongkan jika tidak diubah)...</label>
                            </div>
                            <small class="text-muted">Ukuran maksimal 5MB (PDF/DOC/XLS).</small>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark"><i class="fas fa-file-alt mr-1"></i> Media & Dokumen Pendukung</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Media Publikasi (Foto/Banner)</label>
                            @if($rencana_kegiatan->foto)
                                @php
                                    $fotos = is_string($rencana_kegiatan->foto) ? json_decode($rencana_kegiatan->foto, true) : $rencana_kegiatan->foto;
                                @endphp
                                @if(is_array($fotos) && count($fotos) > 0)
                                    <div class="mb-2 d-flex flex-wrap">
                                        @foreach($fotos as $idx => $foto)
                                            @php $fotoPath = is_array($foto) ? ($foto['path'] ?? '') : $foto; @endphp
                                            @if($fotoPath)
                                                <div class="position-relative mr-2 mb-2 existing-foto-item">
                                                    <a href="{{ Storage::disk('public')->url($fotoPath) }}" target="_blank">
                                                        <img src="{{ Storage::disk('public')->url($fotoPath) }}" style="width:100px; height:100px; object-fit:cover; border-radius:8px;" class="border shadow-sm">
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute shadow btn-delete-existing-foto"
                                                            data-path="{{ $fotoPath }}"
                                                            style="top:-5px; right:-5px; border-radius:50%; width:24px; height:24px; padding:0; display:flex; align-items:center; justify-content:center; z-index:10;"
                                                            title="Hapus foto ini">
                                                        <i class="fas fa-times" style="font-size:12px;"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                            <div class="custom-file mb-1">
                                <input type="file" id="fotoInput" name="foto[]" class="custom-file-input" accept="image/jpeg,image/png" multiple>
                                <label class="custom-file-label" for="fotoInput">Pilih file foto/banner...</label>
                            </div>
                            <small class="text-muted">Maksimal 5 foto (JPG/PNG). Ukuran maksimal 5MB/foto.</small>
                            <div id="image-preview-container" class="row mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dokumen Tambahan (Opsional)</label>
                            @if($rencana_kegiatan->dokumen)
                                @php
                                    $dokumens = is_string($rencana_kegiatan->dokumen) ? json_decode($rencana_kegiatan->dokumen, true) : $rencana_kegiatan->dokumen;
                                @endphp
                                @if(is_array($dokumens) && count($dokumens) > 0)
                                    <div class="mb-2">
                                        @foreach($dokumens as $idx => $dok)
                                            @php $dokPath = is_array($dok) ? ($dok['path'] ?? '') : $dok; @endphp
                                            @if($dokPath)
                                                <div class="d-inline-block position-relative mr-2 mb-2 existing-dokumen-item">
                                                    <a href="{{ Storage::disk('public')->url($dokPath) }}" target="_blank" class="badge bg-navy text-white p-2"><i class="fas fa-file-download mr-1"></i> Dokumen {{ $idx + 1 }}</a>
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute shadow btn-delete-existing-dokumen"
                                                            data-path="{{ $dokPath }}"
                                                            style="top:-5px; right:-5px; border-radius:50%; width:20px; height:20px; padding:0; display:flex; align-items:center; justify-content:center; z-index:10;"
                                                            title="Hapus dokumen ini">
                                                        <i class="fas fa-times" style="font-size:10px;"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                            <div class="custom-file mb-1">
                                <input type="file" id="dokumenInput" name="dokumen[]" class="custom-file-input" accept=".pdf,.doc,.docx" multiple>
                                <label class="custom-file-label" for="dokumenInput">Pilih file dokumen...</label>
                            </div>
                            <small class="text-muted">Maksimal 5 dokumen (PDF/DOC). Ukuran maksimal 5MB/dokumen.</small>
                            <div id="preview-dokumen" class="d-flex flex-column mt-2"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <button type="button" class="btn btn-secondary text-white btn-prev float-left" data-prev="step-2"><i class="fas fa-arrow-left mr-1"></i> Sebelumnya</button>
                        <div class="float-right d-flex">
                            <button type="submit" name="action" value="simpan" class="btn btn-secondary text-white mr-2"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                            @if (in_array($rencana_kegiatan->status, [\App\Models\RencanaKegiatan::STATUS_DRAFT, \App\Models\RencanaKegiatan::STATUS_REVISI]))
                                <button type="submit" name="action" value="ajukan" class="btn bg-navy text-white"><i class="fas fa-paper-plane mr-1"></i> Ajukan Rencana</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const fotoInput = document.getElementById('fotoInput');
        const preview = document.getElementById('image-preview-container');

        let filesBuffer = [];

        fotoInput.addEventListener('change', function() {
            const maxFiles = 5;
            const existingCount = $('.existing-foto-item').length;
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            
            if (existingCount + filesBuffer.length + this.files.length > maxFiles) {
                alert(`Maksimal ${maxFiles} file foto. Saat ini ada ${existingCount} foto tersimpan dan ${filesBuffer.length} antrian.`);
                this.value = '';
                return;
            }
            
            for (let file of this.files) {
                if (!file.type.startsWith('image/')) continue;
                
                // Validasi ukuran file
                if (file.size > maxSize) {
                    alert(`File ${file.name} terlalu besar. Maksimal ukuran 5MB.`);
                    continue;
                }
                
                // Validasi tipe file
                if (!allowedTypes.includes(file.type)) {
                    alert(`File ${file.name} tidak valid. Hanya diperbolehkan JPG, JPEG, PNG.`);
                    continue;
                }

                // hindari duplikasi
                if (!filesBuffer.some(f => f.name === file.name && f.size === file.size)) {
                    filesBuffer.push(file);
                }
            }

            renderPreview();
            syncInputFiles();
        });

        function renderPreview() {
            preview.innerHTML = '';

            filesBuffer.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'col-auto position-relative mb-2 mr-2';

                    div.innerHTML = `
                    <img src="${e.target.result}"
                         style="width:100px; height:100px; object-fit:cover; border-radius:8px;"
                         class="border shadow-sm">
                    <button type="button"
                            class="btn btn-sm btn-danger position-absolute shadow"
                            style="top:-5px; right:-5px; border-radius:50%; width:24px; height:24px; padding:0; display:flex; align-items:center; justify-content:center; z-index:10;"
                            onclick="removeFoto(${index})">
                        <i class="fas fa-times" style="font-size:12px;"></i>
                    </button>
                `;

                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            });
        }

        function removeFoto(index) {
            filesBuffer.splice(index, 1);
            renderPreview();
            syncInputFiles();
        }

        function syncInputFiles() {
            const dataTransfer = new DataTransfer();
            filesBuffer.forEach(file => dataTransfer.items.add(file));
            fotoInput.files = dataTransfer.files;
        }
    </script>

    <script>
        const dokumenInput = document.getElementById('dokumenInput');
        const previewDokumen = document.getElementById('preview-dokumen');

        let dokumenBuffer = [];

        dokumenInput.addEventListener('change', function() {
            const maxFiles = 5;
            const existingCount = $('.existing-dokumen-item').length;
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['application/pdf', 'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            
            if (existingCount + dokumenBuffer.length + this.files.length > maxFiles) {
                alert(`Maksimal ${maxFiles} file dokumen. Saat ini ada ${existingCount} dokumen tersimpan dan ${dokumenBuffer.length} antrian.`);
                this.value = '';
                return;
            }
            
            Array.from(this.files).forEach(file => {
                if (!file.type.match(/pdf|word|officedocument/)) return;
                
                // Validasi ukuran file
                if (file.size > maxSize) {
                    alert(`File ${file.name} terlalu besar. Maksimal ukuran 5MB.`);
                    return;
                }
                
                // Validasi tipe file
                if (!allowedTypes.includes(file.type)) {
                    alert(`File ${file.name} tidak valid. Hanya diperbolehkan PDF, DOC, DOCX.`);
                    return;
                }

                const exists = dokumenBuffer.some(
                    f => f.name === file.name && f.size === file.size
                );

                if (!exists) {
                    dokumenBuffer.push(file);
                }
            });

            syncDokumenInput();
            renderDokumenPreview();

            // ❌ JANGAN reset input
        });

        function renderDokumenPreview() {
            previewDokumen.innerHTML = '';

            dokumenBuffer.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'd-flex align-items-center border rounded p-2';

                div.innerHTML = `
            <i class="fas fa-file-alt text-primary me-2"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold">${file.name}</div>
                <small class="text-muted">${(file.size/1024).toFixed(1)} KB</small>
            </div>
            <button type="button"
                    class="btn btn-sm btn-danger"
                    onclick="removeDokumen(${index})"><i class="fas fa-times"></i></button>
        `;

                previewDokumen.appendChild(div);
            });
        }

        function removeDokumen(index) {
            dokumenBuffer.splice(index, 1);
            syncDokumenInput();
            renderDokumenPreview();
        }

        function syncDokumenInput() {
            const dt = new DataTransfer();
            dokumenBuffer.forEach(file => dt.items.add(file));
            dokumenInput.files = dt.files;
        }

        // Handle anggaran kegiatan file upload
        const anggaranKegiatanInput = document.getElementById('anggaranKegiatanInput');
        const previewAnggaran = document.getElementById('preview-anggaran');
        let anggaranBuffer = [];

        anggaranKegiatanInput.addEventListener('change', function() {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['application/pdf', 'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            
            if (this.files.length > 1) {
                alert('Maksimal 1 file anggaran kegiatan.');
                this.value = '';
                return;
            }

            Array.from(this.files).forEach(file => {
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak diizinkan. Gunakan PDF, DOC, DOCX, XLS, atau XLSX.');
                    this.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert('Ukuran file maksimal 5MB.');
                    this.value = '';
                    return;
                }

                anggaranBuffer = [file];
            });

            renderAnggaranPreview();
        });

        function renderAnggaranPreview() {
            previewAnggaran.innerHTML = '';

            anggaranBuffer.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'd-flex align-items-center border rounded p-2';
                div.innerHTML = `
            <i class="fas fa-file-excel text-success me-2"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold">${file.name}</div>
                <small class="text-muted">${(file.size/1024).toFixed(1)} KB</small>
            </div>
            <button type="button"
                    class="btn btn-sm btn-danger"
                    onclick="removeAnggaran(${index})"><i class="fas fa-times"></i></button>
        `;

                previewAnggaran.appendChild(div);
            });
        }

        function removeAnggaran(index) {
            anggaranBuffer.splice(index, 1);
            anggaranKegiatanInput.value = '';
            renderAnggaranPreview();
        }
    </script>

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
            }
        });
    </script>

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
                    console.log('Element rincian:', $('#summernote-rincian').length);
                    console.log('Element deskripsi:', $('#summernote-deskripsi').length);
                    console.log('Element tujuan:', $('#summernote-tujuan').length);
                    
                    // Load Summernote from CDN
                    $.getScript('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js', function() {
                        console.log('Summernote loaded:', typeof $.summernote !== 'undefined');
                        
                        try {
                            // Summernote untuk rincian kebutuhan
                            $('#summernote-rincian').summernote({
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

                            // Summernote untuk deskripsi
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

                            // Summernote untuk tujuan
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

            // CodeMirror
            $(function() {
                CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                    mode: "htmlmixed",
                    theme: "monokai"
                });
            });
        </script>
    @endpush

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
        <style>
            #map-create {
                background: #f7fafc;
            }
        </style>
    @endpush

    @push('css')
        <!-- Include Summernote CSS -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Inisialisasi Peta
                const initialLat = {{ old('lat', $rencana_kegiatan->lat) ?: -0.0227 }};
                const initialLng = {{ old('lng', $rencana_kegiatan->lng) ?: 109.3323 }};
                window.map = L.map('map-create').setView([initialLat, initialLng], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(window.map);

                // Variabel Global Marker
                let pickMarker = null;

                // Fungsi Update Input Field
                function updateLatLngInputs(lat, lng) {
                    document.getElementById('location_lat').value = lat.toFixed(6);
                    document.getElementById('location_lng').value = lng.toFixed(6);
                }

                // Tambahkan Marker Default dari Database/Old Input
                @if(old('lat', $rencana_kegiatan->lat) && old('lng', $rencana_kegiatan->lng))
                    pickMarker = L.marker([initialLat, initialLng], { draggable: true }).addTo(window.map);
                    pickMarker.on('dragend', function(e) {
                        const pos = e.target.getLatLng();
                        updateLatLngInputs(pos.lat, pos.lng);
                    });
                @endif

                // Fungsi Buat/Pindah Marker
                function createOrMoveMarker(latlng) {
                    if (pickMarker) {
                        pickMarker.setLatLng(latlng);
                    } else {
                        pickMarker = L.marker(latlng, { draggable: true }).addTo(window.map);
                        // Update lat/lng jika marker digeser manual (drag)
                        pickMarker.on('dragend', function(e) {
                            const pos = e.target.getLatLng();
                            updateLatLngInputs(pos.lat, pos.lng);
                        });
                    }
                    updateLatLngInputs(latlng.lat, latlng.lng);
                }

                // 2. Event Klik Manual pada Peta
                window.map.on('click', function(e) {
                    createOrMoveMarker(e.latlng);
                });

                // 3. Inisialisasi Kotak Pencarian (Geocoder)
                L.Control.geocoder({
                    defaultMarkGeocode: false,
                    placeholder: "Cari nama jalan, desa, kota...",
                })
                .on('markgeocode', function(e) {
                    const center = e.geocode.center;
                    
                    // Geser kamera ke hasil pencarian
                    window.map.fitBounds(e.geocode.bbox); 
                    
                    // Buat/pindahkan marker ke hasil pencarian
                    createOrMoveMarker(center);    
                })
                .addTo(window.map);

                // Fix render peta di dalam card Bootstrap
                setTimeout(function() { 
                    if(window.map) window.map.invalidateSize(); 
                }, 250);
            });
            // client-side date check with optional auto-swap for create form
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('rencana-kegiatan-form');
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    // Check if coordinates are filled
                    const latEl = document.querySelector('input[name="lat"]');
                    const lngEl = document.querySelector('input[name="lng"]');
                    const lat = latEl ? latEl.value : '';
                    const lng = lngEl ? lngEl.value : '';

                    if (!lat || !lng) {
                        e.preventDefault();
                        alert(
                            'Silakan pilih lokasi pada peta terlebih dahulu dengan mengklik pada area peta.'
                        );
                        return false;
                    }

                    // Date validation
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
        </script>
        <script>
            // --- LOGIKA TOGGLE JENIS KEGIATAN LAINNYA ---
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
                    // Trigger sekali untuk inisialisasi status required
                    if (jenisKegiatanSelect.value === 'lainnya') {
                        document.querySelector('input[name="jenis_kegiatan_lainnya"]').required = true;
                    }
                }
            });

            // --- LOGIKA MULTI-STEP WIZARD ---
            document.addEventListener('DOMContentLoaded', function() {
                const steps = ['step-1', 'step-2', 'step-3'];
                let currentStepIndex = 0;
                
                // Elemen Progress
                const progressBar = document.getElementById('wizard-progress');
                
                function showStep(index) {
                    // Hide all steps
                    steps.forEach(step => {
                        const el = document.getElementById(step);
                        if (el) el.style.display = 'none';
                    });
                    
                    // Show current step
                    const currentEl = document.getElementById(steps[index]);
                    if (currentEl) currentEl.style.display = 'block';
                    
                    // Khusus Step 2: Trigger Leaflet agar me-render ulang ukuran container
                    if (steps[index] === 'step-2' && typeof window.map !== 'undefined') {
                        setTimeout(() => window.map.invalidateSize(), 300);
                    }
                    
                    // Update UI Progress Bar
                    updateProgressUI(index);
                }
                
                function updateProgressUI(index) {
                    // Update Bar Width (0%, 50%, 100%)
                    const progressPercentage = (index / (steps.length - 1)) * 100;
                    if(progressBar) progressBar.style.width = progressPercentage + '%';
                    
                    // Update Circles and Texts
                    for(let i=0; i<steps.length; i++) {
                        const indicator = document.getElementById('indicator-' + (i+1));
                        if(!indicator) continue;
                        const circle = indicator.querySelector('.step-circle');
                        const text = indicator.querySelector('.step-text');
                        
                        if (i < index) {
                            // Completed Steps
                            circle.className = 'rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle';
                            text.className = 'fw-bold text-success d-block step-text';
                        } else if (i === index) {
                            // Current Active Step
                            circle.className = 'rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle';
                            text.className = 'fw-bold text-primary d-block step-text';
                        } else {
                            // Future Steps
                            circle.className = 'rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle';
                            text.className = 'fw-bold text-muted d-block step-text';
                        }
                    }
                }
                
                // Event Listener Tombol "Selanjutnya"
                document.querySelectorAll('.btn-next').forEach(btn => {
                    btn.addEventListener('click', function() {
                        // 1. Validasi Halaman Saat Ini Sebelum Pindah
                        const currentStepEl = document.getElementById(steps[currentStepIndex]);
                        const inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
                        let isValid = true;
                        
                        // Periksa setiap field wajib
                        for (let i = 0; i < inputs.length; i++) {
                            const input = inputs[i];
                            if (!input.checkValidity()) {
                                input.reportValidity(); // Memunculkan pop-up browser asli
                                isValid = false;
                                break;
                            }
                        }
                        
                        // Khusus untuk input koordinat peta yang hidden/readonly, periksa manual jika kosong
                        if (currentStepIndex === 1 && isValid) { // Jika sedang di Step 2 (Lokasi)
                            const lat = document.getElementById('location_lat').value;
                            if (!lat) {
                                alert("Mohon klik peta atau cari lokasi terlebih dahulu.");
                                isValid = false;
                            }
                        }

                        // Khusus Summernote required yang tidak terdeteksi HTML5 default
                        if (currentStepIndex === 0 && isValid) {
                            const desc = $('#summernote-deskripsi').summernote('isEmpty');
                            const tujuan = $('#summernote-tujuan').summernote('isEmpty');
                            if (desc || tujuan) {
                                alert("Deskripsi dan Tujuan Kegiatan tidak boleh kosong.");
                                isValid = false;
                            }
                        }

                        if (currentStepIndex === 2 && isValid) {
                            const rincian = $('#summernote-rincian').summernote('isEmpty');
                            if (rincian) {
                                alert("Rincian Kebutuhan tidak boleh kosong.");
                                isValid = false;
                            }
                        }
                        
                        // Jika lulus validasi, pindah ke halaman berikutnya
                        if (isValid) {
                            currentStepIndex++;
                            showStep(currentStepIndex);
                            window.scrollTo(0, 0); // Gulir ke atas
                        }
                    });
                });
                
                // Event Listener Tombol "Sebelumnya"
                document.querySelectorAll('.btn-prev').forEach(btn => {
                    btn.addEventListener('click', function() {
                        currentStepIndex--;
                        showStep(currentStepIndex);
                        window.scrollTo(0, 0);
                    });
                });
                
                // --- LOGIKA SMART DATE RANGE ---
                const tglMulai = document.getElementById('tanggal_mulai');
                const tglSelesai = document.getElementById('tanggal_selesai');
                
                if(tglMulai && tglSelesai) {
                    tglMulai.addEventListener('change', function() {
                        // Set attribut min (tanggal minimal) pada field tanggal selesai
                        tglSelesai.min = this.value;
                        
                        // Reset tanggal selesai jika tanggalnya lebih kecil dari tanggal mulai yang baru
                        if (tglSelesai.value && tglSelesai.value < this.value) {
                            tglSelesai.value = this.value; 
                        }
                    });
                }
                
                // Init view awal
                showStep(currentStepIndex);
            });
            // ---------------------------------
        </script>
        <script src="/public/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script>
            $(document).ready(function () {
                bsCustomFileInput.init();
                
                // Logika Hapus File Existing (Media Publikasi)
                $('.btn-delete-existing-foto').click(function(e) {
                    e.preventDefault();
                    if(confirm('Hapus foto ini? File akan terhapus saat Anda klik Perbarui Rencana Kegiatan.')) {
                        let path = $(this).data('path');
                        // Tambahkan hidden input untuk backend (RencanaKegiatanController)
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'remove_foto[]',
                            value: path
                        }).appendTo('form');
                        
                        // Efek visual hilang perlahan
                        $(this).closest('.existing-foto-item').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                });

                // Logika Hapus File Existing (Dokumen Tambahan)
                $('.btn-delete-existing-dokumen').click(function(e) {
                    e.preventDefault();
                    if(confirm('Hapus dokumen ini? File akan terhapus saat Anda klik Perbarui Rencana Kegiatan.')) {
                        let path = $(this).data('path');
                        // Tambahkan hidden input
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'remove_dokumen[]',
                            value: path
                        }).appendTo('form');
                        
                        // Efek visual hilang perlahan
                        $(this).closest('.existing-dokumen-item').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
