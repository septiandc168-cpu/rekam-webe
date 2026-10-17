@extends('layouts.adminlte')

@section('content_title', 'Buat Rencana Kegiatan')

@section('content')
    <div class="container text-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Form Rencana Kegiatan</h5>
        </div>

        <form id="rencana-kegiatan-form" action="{{ route('rencana_kegiatan.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="action" id="form-action" value="ajukan">
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
                        <div class="step-indicator active text-center position-relative" style="z-index: 2; width: 33%; cursor: pointer;" id="indicator-1">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle" style="width: 40px; height: 40px; border: 4px solid #fff; font-weight: bold;">1</div>
                            <span class="fw-bold d-block step-text text-primary">Detail Kegiatan</span>
                        </div>
                        <div class="step-indicator text-center position-relative" style="z-index: 2; width: 33%; cursor: pointer;" id="indicator-2">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle" style="width: 40px; height: 40px; border: 4px solid #fff; font-weight: bold;">2</div>
                            <span class="fw-bold text-muted d-block step-text">Waktu & Lokasi</span>
                        </div>
                        <div class="step-indicator text-center position-relative" style="z-index: 2; width: 33%; cursor: pointer;" id="indicator-3">
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
                                <input type="text" name="nama_kegiatan" class="form-control" placeholder="Masukkan nama kegiatan..." value="{{ old('nama_kegiatan') }}" required>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Tuliskan nama kegiatan yang jelas dan spesifik agar mudah diidentifikasi oleh Admin.</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Estimasi Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="estimasi_peserta" class="form-control" placeholder="Masukkan estimasi jumlah peserta..." value="{{ old('estimasi_peserta') }}" required>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Perkiraan jumlah peserta yang akan hadir dalam kegiatan.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                                <input type="text" name="penanggung_jawab" class="form-control" placeholder="Masukkan nama penanggung jawab..." value="{{ old('penanggung_jawab') }}" required>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Nama individu yang bertanggung jawab atas pelaksanaan kegiatan ini.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelompok / Komunitas Pelaksana <span class="text-danger">*</span></label>
                                <input type="text" name="kelompok" class="form-control" placeholder="Masukkan nama kelompok / komunitas pelaksana..." value="{{ old('kelompok') }}" required>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Nama kelompok, komunitas, atau lembaga yang bertanggung jawab melaksanakan kegiatan.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                <select name="jenis_kegiatan" class="form-select" id="jenis_kegiatan_select" required>
                                    <option value="">-- Pilih Jenis Kegiatan --</option>
                                    @foreach(\App\Models\RencanaKegiatan::getJenisKegiatanOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('jenis_kegiatan') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Pilih kategori yang paling sesuai dengan kegiatan yang akan dilaksanakan.</small>
                            </div>
                            <div class="col-md-6 mb-3" id="jenis_kegiatan_lainnya_row" style="display: {{ old('jenis_kegiatan') == 'lainnya' ? 'block' : 'none' }};">
                                <label class="form-label">Deskripsi Jenis Kegiatan Lainnya <span class="text-danger">*</span></label>
                                <input type="text" name="jenis_kegiatan_lainnya" class="form-control" placeholder="Masukkan jenis kegiatan lainnya..." value="{{ old('jenis_kegiatan_lainnya') }}">
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Jelaskan secara singkat jenis kegiatan yang tidak tercantum dalam daftar pilihan.</small>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control summernote-editor" id="summernote-deskripsi" rows="3" placeholder="Masukkan deskripsi kegiatan...">{!! old('deskripsi') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Jelaskan gambaran umum kegiatan: apa yang dilakukan, di mana, dan bagaimana pelaksanaannya.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tujuan Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="tujuan" class="form-control summernote-editor" id="summernote-tujuan" rows="2" placeholder="Masukkan tujuan kegiatan...">{!! old('tujuan') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Sebutkan tujuan utama kegiatan secara jelas. Gunakan format poin jika lebih dari satu tujuan.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary text-white float-left"><i class="fas fa-times mr-1"></i> Batal</a>
                        <div class="float-right d-flex">
                            <button type="button" class="btn btn-secondary text-white mr-2 btn-save-draft"><i class="fas fa-save mr-1"></i> Simpan sebagai Draft</button>
                            <button type="button" class="btn bg-navy text-white btn-next" data-next="step-2">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
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
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" required>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Tanggal pertama kegiatan dimulai.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" required>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Tanggal terakhir kegiatan berlangsung. Jika hanya 1 hari, isi sama dengan tanggal mulai.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_mulai" class="form-control" value="{{ old('waktu_mulai') }}" required>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Jam kegiatan dimulai (format 24 jam, contoh: 08:00).</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_selesai" class="form-control" value="{{ old('waktu_selesai') }}" required>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Perkiraan jam kegiatan selesai (format 24 jam, contoh: 16:00).</small>
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
                            <input type="text" name="desa" class="form-control" placeholder="Masukkan nama desa / wilayah..." value="{{ old('desa') }}" required>
                            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Tuliskan nama desa atau wilayah tempat kegiatan akan dilaksanakan.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Koordinat Lokasi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="location_lat" name="lat" class="form-control" placeholder="Latitude" value="{{ old('lat') }}" readonly required>
                                <input type="text" id="location_lng" name="lng" class="form-control" placeholder="Longitude" value="{{ old('lng') }}" readonly required>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Klik langsung pada peta atau gunakan kotak pencarian untuk menentukan titik lokasi kegiatan secara akurat.</small>
                        </div>
                        <div class="mb-3" id="map-create" style="width:100%; height:400px; border:1px solid #ddd; border-radius:4px;"></div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <button type="button" class="btn btn-secondary text-white btn-prev float-left" data-prev="step-1"><i class="fas fa-arrow-left mr-1"></i> Sebelumnya</button>
                        <div class="float-right d-flex">
                            <button type="button" class="btn btn-secondary text-white mr-2 btn-save-draft"><i class="fas fa-save mr-1"></i> Simpan sebagai Draft</button>
                            <button type="button" class="btn bg-navy text-white btn-next" data-next="step-3">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
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
                            <label class="form-label fw-bold text-dark mb-1">Rincian Kebutuhan <span class="text-danger">*</span></label>
                            <textarea name="rincian_kebutuhan" id="summernote-rincian" class="form-control summernote-editor" rows="4" placeholder="Tuliskan rincian kebutuhan kegiatan..." required>{!! old('rincian_kebutuhan') !!}</textarea>
                            <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Tuliskan rincian kebutuhan kegiatan seperti barang/alat, sewa, konsumsi, atau rincian anggaran yang dibutuhkan. Jika rincian kebutuhan sudah tertera pada file atau in-kind, cukup tuliskan keterangan singkat (contoh: "Tertera pada file" atau "In-kind").</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1">File Anggaran Kegiatan <span class="text-danger">*</span></label>
                            <div class="custom-file mb-1">
                                <input type="file" id="anggaranKegiatanInput" name="anggaran_kegiatan" class="custom-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.rtf,.zip,.rar,.7z" required>
                                <label class="custom-file-label" for="anggaranKegiatanInput">Pilih file anggaran...</label>
                            </div>
                            <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Unggah file proposal/RAB/rincian anggaran kegiatan. Bebas format dokumen (PDF, Word, Excel, ZIP, dll) hingga 50MB.</small>
                            <div id="preview-anggaran" class="d-flex flex-column mt-2"></div>
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
                            <div class="custom-file mb-1">
                                <input type="file" id="fotoInput" name="foto[]" class="custom-file-input" accept="image/*" multiple>
                                <label class="custom-file-label" for="fotoInput">Pilih file foto/banner...</label>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Unggah foto/banner terkait kegiatan. Bebas format gambar & jumlah file, foto berukuran besar akan otomatis dikompres oleh sistem.</small>
                            <div id="image-preview-container" class="row mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dokumen Pendukung (Opsional)</label>
                            <div class="custom-file mb-1">
                                <input type="file" id="dokumenInput" name="dokumen[]" class="custom-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.rtf,.zip,.rar,.7z" multiple>
                                <label class="custom-file-label" for="dokumenInput">Pilih file dokumen...</label>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Lampirkan dokumen pendukung seperti surat izin, proposal, atau jadwal. Bebas format dokumen & jumlah file hingga 50MB/file.</small>
                            <div id="preview-dokumen" class="d-flex flex-column mt-2"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <button type="button" class="btn btn-secondary text-white btn-prev float-left" data-prev="step-2"><i class="fas fa-arrow-left mr-1"></i> Sebelumnya</button>
                        <div class="float-right d-flex">
                            <button type="button" class="btn btn-secondary text-white mr-2 btn-save-draft"><i class="fas fa-save mr-1"></i> Simpan sebagai Draft</button>
                            <button type="submit" name="action" value="ajukan" class="btn bg-navy text-white"><i class="fas fa-paper-plane mr-1"></i> Ajukan Rencana</button>
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
            for (let file of this.files) {
                if (!file.type.startsWith('image/')) continue;
                
                // Hindari duplikasi
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
            const maxSize = 50 * 1024 * 1024; // 50MB
            
            Array.from(this.files).forEach(file => {
                // Validasi ukuran file
                if (file.size > maxSize) {
                    alert(`File ${file.name} melebihi batas 50MB.`);
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
                let icon = 'fa-file-alt text-secondary';
                const nameLower = file.name.toLowerCase();
                if (nameLower.endsWith('.pdf')) icon = 'fa-file-pdf text-danger';
                else if (nameLower.endsWith('.doc') || nameLower.endsWith('.docx')) icon = 'fa-file-word text-primary';
                else if (nameLower.endsWith('.xls') || nameLower.endsWith('.xlsx') || nameLower.endsWith('.csv')) icon = 'fa-file-excel text-success';
                else if (nameLower.endsWith('.ppt') || nameLower.endsWith('.pptx')) icon = 'fa-file-powerpoint text-warning';
                else if (nameLower.endsWith('.zip') || nameLower.endsWith('.rar') || nameLower.endsWith('.7z')) icon = 'fa-file-archive text-info';

                const div = document.createElement('div');
                div.className = 'preview-file-item position-relative p-2 mb-2 border rounded bg-white shadow-sm';
                div.style.paddingRight = '25px';
                div.innerHTML = `
                    <div class="d-flex align-items-center text-truncate mr-2" style="max-width: 90%;">
                        <i class="fas ${icon} mr-2" style="font-size:1.2rem;"></i>
                        <div class="text-truncate">
                            <div class="text-truncate font-weight-bold" style="font-size:0.85rem;" title="${file.name}">${file.name}</div>
                            <small class="text-muted">${(file.size/1024).toFixed(1)} KB</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger position-absolute shadow"
                            style="top:-6px; right:-6px; border-radius:50%; width:22px; height:22px; padding:0; display:flex; align-items:center; justify-content:center; z-index:10;"
                            onclick="removeDokumen(${index})" title="Hapus file ini">
                        <i class="fas fa-times text-white" style="color:#ffffff !important; font-size:11px !important; line-height:1 !important; margin:0 !important;"></i>
                    </button>
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
            const maxSize = 50 * 1024 * 1024; // 50MB
            
            if (this.files.length > 1) {
                alert('Maksimal 1 file anggaran kegiatan.');
                this.value = '';
                return;
            }

            Array.from(this.files).forEach(file => {
                if (file.size > maxSize) {
                    alert('Ukuran file maksimal 50MB.');
                    this.value = '';
                    return;
                }

                anggaranBuffer = [file];
            });

            renderAnggaranPreview();
            if (window.scheduleUpdateProgress) window.scheduleUpdateProgress();
        });

        function renderAnggaranPreview() {
            if (!previewAnggaran) return;
            previewAnggaran.innerHTML = '';

            anggaranBuffer.forEach((file, index) => {
                let icon = 'fa-file-alt text-secondary';
                const nameLower = file.name.toLowerCase();
                if (nameLower.endsWith('.pdf')) icon = 'fa-file-pdf text-danger';
                else if (nameLower.endsWith('.doc') || nameLower.endsWith('.docx')) icon = 'fa-file-word text-primary';
                else if (nameLower.endsWith('.xls') || nameLower.endsWith('.xlsx') || nameLower.endsWith('.csv')) icon = 'fa-file-excel text-success';
                else if (nameLower.endsWith('.ppt') || nameLower.endsWith('.pptx')) icon = 'fa-file-powerpoint text-warning';
                else if (nameLower.endsWith('.zip') || nameLower.endsWith('.rar') || nameLower.endsWith('.7z')) icon = 'fa-file-archive text-info';

                const div = document.createElement('div');
                div.className = 'preview-file-item position-relative p-2 mb-2 border rounded bg-white shadow-sm';
                div.style.paddingRight = '25px';
                div.innerHTML = `
                    <div class="d-flex align-items-center text-truncate mr-2" style="max-width: 90%;">
                        <i class="fas ${icon} mr-2" style="font-size:1.2rem;"></i>
                        <div class="text-truncate">
                            <div class="text-truncate font-weight-bold" style="font-size:0.85rem;" title="${file.name}">${file.name}</div>
                            <small class="text-muted">${(file.size/1024).toFixed(1)} KB</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger position-absolute shadow"
                            style="top:-6px; right:-6px; border-radius:50%; width:22px; height:22px; padding:0; display:flex; align-items:center; justify-content:center; z-index:10;"
                            onclick="removeAnggaran(${index})" title="Hapus file ini">
                        <i class="fas fa-times text-white" style="color:#ffffff !important; font-size:11px !important; line-height:1 !important; margin:0 !important;"></i>
                    </button>
                `;

                previewAnggaran.appendChild(div);
            });
        }

        function removeAnggaran(index) {
            anggaranBuffer = [];
            if (anggaranKegiatanInput) {
                anggaranKegiatanInput.value = '';
            }
            renderAnggaranPreview();
            const label = document.querySelector('label[for="anggaranKegiatanInput"]');
            if (label) {
                label.innerText = 'Pilih file anggaran...';
            }
            if (window.scheduleUpdateProgress) window.scheduleUpdateProgress();
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

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
        <style>
            #map-create {
                background: #f7fafc;
            }
            .step-indicator {
                cursor: pointer;
                user-select: none;
                transition: transform 0.2s ease;
            }
            .step-indicator:hover .step-circle {
                transform: scale(1.08);
            }
            .step-circle {
                transition: all 0.25s ease;
            }
            .progress {
                overflow: visible;
                background-color: #e9ecef;
            }
            .note-editor .note-placeholder {
                color: #6c757d !important;
                font-style: normal !important;
                pointer-events: none;
            }
        </style>
    @endpush

    @push('css')
        <!-- Include Summernote CSS -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Inisialisasi Peta (Default View: Pontianak)
                window.map = L.map('map-create').setView([-0.0227, 109.3323], 12);
                
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
                    if (window.scheduleUpdateProgress) window.scheduleUpdateProgress();
                }

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

                // Cek apakah ada koordinat dari old() (Setelah validasi error)
                let oldLat = document.getElementById('location_lat').value;
                let oldLng = document.getElementById('location_lng').value;
                if (oldLat && oldLng) {
                    let oldLatLng = { lat: parseFloat(oldLat), lng: parseFloat(oldLng) };
                    window.map.setView(oldLatLng, 15); // Zoom lebih dekat
                    createOrMoveMarker(oldLatLng);
                }

                // 2. Event Klik Manual pada Peta
                window.map.on('click', function(e) {
                    createOrMoveMarker(e.latlng);
                });

                // 3. Inisialisasi Kotak Pencarian (Geocoder)
                L.Control.geocoder({
                    geocoder: L.Control.Geocoder.arcgis(),
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
            // --- LOGIKA MULTI-STEP WIZARD & SUBMIT ---
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('rencana-kegiatan-form');
                const steps = ['step-1', 'step-2', 'step-3'];
                let currentStepIndex = 0;
                
                // Elemen Progress
                const progressBar = document.getElementById('wizard-progress');

                // Helper untuk cek apakah konten Summernote/Textarea terisi
                function isSummernoteFilled(selector) {
                    const $el = $(selector);
                    if ($el.length && typeof $el.summernote === 'function') {
                        try {
                            if ($el.summernote('isEmpty')) return false;
                            const code = $el.summernote('code');
                            const cleanText = $('<div>').html(code).text().replace(/\u00a0/g, ' ').trim();
                            return cleanText.length > 0;
                        } catch(e) {}
                    }
                    const rawEl = typeof selector === 'string' ? document.querySelector(selector) : selector;
                    if (rawEl && rawEl.value) {
                        const cleanText = rawEl.value.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
                        return cleanText.length > 0;
                    }
                    return false;
                }

                // Cek apakah seluruh kolom wajib pada tahap tertentu sudah terisi
                function isStepComplete(stepIdx) {
                    if (stepIdx === 0) {
                        // Tahap 1: Detail Kegiatan
                        const nama = (document.querySelector('input[name="nama_kegiatan"]')?.value || '').trim();
                        const estimasi = (document.querySelector('input[name="estimasi_peserta"]')?.value || '').trim();
                        const pj = (document.querySelector('input[name="penanggung_jawab"]')?.value || '').trim();
                        const kelompok = (document.querySelector('input[name="kelompok"]')?.value || '').trim();
                        const jenisSelect = document.querySelector('select[name="jenis_kegiatan"]');
                        const jenis = (jenisSelect?.value || '').trim();
                        
                        let jenisValid = jenis !== '';
                        if (jenis === 'lainnya') {
                            const jenisLainnya = (document.querySelector('input[name="jenis_kegiatan_lainnya"]')?.value || '').trim();
                            jenisValid = jenisLainnya !== '';
                        }

                        const deskripsiValid = isSummernoteFilled('#summernote-deskripsi');
                        const tujuanValid = isSummernoteFilled('#summernote-tujuan');

                        return !!(nama && estimasi && pj && kelompok && jenisValid && deskripsiValid && tujuanValid);
                    } else if (stepIdx === 1) {
                        // Tahap 2: Waktu & Lokasi
                        const tglMulai = (document.querySelector('input[name="tanggal_mulai"]')?.value || '').trim();
                        const tglSelesai = (document.querySelector('input[name="tanggal_selesai"]')?.value || '').trim();
                        const waktuMulai = (document.querySelector('input[name="waktu_mulai"]')?.value || '').trim();
                        const waktuSelesai = (document.querySelector('input[name="waktu_selesai"]')?.value || '').trim();
                        const desa = (document.querySelector('input[name="desa"]')?.value || '').trim();
                        const lat = (document.querySelector('input[name="lat"]')?.value || '').trim();
                        const lng = (document.querySelector('input[name="lng"]')?.value || '').trim();

                        return !!(tglMulai && tglSelesai && waktuMulai && waktuSelesai && desa && lat && lng);
                    } else if (stepIdx === 2) {
                        // Tahap 3: Kebutuhan & Dokumen
                        const rincianValid = isSummernoteFilled('#summernote-rincian');
                        const anggaranInput = document.getElementById('anggaranKegiatanInput');
                        const anggaranValid = (anggaranInput && anggaranInput.files && anggaranInput.files.length > 0) || (typeof anggaranBuffer !== 'undefined' && anggaranBuffer.length > 0);

                        return !!(rincianValid && anggaranValid);
                    }
                    return false;
                }
                
                function showStep(index) {
                    currentStepIndex = index;
                    // Hide all steps
                    steps.forEach(step => {
                        const el = document.getElementById(step);
                        if (el) el.style.display = 'none';
                    });
                    
                    // Show current step
                    const currentEl = document.getElementById(steps[index]);
                    if (currentEl) {
                        currentEl.style.display = 'block';
                        $(currentEl).find('.summernote-editor').each(function() {
                            const $t = $(this);
                            if (typeof $t.summernote === 'function') {
                                if ($t.summernote('isEmpty') || $t.val().trim() === '<p><br></p>' || $t.val().trim() === '') {
                                    $t.summernote('empty');
                                }
                            }
                        });
                    }
                    
                    // Khusus Step 2: Trigger Leaflet agar me-render ulang ukuran container
                    if (steps[index] === 'step-2' && typeof window.map !== 'undefined') {
                        setTimeout(() => window.map.invalidateSize(), 300);
                    }
                    
                    // Update UI Progress Bar
                    updateProgressUI(index);
                }
                
                function updateProgressUI(index) {
                    // Update Circles and Texts sesuai kelengkapan kolom yang terisi
                    for(let i=0; i<steps.length; i++) {
                        const indicator = document.getElementById('indicator-' + (i+1));
                        if(!indicator) continue;
                        const circle = indicator.querySelector('.step-circle');
                        const text = indicator.querySelector('.step-text');
                        const complete = isStepComplete(i);
                        const isCurrent = (i === index);

                        if (isCurrent) {
                            indicator.classList.add('active');
                            if (complete) {
                                // Tahap aktif dan sudah lengkap terisi
                                circle.className = 'rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle shadow';
                                circle.innerHTML = '<i class="fas fa-check"></i>';
                                circle.style.boxShadow = '0 0 0 4px rgba(0, 123, 255, 0.45)';
                                text.className = 'fw-bold text-primary d-block step-text';
                                indicator.setAttribute('title', 'Tahap ' + (i+1) + ': Sudah terisi lengkap (sedang aktif)');
                            } else {
                                // Tahap aktif dan belum lengkap
                                circle.className = 'rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle shadow';
                                circle.innerHTML = (i+1);
                                circle.style.boxShadow = '0 0 0 4px rgba(0, 123, 255, 0.25)';
                                text.className = 'fw-bold text-primary d-block step-text';
                                indicator.setAttribute('title', 'Tahap ' + (i+1) + ': Belum lengkap (sedang aktif)');
                            }
                        } else {
                            indicator.classList.remove('active');
                            circle.style.boxShadow = 'none';
                            if (complete) {
                                // Tahap tidak aktif dan sudah lengkap terisi
                                circle.className = 'rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle';
                                circle.innerHTML = '<i class="fas fa-check"></i>';
                                text.className = 'fw-bold text-success d-block step-text';
                                indicator.setAttribute('title', 'Tahap ' + (i+1) + ': Sudah terisi lengkap');
                            } else {
                                // Tahap tidak aktif dan belum lengkap
                                circle.className = 'rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle';
                                circle.innerHTML = (i+1);
                                text.className = 'fw-bold text-muted d-block step-text';
                                indicator.setAttribute('title', 'Tahap ' + (i+1) + ': Belum lengkap');
                            }
                        }
                    }

                    // Update lebar bar garis penghubung sesuai tahap yang terisi
                    let progressPercentage = 0;
                    if (isStepComplete(0) && isStepComplete(1)) {
                        progressPercentage = 100;
                    } else if (isStepComplete(0)) {
                        progressPercentage = 50;
                    } else {
                        progressPercentage = 0;
                    }
                    if(progressBar) {
                        progressBar.style.width = progressPercentage + '%';
                        progressBar.style.transition = 'width 0.3s ease';
                    }
                }

                // Klik langsung pada lingkaran / label indikator untuk navigasi ke tahap tersebut
                for(let i=0; i<steps.length; i++) {
                    const indicator = document.getElementById('indicator-' + (i+1));
                    if(indicator) {
                        indicator.style.cursor = 'pointer';
                        indicator.addEventListener('click', function() {
                            showStep(i);
                            window.scrollTo(0, 0);
                        });
                    }
                }

                // Pembaruan realtime saat form diinput / diubah
                let updateTimeout = null;
                function scheduleUpdateProgress() {
                    if (updateTimeout) clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(() => {
                        updateProgressUI(currentStepIndex);
                    }, 100);
                }
                window.scheduleUpdateProgress = scheduleUpdateProgress;

                if (form) {
                    form.addEventListener('input', scheduleUpdateProgress);
                    form.addEventListener('change', scheduleUpdateProgress);
                }

                // Inisialisasi awal
                updateProgressUI(0);
                
                // Event Listener Tombol "Simpan sebagai Draft" di semua bagian
                document.querySelectorAll('.btn-save-draft').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const namaKegiatanInput = document.querySelector('input[name="nama_kegiatan"]');
                        const namaKegiatan = namaKegiatanInput ? namaKegiatanInput.value.trim() : '';
                        if (!namaKegiatan) {
                            alert('Mohon isi Nama Kegiatan terlebih dahulu untuk menyimpan sebagai draft.');
                            showStep(0);
                            if (namaKegiatanInput) namaKegiatanInput.focus();
                            return false;
                        }

                        // Sinkronkan summernote ke textarea
                        if (typeof $ !== 'undefined') {
                            $('.summernote-editor').each(function() {
                                if (typeof $(this).summernote === 'function') {
                                    $(this).val($(this).summernote('code'));
                                }
                            });
                        }

                        const actionInput = document.getElementById('form-action');
                        if (actionInput) actionInput.value = 'draft';
                        if (form) {
                            form.noValidate = true;
                            form.submit();
                        }
                    });
                });

                // Event Listener Tombol "Selanjutnya"
                document.querySelectorAll('.btn-next').forEach(btn => {
                    btn.addEventListener('click', function() {
                        currentStepIndex++;
                        showStep(currentStepIndex);
                        window.scrollTo(0, 0); // Gulir ke atas
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

                // Handle Form Submit untuk "Ajukan Rencana"
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const actionVal = document.getElementById('form-action').value;
                        if (actionVal === 'draft') {
                            return true;
                        }

                        // Sinkronkan summernote ke textarea
                        if (typeof $ !== 'undefined') {
                            $('.summernote-editor').each(function() {
                                if (typeof $(this).summernote === 'function') {
                                    $(this).val($(this).summernote('code'));
                                }
                            });
                        }

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
                            showStep(1);
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
                                    showStep(1);
                                }
                                return false;
                            }
                        }
                    });
                }
                
                // Init view awal
                showStep(currentStepIndex);
            });
            // ---------------------------------
            // Rincian Pengajuan Dynamic Table Logic
            // ---------------------------------
            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(number || 0);
            }
        </script>
        <script src="/public/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script>
            $(document).ready(function () {
                bsCustomFileInput.init();
            });

            // Inisialisasi Summernote Editor
            function initializeSummernoteEditors() {
                function setupEditors() {
                    $('.summernote-editor').each(function() {
                        var $el = $(this);
                        // Hancurkan instance lama jika sudah terlanjur diinisialisasi dengan konfigurasi default
                        if ($el.next().hasClass('note-editor') || $el.data('summernote')) {
                            try { $el.summernote('destroy'); } catch(e) {}
                        }

                        $el.summernote({
                            placeholder: $el.attr('placeholder') || 'Masukkan isian...',
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120,
                            callbacks: {
                                onInit: function() {
                                    var $target = $(this);
                                    if ($target.summernote('isEmpty') || $target.val().trim() === '<p><br></p>' || $target.val().trim() === '') {
                                        $target.summernote('empty');
                                    }
                                    if (window.scheduleUpdateProgress) window.scheduleUpdateProgress();
                                },
                                onChange: function() {
                                    if (window.scheduleUpdateProgress) window.scheduleUpdateProgress();
                                },
                                onBlur: function() {
                                    if (window.scheduleUpdateProgress) window.scheduleUpdateProgress();
                                }
                            }
                        });
                    });
                }

                if (typeof $ !== 'undefined' && typeof $.fn.summernote !== 'undefined') {
                    setupEditors();
                } else if (typeof $ !== 'undefined') {
                    $.getScript('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js', function() {
                        setupEditors();
                    });
                } else {
                    setTimeout(initializeSummernoteEditors, 100);
                }
            }

            if (typeof $ !== 'undefined') {
                $(document).ready(initializeSummernoteEditors);
            } else {
                initializeSummernoteEditors();
            }
        </script>
    @endpush
@endsection
