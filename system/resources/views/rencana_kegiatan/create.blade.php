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
                                <input type="text" name="nama_kegiatan" class="form-control" placeholder="Contoh: Penanaman 1000 Bibit Mangrove" value="{{ old('nama_kegiatan') }}" required>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Tuliskan nama kegiatan yang jelas dan spesifik agar mudah diidentifikasi oleh Admin.</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Estimasi Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="estimasi_peserta" class="form-control" placeholder="Contoh: 50" value="{{ old('estimasi_peserta') }}" required>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Perkiraan jumlah peserta yang akan hadir dalam kegiatan.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                                <input type="text" name="penanggung_jawab" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly required>
                                <small class="text-muted">Terisi otomatis berdasarkan akun login Anda.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelompok / Komunitas Pelaksana <span class="text-danger">*</span></label>
                                <input type="text" name="kelompok" class="form-control" placeholder="Contoh: Kelompok Tani Harapan Jaya" value="{{ old('kelompok') }}" required>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Nama kelompok, komunitas, atau lembaga yang bertanggung jawab melaksanakan kegiatan.</small>
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
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Pilih kategori yang paling sesuai dengan kegiatan yang akan dilaksanakan.</small>
                            </div>
                            <div class="col-md-6 mb-3" id="jenis_kegiatan_lainnya_row" style="display: {{ old('jenis_kegiatan') == 'lainnya' ? 'block' : 'none' }};">
                                <label class="form-label">Deskripsi Jenis Kegiatan Lainnya <span class="text-danger">*</span></label>
                                <input type="text" name="jenis_kegiatan_lainnya" class="form-control" placeholder="Contoh: Monitoring Terumbu Karang & Bersih Pantai (Beach Cleanup)" value="{{ old('jenis_kegiatan_lainnya') }}">
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Jelaskan secara singkat jenis kegiatan yang tidak tercantum dalam daftar pilihan.</small>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control" id="summernote-deskripsi" rows="3" placeholder="Contoh: Kegiatan ini difokuskan pada perbaikan ekosistem...">{!! old('deskripsi') !!}</textarea>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Jelaskan gambaran umum kegiatan: apa yang dilakukan, di mana, dan bagaimana pelaksanaannya.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tujuan Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="tujuan" class="form-control" id="summernote-tujuan" rows="2" placeholder="Contoh: 1. Mencegah abrasi; 2. Membuka lahan baru...">{!! old('tujuan') !!}</textarea>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Sebutkan tujuan utama kegiatan secara jelas. Gunakan format poin jika lebih dari satu tujuan.</small>
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
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Tanggal pertama kegiatan dimulai.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" required>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Tanggal terakhir kegiatan berlangsung. Jika hanya 1 hari, isi sama dengan tanggal mulai.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_mulai" class="form-control" value="{{ old('waktu_mulai') }}" required>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Jam kegiatan dimulai (format 24 jam, contoh: 08:00).</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_selesai" class="form-control" value="{{ old('waktu_selesai') }}" required>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Perkiraan jam kegiatan selesai (format 24 jam, contoh: 16:00).</small>
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
                            <input type="text" name="desa" class="form-control" placeholder="Contoh: Pesisir Desa Suka Maju" value="{{ old('desa') }}" required>
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
                            <!-- Grand Total Banner -->
                            <div class="p-3 mb-3 rounded d-flex align-items-center justify-content-between" style="background-color: #eef2ff; border: 1px solid #c7d2fe;">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-calculator text-navy mr-2"></i>Jumlah Kebutuhan</h6>
                                    <small class="text-muted">Total otomatis terhitung dari perincian kebutuhan di bawah</small>
                                </div>
                                <h4 class="mb-0 fw-bold text-navy" id="display-grand-total">Rp 0</h4>
                            </div>

                            <label class="form-label fw-bold text-dark mb-2">Perincian Kebutuhan <span class="text-danger">*</span></label>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-sm" id="table-rincian-pengajuan">
                                    <thead class="bg-navy text-white">
                                        <tr>
                                            <th class="text-center align-middle" style="width: 40px;">No</th>
                                            <th class="align-middle" style="min-width: 200px;">Objek Kebutuhan</th>
                                            <th class="align-middle" style="width: 140px;">Jumlah</th>
                                            <th class="align-middle" style="width: 160px;">Harga Satuan (Rp)</th>
                                            <th class="align-middle" style="width: 160px;">Subtotal (Rp)</th>
                                            <th class="align-middle" style="min-width: 180px;">Keterangan</th>
                                            <th class="text-center align-middle" style="width: 50px;"><i class="fas fa-cog"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-rincian-pengajuan">
                                        <!-- Rows added dynamically via JS -->
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn bg-navy text-white btn-sm font-weight-bold shadow-sm" id="btn-tambah-baris-rincian">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>

                            <textarea name="rincian_kebutuhan" id="hidden-rincian-kebutuhan" class="d-none">{!! old('rincian_kebutuhan') !!}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File Anggaran Kegiatan (Opsional)</label>
                            <div class="custom-file mb-1">
                                <input type="file" id="anggaranKegiatanInput" name="anggaran_kegiatan" class="custom-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx">
                                <label class="custom-file-label" for="anggaranKegiatanInput">Pilih file anggaran (jika ada)...</label>
                            </div>
                            <small class="text-muted">Unggah proposal/RAB jika ada. Ukuran maksimal 5MB (PDF/DOC/XLS).</small>
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
                                <input type="file" id="fotoInput" name="foto[]" class="custom-file-input" accept="image/jpeg,image/png" multiple>
                                <label class="custom-file-label" for="fotoInput">Pilih file foto/banner...</label>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Unggah foto/banner terkait kegiatan sebagai media dokumentasi. Maksimal 5 foto (JPG/PNG), ukuran maks. 5MB/foto.</small>
                            <div id="image-preview-container" class="row mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dokumen Tambahan (Opsional)</label>
                            <div class="custom-file mb-1">
                                <input type="file" id="dokumenInput" name="dokumen[]" class="custom-file-input" accept=".pdf,.doc,.docx" multiple>
                                <label class="custom-file-label" for="dokumenInput">Pilih file dokumen...</label>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Lampirkan dokumen pendukung seperti surat izin, proposal, atau jadwal kegiatan. Maks. 5 file (PDF/DOC), ukuran maks. 5MB/file.</small>
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
            const maxFiles = 5;
            const maxSize = 5 * 1024 * 1024; // 5MB sesuai instruksi
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            
            if (filesBuffer.length + this.files.length > maxFiles) {
                alert(`Maksimal ${maxFiles} file foto. Saat ini ada ${filesBuffer.length} file.`);
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
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['application/pdf', 'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            
            if (dokumenBuffer.length + this.files.length > maxFiles) {
                alert(`Maksimal ${maxFiles} file dokumen. Saat ini ada ${dokumenBuffer.length} file.`);
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
                                placeholder: $('#summernote-rincian').attr('placeholder'),
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
                                placeholder: $('#summernote-deskripsi').attr('placeholder'),
                                toolbar: [
                                    ['style', ['style']],
                                    ['font', ['bold', 'underline', 'clear']],
                                    ['fontname', ['fontname']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']]
                                ],
                                height: 120
                            });
                            console.log('Summernote deskripsi initialized');

                            // Summernote untuk tujuan
                            $('#summernote-tujuan').summernote({
                                placeholder: $('#summernote-tujuan').attr('placeholder'),
                                toolbar: [
                                    ['style', ['style']],
                                    ['font', ['bold', 'underline', 'clear']],
                                    ['fontname', ['fontname']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']]
                                ],
                                height: 120
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
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
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
                
                function showStep(index) {
                    currentStepIndex = index;
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

                // Handle Form Submit untuk "Ajukan Rencana"
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const actionVal = document.getElementById('form-action').value;
                        if (actionVal === 'draft') {
                            return true;
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

            function parseNumber(val) {
                if (!val) return 0;
                var clean = val.toString().replace(/[^0-9]/g, '');
                return parseFloat(clean) || 0;
            }

            function extractQuantity(val) {
                if (!val) return 1;
                var match = val.toString().match(/\d+(\.\d+)?/);
                return match ? parseFloat(match[0]) : 1;
            }

            function recalculateRincianTable() {
                var totalGrand = 0;
                var items = [];

                $('#tbody-rincian-pengajuan tr').each(function(index) {
                    $(this).find('.rincian-no').text(index + 1);

                    var objek = $(this).find('.rincian-objek').val() || '';
                    var jumlahStr = $(this).find('.rincian-jumlah').val() || '';
                    var hargaRaw = $(this).find('.rincian-harga').val() || '';
                    var hargaNum = parseNumber(hargaRaw);
                    var qty = extractQuantity(jumlahStr);
                    var subtotal = qty * hargaNum;

                    $(this).find('.rincian-subtotal').val(formatRupiah(subtotal));

                    totalGrand += subtotal;

                    if (objek.trim() !== '' || jumlahStr.trim() !== '' || hargaNum > 0) {
                        items.push({
                            objek: objek.trim(),
                            jumlah: jumlahStr.trim(),
                            harga_satuan: hargaNum,
                            subtotal: subtotal,
                            keterangan: ($(this).find('.rincian-keterangan').val() || '').trim()
                        });
                    }
                });

                $('#display-grand-total').text(formatRupiah(totalGrand));
                
                if (items.length > 0) {
                    $('#hidden-rincian-kebutuhan').val(JSON.stringify(items));
                } else {
                    $('#hidden-rincian-kebutuhan').val('');
                }
            }

            function addRincianRow(data) {
                data = data || { objek: '', jumlah: '', harga_satuan: '', subtotal: 0, keterangan: '' };
                var rowCount = $('#tbody-rincian-pengajuan tr').length + 1;
                
                var html = '<tr class="rincian-row">' +
                    '<td class="text-center align-middle rincian-no text-muted fw-bold">' + rowCount + '</td>' +
                    '<td><input type="text" class="form-control form-control-sm rincian-objek" placeholder="Objek kebutuhan / nama barang" value="' + (data.objek || '').replace(/"/g, '&quot;') + '"></td>' +
                    '<td><input type="text" class="form-control form-control-sm rincian-jumlah" placeholder="Contoh: 1 orang" value="' + (data.jumlah || '').replace(/"/g, '&quot;') + '"></td>' +
                    '<td><input type="number" min="0" class="form-control form-control-sm rincian-harga" placeholder="0" value="' + (data.harga_satuan !== '' && data.harga_satuan !== undefined ? data.harga_satuan : '') + '"></td>' +
                    '<td><input type="text" class="form-control form-control-sm rincian-subtotal bg-light" readonly placeholder="Rp 0"></td>' +
                    '<td><input type="text" class="form-control form-control-sm rincian-keterangan" placeholder="Keterangan (opsional)" value="' + (data.keterangan || '').replace(/"/g, '&quot;') + '"></td>' +
                    '<td class="text-center align-middle"><button type="button" class="btn btn-sm btn-danger rounded-circle btn-remove-rincian shadow-sm" style="width: 24px; height: 24px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Hapus Baris"><i class="fas fa-times" style="font-size: 11px;"></i></button></td>' +
                    '</tr>';
                    
                $('#tbody-rincian-pengajuan').append(html);
                recalculateRincianTable();
            }

            function loadInitialRincian(rawContent) {
                $('#tbody-rincian-pengajuan').empty();
                if (!rawContent) {
                    addRincianRow();
                    return;
                }
                
                try {
                    var parsed = typeof rawContent === 'string' ? JSON.parse(rawContent) : rawContent;
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        parsed.forEach(function(item) {
                            addRincianRow(item);
                        });
                        return;
                    }
                } catch(e) {}
                
                // Legacy text fallback
                var cleanText = rawContent.replace(/<[^>]*>/g, '\n').split('\n').map(function(s){ return s.trim(); }).filter(Boolean);
                if (cleanText.length > 0) {
                    cleanText.forEach(function(line) {
                        addRincianRow({ objek: line, jumlah: '1', harga_satuan: 0, keterangan: '' });
                    });
                } else {
                    addRincianRow();
                }
            }

            $('#btn-tambah-baris-rincian').on('click', function() {
                addRincianRow();
            });

            $(document).on('click', '.btn-remove-rincian', function() {
                if ($('#tbody-rincian-pengajuan tr').length > 1) {
                    $(this).closest('tr').remove();
                    recalculateRincianTable();
                } else {
                    alert('Minimal harus ada 1 baris rincian pengajuan.');
                }
            });

            $(document).on('input keyup change', '.rincian-objek, .rincian-jumlah, .rincian-harga, .rincian-keterangan', function() {
                recalculateRincianTable();
            });

            // Load initial content on ready
            loadInitialRincian($('#hidden-rincian-kebutuhan').val());

            // Ensure table recalculated on form submit
            $('form').on('submit', function() {
                recalculateRincianTable();
            });
        </script>
        <script src="/public/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script>
            $(document).ready(function () {
                bsCustomFileInput.init();
            });
        </script>
    @endpush
@endsection
