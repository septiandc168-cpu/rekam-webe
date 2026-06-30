@extends('layouts.adminlte')

@section('content_title', 'Buat Laporan Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Form Laporan Kegiatan</h5>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <h6><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi Kesalahan:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="laporan-kegiatan-form" action="{{ route('laporan_kegiatan.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="rencana_kegiatan_id" value="{{ $rencanaKegiatan->uuid }}">

            <!-- Informasi Rencana Kegiatan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi Rencana Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->nama_kegiatan }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kegiatan</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->getJenisKegiatanLabel() }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Penanggung Jawab</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->penanggung_jawab }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kelompok / Komunitas Pelaksana</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->kelompok }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="2" readonly>{!! strip_tags($rencanaKegiatan->deskripsi) !!}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tujuan</label>
                        <textarea class="form-control" rows="2" readonly>{!! strip_tags($rencanaKegiatan->tujuan) !!}</textarea>
                    </div>
                </div>
            </div>

            <!-- Detail Pelaksanaan Kegiatan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks mr-1"></i>
                        Detail Pelaksanaan Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->desa }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waktu Pelaksanaan</label>
                                <input type="text" class="form-control" 
                                    value="{{ $rencanaKegiatan->waktu_mulai && $rencanaKegiatan->waktu_selesai
            ? \Carbon\Carbon::parse($rencanaKegiatan->waktu_mulai)->format('H:i').' - '.\Carbon\Carbon::parse($rencanaKegiatan->waktu_selesai)->format('H:i')
            : ($rencanaKegiatan->waktu_mulai
                ? \Carbon\Carbon::parse($rencanaKegiatan->waktu_mulai)->format('H:i')
                : 'Belum ditentukan') }}"readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pelaksanaan</label>
                                <input type="text" class="form-control" 
                                    value="{{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_selesai)->format('d F Y') }}" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Realisasi Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                                    <input type="date" name="realisasi_tanggal_mulai" class="form-control" 
                                        placeholder="Pilih tanggal mulai kegiatan..." required
                                        value="{{ old('realisasi_tanggal_mulai') }}">
                                    <small class="form-text text-muted">Masukkan tanggal mulai kegiatan</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><small>.</small></label>
                                    <input type="date" name="realisasi_tanggal_selesai" class="form-control" 
                                        placeholder="Pilih tanggal selesai kegiatan..." required
                                        value="{{ old('realisasi_tanggal_selesai') }}">
                                    <small class="form-text text-muted">Masukkan tanggal selesai kegiatan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Target Peserta</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->estimasi_peserta ?? '-' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Realisasi Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="realisasi_peserta" class="form-control" 
                                    placeholder="Masukkan jumlah peserta yang hadir..." min="0" required
                                    value="{{ old('realisasi_peserta') }}">
                                <small class="form-text text-muted">Masukkan jumlah peserta yang hadir</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="mb-3">
                                <label class="form-label">Rangkaian Kegiatan / Alur Acara <span class="text-danger">*</span></label>
                                <textarea name="rangkaian_kegiatan" class="form-control" id="summernote-rangkaian" rows="4" 
                                    placeholder="Jelaskan rangkaian kegiatan atau alur acara dari awal hingga akhir..."
                                    required>{{ old('rangkaian_kegiatan') }}</textarea>
                                <small class="form-text text-muted">
                                Tuliskan rangkaian kegiatan secara kronologis dari awal hingga akhir kegiatan.
                                <br>
                                1. Awali dengan pembukaan atau persiapan kegiatan.
                                <br>
                                2. Jelaskan kegiatan inti yang dilakukan.
                                <br>
                                3. Akhiri dengan penutup atau hasil kegiatan.
                                <br>
                                Tuliskan dalam 1-3 paragraf singkat atau dalam bentuk poin agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Pembukaan kegiatan dan penyampaian tujuan kegiatan.</li>
                                <li>Pelaksanaan kegiatan inti berupa pelatihan/pembinaan.</li>
                                <li>Diskusi dan tanya jawab dengan peserta.</li>
                                <li>Penutup dan kesimpulan kegiatan.</li>
                                </ul>
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Profil Peserta <span class="text-danger">*</span></label>
                                <textarea name="profil_peserta" class="form-control" id="summernote-profil" rows="3" 
                                    placeholder="Jelaskan profil peserta (Masyarakat, Pokdarwis, Mahasiswa, dll)..."
                                    required>{{ old('profil_peserta') }}</textarea>
                                <small class="form-text text-muted">
                                Jelaskan profil atau jenis peserta yang mengikuti kegiatan.
                                <br>
                                Tuliskan kelompok atau latar belakang peserta secara singkat.
                                <br>
                                Tuliskan dalam 1-3 paragraf singkat atau dalam bentuk poin agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Masyarakat Desa Pulau Cempedak</li>
                                <li>Kelompok Sadar Wisata (Pokdarwis)</li>
                                <li>Mahasiswa peserta KKN</li>
                                <li>Ibu-ibu anggota Garda Emak</li>
                                <li>Perangkat desa dan tokoh masyarakat</li>
                                </ul>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hasil dan Output Kegiatan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Hasil dan Output Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Hasil yang Dicapai <span class="text-danger">*</span></label>
                                <textarea name="hasil_dicapai" class="form-control" id="summernote-hasil" rows="4" 
                                    placeholder="Jelaskan hasil yang berhasil dicapai dari kegiatan ini..."
                                    required>{{ old('hasil_dicapai') }}</textarea>
                                <small class="form-text text-muted">
                                Jelaskan hasil atau dampak yang diperoleh setelah kegiatan dilaksanakan.
                                <br>
                                Tuliskan secara singkat mengenai capaian kegiatan, perubahan yang terjadi, atau manfaat yang dirasakan oleh peserta.
                                <br>
                                Tuliskan dalam 1-3 paragraf singkat atau dalam bentuk poin agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Peserta memahami cara pembuatan pupuk organik.</li>
                                <li>Masyarakat mampu mengidentifikasi hama tanaman secara mandiri.</li>
                                <li>Terbentuk kelompok kerja kecil untuk melanjutkan kegiatan.</li>
                                <li>Peserta menunjukkan antusiasme dan aktif dalam sesi diskusi.</li>
                                </ul>
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Output Nyata <span class="text-danger">*</span></label>
                                <textarea name="output_nyata" class="form-control" id="summernote-output" rows="4" 
                                    placeholder="Jelaskan output nyata yang dihasilkan (dokumen, data, foto, komitmen, dll)..."
                                    required>{{ old('output_nyata') }}</textarea>
                                <small class="form-text text-muted">
                                Tuliskan output nyata yang dihasilkan dari kegiatan ini.
                                <br>
                                Output dapat berupa dokumen, data, produk, foto, komitmen, kesepakatan, atau bentuk hasil lain yang dapat dibuktikan.
                                <br>
                                Tuliskan dalam 1-3 paragraf singkat atau dalam bentuk poin agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Data hasil identifikasi hama tanaman di lokasi kegiatan.</li>
                                <li>Terbentuknya komitmen atau kesepakatan bersama dari peserta.</li>
                                <li>Produk hasil praktik seperti pupuk organik.</li>
                                </ul>
                                </small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Dampak Awal yang Terlihat <span class="text-danger">*</span></label>
                                <textarea name="dampak_awal" class="form-control" id="summernote-dampak" rows="4" 
                                    placeholder="Jelaskan dampak awal yang terlihat setelah kegiatan..."
                                    required>{{ old('dampak_awal') }}</textarea>
                                <small class="form-text text-muted">
                                Jelaskan dampak awal yang mulai terlihat setelah kegiatan dilaksanakan.
                                <br>
                                Tuliskan perubahan, respon, atau tindak lanjut yang muncul dari peserta atau masyarakat setelah kegiatan berlangsung.
                                <br>
                                Tuliskan dalam 1-3 paragraf singkat atau dalam bentuk poin agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Peserta mulai mencoba menerapkan pembuatan pupuk organik di rumah.</li>
                                <li>Masyarakat menunjukkan minat untuk mengikuti kegiatan lanjutan.</li>
                                <li>Peserta lebih aktif berdiskusi mengenai pengelolaan limbah organik.</li>
                                <li>Terjadi peningkatan kesadaran masyarakat terhadap pentingnya menjaga lingkungan.</li>
                                </ul>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kendala dan Evaluasi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Kendala dan Evaluasi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Kendala yang Dihadapi</label>
                                <textarea name="kendala" class="form-control" id="summernote-kendala" rows="4" 
                                    placeholder="Jelaskan kendala yang dihadapi selama pelaksanaan kegiatan...">{{ old('kendala') }}</textarea>
                                <small class="form-text text-muted">
                                Jelaskan kendala atau hambatan yang dihadapi selama kegiatan berlangsung.
                                <br>
                                Kendala dapat berupa keterbatasan waktu, sarana dan prasarana, partisipasi peserta, kondisi cuaca, atau faktor lainnya yang mempengaruhi pelaksanaan kegiatan.
                                <br>
                                Tuliskan dalam 1-3 paragraf singkat atau dalam bentuk poin agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Jumlah peserta yang hadir lebih sedikit dari yang direncanakan.</li>
                                <li>Keterbatasan alat atau bahan saat praktik kegiatan.</li>
                                <li>Kondisi cuaca yang kurang mendukung selama kegiatan berlangsung.</li>
                                <li>Waktu pelaksanaan terbatas sehingga beberapa materi tidak dapat dibahas secara mendalam.</li>
                                </ul>
                                </small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Solusi yang Dilakukan</label>
                                <textarea name="solusi" class="form-control" id="summernote-solusi" rows="4" 
                                    placeholder="Jelaskan solusi yang dilakukan untuk mengatasi kendala...">{{ old('solusi') }}</textarea>
                                <small class="form-text text-muted">
                                Jelaskan solusi atau langkah yang dilakukan untuk mengatasi kendala selama kegiatan berlangsung.
                                <br>
                                Tuliskan tindakan yang diambil oleh pelaksana kegiatan agar kegiatan tetap dapat berjalan dengan baik.
                                <br>
                                Tuliskan dalam 1-3 paragraf singkat atau dalam bentuk poin agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Menyesuaikan jadwal kegiatan agar lebih sesuai dengan waktu peserta.</li>
                                <li>Menggunakan alat atau bahan alternatif yang tersedia di lokasi.</li>
                                <li>Melakukan koordinasi tambahan dengan peserta atau pihak terkait.</li>
                                <li>Menyederhanakan materi agar dapat disampaikan dalam waktu yang terbatas.</li>
                                </ul>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Catatan Evaluasi & Rekomendasi</label>
                                <textarea name="evaluasi_rekomendasi" class="form-control" id="summernote-evaluasi" rows="4" 
                                    placeholder="Berikan evaluasi dan rekomendasi untuk kegiatan selanjutnya...">{{ old('evaluasi_rekomendasi') }}</textarea>
                                <small class="form-text text-muted">
                                Tuliskan evaluasi terhadap pelaksanaan kegiatan serta rekomendasi untuk perbaikan atau pengembangan kegiatan di masa mendatang.
                                <br>
                                Evaluasi dapat berupa hal yang sudah berjalan dengan baik maupun hal yang masih perlu ditingkatkan.
                                <br>
                                Tuliskan dalam 1-3 paragraf singkat atau dalam bentuk poin agar mudah dipahami.
                                <br><br>
                                Contoh:
                                <ul>
                                <li>Kegiatan berjalan dengan baik dan peserta menunjukkan antusiasme tinggi.</li>
                                <li>Perlu penambahan waktu pada sesi praktik agar peserta lebih memahami materi.</li>
                                <li>Disarankan adanya kegiatan lanjutan untuk memperdalam materi yang telah diberikan.</li>
                                <li>Perlu koordinasi lebih awal dengan peserta agar kehadiran lebih maksimal.</li>
                                </ul>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Kegiatan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-upload mr-1"></i>
                        Dokumentasi Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto Kegiatan <span class="text-danger">*</span></label>
                                <input type="file" name="foto_kegiatan[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">
                                    Unggah foto kegiatan<br>
                                    Maksimal 10 foto dengan ukuran maksimal 3MB per foto<br>
                                    Format: JPG, JPEG, PNG
                                </small>
                            </div>
                            <div id="preview-foto_kegiatan" class="d-flex flex-column"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Daftar Hadir <span class="text-danger">*</span></label>
                                <input type="file" name="daftar_hadir[]" class="form-control" 
                                    accept=".pdf,.doc,.docx,.xls,.xlsx" multiple>
                                <small class="text-muted">
                                    Unggah daftar hadir kegiatan<br>
                                    Maksimal 10 file dengan ukuran maksimal 3MB per file<br>
                                    Format: PDF, DOC, DOCX, XLS, XLSX
                                </small>
                            </div>
                            <div id="preview-daftar-hadir" class="d-flex flex-column gap-2 mb-3"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notulen</label>
                                <input type="file" name="notulen[]" class="form-control" 
                                    accept=".pdf,.doc,.docx" multiple>
                                <small class="text-muted">
                                    Unggah notulen kegiatan<br>
                                    Maksimal 10 file dengan ukuran maksimal 3MB per file<br>
                                    Format: PDF, DOC, DOCX
                                </small>
                            </div>
                            <div id="preview-notulen" class="d-flex flex-column gap-2 mb-3"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Materi <span class="text-danger">*</span></label>
                                <input type="file" name="materi[]" class="form-control" 
                                    accept=".pdf,.ppt,.pptx,.doc,.docx" multiple>
                                <small class="text-muted">
                                    Unggah materi kegiatan<br>
                                    Maksimal 10 file dengan ukuran maksimal 3MB per file<br>
                                    Format: PDF, PPT, PPTX, DOC, DOCX
                                </small>
                            </div>
                            <div id="preview-materi" class="d-flex flex-column gap-2 mb-3"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Berita Acara</label>
                                <input type="file" name="berita_acara[]" class="form-control" 
                                    accept=".pdf,.doc,.docx" multiple>
                                <small class="text-muted">
                                    Unggah berita acara kegiatan<br>
                                    Maksimal 10 file dengan ukuran maksimal 3MB per file<br>
                                    Format: PDF, DOC, DOCX
                                </small>
                            </div>
                            <div id="preview-berita-acara" class="d-flex flex-column gap-2 mb-3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('laporan_kegiatan.index') }}" class="btn btn-secondary btn-sm"
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

    <!-- Include Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    <script>
        // Wait for jQuery to be available
        function waitForJQuery() {
            if (typeof $ !== 'undefined') {
                console.log('jQuery loaded:', typeof $ !== 'undefined');
                initializeSummernote();
            } else {
                setTimeout(waitForJQuery, 100);
            }
        }

        function initializeSummernote() {
            // Validasi tanggal realisasi
            document.addEventListener('DOMContentLoaded', function() {
                const tanggalMulaiInput = document.querySelector('input[name="realisasi_tanggal_mulai"]');
                const tanggalSelesaiInput = document.querySelector('input[name="realisasi_tanggal_selesai"]');
                
                function validateDates() {
                    if (tanggalMulaiInput.value && tanggalSelesaiInput.value) {
                        const tanggalMulai = new Date(tanggalMulaiInput.value);
                        const tanggalSelesai = new Date(tanggalSelesaiInput.value);
                        
                        if (tanggalSelesai < tanggalMulai) {
                            tanggalSelesaiInput.setCustomValidity('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                        } else {
                            tanggalSelesaiInput.setCustomValidity('');
                        }
                    }
                }
                
                tanggalMulaiInput.addEventListener('change', validateDates);
                tanggalSelesaiInput.addEventListener('change', validateDates);
            });

            // File preview functions
            function createImagePreview(input, previewContainer) {
                const files = input.files;
                previewContainer.innerHTML = '';
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'mb-2';
                        div.innerHTML = `
                            <img src="${e.target.result}" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                            <small class="text-muted d-block">${file.name}</small>
                        `;
                        previewContainer.appendChild(div);
                    };
                    
                    reader.readAsDataURL(file);
                }
            }

            function createFilePreview(input, previewContainer) {
                const files = input.files;
                previewContainer.innerHTML = '';
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const div = document.createElement('div');
                    div.className = 'mb-2';
                    div.innerHTML = `
                        <div class="d-flex align-items-center p-2 border rounded">
                            <i class="fas fa-file-alt me-2"></i>
                            <small>${file.name}</small>
                        </div>
                    `;
                    previewContainer.appendChild(div);
                }
            }

            // Setup file preview listeners
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('input[name="foto_kegiatan[]"]').addEventListener('change', function() {
                    createImagePreview(this, document.getElementById('preview-foto_kegiatan'));
                });

                document.querySelector('input[name="daftar_hadir[]"]').addEventListener('change', function() {
                    createFilePreview(this, document.getElementById('preview-daftar-hadir'));
                });

                document.querySelector('input[name="notulen[]"]').addEventListener('change', function() {
                    createFilePreview(this, document.getElementById('preview-notulen'));
                });

                document.querySelector('input[name="materi[]"]').addEventListener('change', function() {
                    createFilePreview(this, document.getElementById('preview-materi'));
                });

                document.querySelector('input[name="berita_acara[]"]').addEventListener('change', function() {
                    createFilePreview(this, document.getElementById('preview-berita-acara'));
                });
            });

            // Initialize Summernote editors
            $(document).ready(function() {
                console.log('Document ready, initializing Summernote...');
                
                // Check if elements exist
                console.log('Element rangkaian:', $('#summernote-rangkaian').length);
                console.log('Element profil:', $('#summernote-profil').length);
                
                // Load Summernote from CDN
                $.getScript('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js', function() {
                    console.log('Summernote loaded:', typeof $.summernote !== 'undefined');
                    
                    try {
                        $('#summernote-rangkaian').summernote({
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120
                        });
                        console.log('Summernote rangkaian initialized');

                        $('#summernote-profil').summernote({
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 100
                        });
                        console.log('Summernote profil initialized');

                        $('#summernote-hasil').summernote({
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120
                        });
                        console.log('Summernote hasil initialized');

                        $('#summernote-output').summernote({
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120
                        });
                        console.log('Summernote output initialized');

                        $('#summernote-dampak').summernote({
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120
                        });
                        console.log('Summernote dampak initialized');

                        $('#summernote-kendala').summernote({
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120
                        });
                        console.log('Summernote kendala initialized');

                        $('#summernote-solusi').summernote({
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120
                        });
                        console.log('Summernote solusi initialized');

                        $('#summernote-evaluasi').summernote({
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120
                        });
                        console.log('Summernote evaluasi initialized');
                        
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
@endsection
