@extends('layouts.adminlte')

@section('content_title', 'Buat Rencana Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Form Rencana Kegiatan</h5>
        </div>

        <form id="rencana-kegiatan-form" action="{{ route('rencana_kegiatan.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            
            <!-- Lokasi Kegiatan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Lokasi Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Koordinat Lokasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="location_lat" name="lat" class="form-control"
                                placeholder="Latitude" readonly required>
                            <input type="text" id="location_lng" name="lng" class="form-control"
                                placeholder="Longitude" readonly required>
                            <button type="button" id="use-location" class="btn btn-outline-secondary">Gunakan Lokasi
                                Saya</button>
                        </div>
                        <small class="form-text text-muted">Pilih lokasi dengan mengklik pada peta atau gunakan lokasi
                            Anda
                            saat ini</small>
                    </div>

                    <div class="mb-3" id="map-create"
                        style="width:100%; height:70vh; border:1px solid #ddd; border-radius:4px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Desa / Wilayah <span class="text-danger">*</span></label>
                        <input type="text" name="desa" class="form-control" placeholder="Nama desa atau wilayah">
                        <small class="form-text text-muted">Tuliskan nama desa atau wilayah lokasi kegiatan</small>
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
                                <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kegiatan" class="form-control" placeholder="Nama kegiatan"
                                    required>
                                <small class="form-text text-muted">Tuliskan nama kegiatan yang akan dilaksanakan</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estimasi Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="estimasi_peserta" class="form-control" min="0" placeholder="Estimasi Jumlah Peserta">
                                <small class="form-text text-muted">Perkirakan jumlah peserta yang akan hadir</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_mulai" class="form-control">
                                    <small class="form-text text-muted">Pilih tanggal mulai kegiatan</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_selesai" class="form-control">
                                    <small class="form-text text-muted">Pilih tanggal selesai kegiatan</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="waktu_mulai" class="form-control">
                                    <small class="form-text text-muted">Pilih waktu mulai kegiatan</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="waktu_selesai" class="form-control">
                                    <small class="form-text text-muted">Pilih waktu selesai kegiatan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                                <input type="text" name="penanggung_jawab" class="form-control"
                                    placeholder="Nama Penanggung Jawab">
                                <small class="form-text text-muted">Tuliskan nama penanggung jawab kegiatan</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kelompok / Komunitas Pelaksana <span class="text-danger">*</span></label>
                                <input type="text" name="kelompok" class="form-control" placeholder="Nama kelompok">
                                <small class="form-text text-muted">Tuliskan nama kelompok atau komunitas pelaksana</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                <select name="jenis_kegiatan" class="form-select" required>
                                    <option value="">-- Pilih Jenis Kegiatan --</option>
                                    @foreach(\App\Models\RencanaKegiatan::getJenisKegiatanOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('jenis_kegiatan') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Pilih jenis kegiatan yang sesuai</small>
                            </div>

                            <div class="mb-3" id="jenis_kegiatan_lainnya_row" @if(old('jenis_kegiatan') !== 'lainnya') style="display: none;" @endif>
                                <label class="form-label">Deskripsi Jenis Kegiatan Lainnya <span class="text-danger">*</span></label>
                                <input type="text" name="jenis_kegiatan_lainnya" class="form-control" 
                                    placeholder="Jelaskan jenis kegiatan lainnya..."
                                    value="{{ old('jenis_kegiatan_lainnya') }}"
                                    @if(old('jenis_kegiatan') === 'lainnya') required @endif>
                                <small class="form-text text-muted">Jelaskan jenis kegiatan lainnya</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control" id="summernote-deskripsi" rows="3" placeholder="Deskripsikan kegiatan yang akan dilaksanakan..."></textarea>
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
                                <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                                <textarea name="tujuan" class="form-control" id="summernote-tujuan" rows="2" placeholder="Tuliskan tujuan kegiatan yang akan dilaksanakan..."></textarea>
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

            <!-- Detail Kebutuhan dan Dokumentasi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-1"></i>
                        Detail Kebutuhan Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Rincian Kebutuhan <span class="text-danger">*</span></label>
                                <textarea type="text" name="rincian_kebutuhan" class="form-control" id="summernote"></textarea>
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
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Anggaran Kegiatan <span class="text-danger">*</span></label>
                                <input type="file" id="anggaranKegiatanInput" name="anggaran_kegiatan" class="form-control" required
                                    accept=".pdf,.doc,.docx,.xls,.xlsx">
                                <small class="text-muted">
                                    Unggah dokumen anggaran kegiatan<br>
                                    Ukuran maksimal 5MB<br>
                                    Format: PDF, DOC, DOCX, XLS, XLSX
                                </small>
                            </div>
                            {{-- PREVIEW ANGGARAN KEGIATAN --}}
                            <div id="preview-anggaran" class="d-flex flex-column"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-1"></i>
                        Media Pendukung Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Media Publikasi</label>
                                <input type="file" id="fotoInput" name="foto[]" class="form-control" accept="image/*"
                                    multiple>
                                <small class="text-muted">
                                    Unggah foto media publikasi kegiatan (banner, poster, flyer dll)<br>
                                    Maksimal 5 foto dengan ukuran maksimal 2MB per foto<br>
                                    Format: JPG, JPEG, PNG
                                </small>
                            </div>
                            {{-- PREVIEW --}}
                            <div id="preview-foto" class="d-flex flex-column"></div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dokumen Pendukung Kegiatan</label>
                                <input type="file" id="dokumenInput" name="dokumen[]" class="form-control" multiple
                                    accept=".pdf,.doc,.docx">
                                <small class="text-muted">
                                    Unggah dokumen pendukung kegiatan (undangan, absensi, dll)<br>
                                    Maksimal 5 dokumen dengan ukuran maksimal 5MB per dokumen<br>
                                    Format: PDF, DOC, DOCX
                                </small>
                            </div>
                            {{-- PREVIEW DOKUMEN --}}
                            <div id="preview-dokumen" class="d-flex flex-column gap-2 mb-3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                        style="height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm"
                        style="height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const fotoInput = document.getElementById('fotoInput');
        const preview = document.getElementById('preview-foto');

        let filesBuffer = [];

        fotoInput.addEventListener('change', function() {
            const maxFiles = 5;
            const maxSize = 4 * 1024 * 1024; // 4MB
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
                    alert(`File ${file.name} terlalu besar. Maksimal ukuran 4MB.`);
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
                    div.className = 'position-relative d-flex align-items-center gap-2 mb-2';

                    div.innerHTML = `
                    <img src="${e.target.result}"
                         style="width:100px;height:100px;object-fit:cover"
                         class="rounded border">

                    <button type="button"
                            class="btn btn-sm btn-danger ms-auto"
                            onclick="removeFoto(${index})">
                        <i class="fas fa-times"></i>
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
                    console.log('Element rincian:', $('#summernote').length);
                    console.log('Element deskripsi:', $('#summernote-deskripsi').length);
                    console.log('Element tujuan:', $('#summernote-tujuan').length);
                    
                    // Load Summernote from CDN
                    $.getScript('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js', function() {
                        console.log('Summernote loaded:', typeof $.summernote !== 'undefined');
                        
                        try {
                            // Summernote untuk rincian kebutuhan
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('map-create').setView([-6.200000, 106.816666], 5);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // marker user pick
                let pickMarker = null;

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

                // ensure proper rendering
                setTimeout(function() {
                    map.invalidateSize();
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
                            'Silakan pilih lokasi pada peta terlebih dahulu dengan mengklik pada peta atau menggunakan tombol "Gunakan Lokasi Saya".'
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
    @endpush
@endsection
