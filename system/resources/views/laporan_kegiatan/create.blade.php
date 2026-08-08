@extends('layouts.adminlte')

@section('content_title', 'Buat Laporan Kegiatan')

@section('content')
    <div class="container pb-5 text-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-file-signature text-dark mr-2"></i> 
                {{ isset($isLaporanLangsung) && $isLaporanLangsung ? 'Buat Laporan Langsung' : 'Form Laporan Kegiatan' }}
            </h5>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm rounded mb-4">
                <h6 class="font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi Kesalahan Validasi:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!isset($isLaporanLangsung) || !$isLaporanLangsung)
            <!-- CALLOUT: Header Konteks Rencana Kegiatan -->
            <div class="card shadow-sm mb-4 bg-white p-3" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                <h6 class="text-dark fw-bold mb-3"><i class="fas fa-info-circle mr-1 text-secondary"></i> Konteks Rencana Kegiatan</h6>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Nama Kegiatan:</strong><br>
                        <span class="text-dark">{{ $rencanaKegiatan->nama_kegiatan }}</span>
                    </div>
                    <div class="col-md-4">
                        <strong>Waktu & Lokasi Rencana:</strong><br>
                        <span class="text-dark">
                            {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_selesai)->translatedFormat('d M Y') }}<br>
                            Di {{ $rencanaKegiatan->desa }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <strong>Target Peserta:</strong><br>
                        <span class="text-dark">{{ $rencanaKegiatan->estimasi_peserta ?? '-' }} Orang</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- WIZARD PROGRESS BAR -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between position-relative">
                    <div class="progress position-absolute" style="height: 4px; top: 20px; left: 15%; right: 15%; z-index: 1;">
                        <div class="progress-bar bg-primary" id="wizard-progress" role="progressbar" style="width: 0%;"></div>
                    </div>
                    
                    <div class="step-indicator text-center position-relative active" style="z-index: 2; width: 33%;" id="indicator-1">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle" style="width: 40px; height: 40px; border: 4px solid #fff; font-weight: bold;">1</div>
                        <span class="fw-bold text-primary d-block step-text">Realisasi Kegiatan</span>
                    </div>
                    
                    <div class="step-indicator text-center position-relative" style="z-index: 2; width: 33%;" id="indicator-2">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle" style="width: 40px; height: 40px; border: 4px solid #fff; font-weight: bold;">2</div>
                        <span class="fw-bold text-muted d-block step-text">Evaluasi</span>
                    </div>
                    
                    <div class="step-indicator text-center position-relative" style="z-index: 2; width: 33%;" id="indicator-3">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2 step-circle" style="width: 40px; height: 40px; border: 4px solid #fff; font-weight: bold;">3</div>
                        <span class="fw-bold text-muted d-block step-text">Lampiran Bukti</span>
                    </div>
                </div>
            </div>
        </div>

        <form id="laporan-kegiatan-form" action="{{ route('laporan_kegiatan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="action" id="form-action" value="diajukan">
            @if(isset($isLaporanLangsung) && $isLaporanLangsung)
                <input type="hidden" name="is_laporan_langsung" value="1">
            @else
                <input type="hidden" name="rencana_kegiatan_id" value="{{ $rencanaKegiatan?->uuid }}">
            @endif

            <!-- STEP 1: Realisasi Kegiatan -->
            <div class="wizard-step" id="step-1">
                <!-- Detail Pelaksanaan Kegiatan -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark">
                            <i class="fas fa-tasks mr-1"></i> Detail Pelaksanaan Kegiatan
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(isset($isLaporanLangsung) && $isLaporanLangsung)
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                                    <input type="text" name="judul_kegiatan" class="form-control" placeholder="Masukkan judul kegiatan..." required value="{{ old('judul_kegiatan') }}">
                                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Tuliskan judul kegiatan yang jelas dan spesifik sesuai pelaksanaan di lapangan.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Lokasi Kegiatan <span class="text-danger">*</span></label>
                                    <input type="text" name="lokasi_kegiatan" class="form-control" placeholder="Masukkan lokasi kejadian..." required value="{{ old('lokasi_kegiatan') }}">
                                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Sebutkan lokasi spesifik tempat kegiatan berlangsung (nama desa/kecamatan/kabupaten).</small>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Realisasi Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="realisasi_tanggal_mulai" class="form-control" required value="{{ old('realisasi_tanggal_mulai') }}">
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Tanggal aktual kegiatan mulai dilaksanakan di lapangan.</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Realisasi Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="realisasi_tanggal_selesai" class="form-control" required value="{{ old('realisasi_tanggal_selesai') }}">
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Tanggal aktual kegiatan selesai. Jika 1 hari, isi sama dengan tanggal mulai.</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Realisasi Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="realisasi_peserta" class="form-control" placeholder="Contoh: 45" min="0" required value="{{ old('realisasi_peserta') }}">
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Jumlah peserta yang benar-benar hadir berdasarkan daftar hadir.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rangkaian Kegiatan / Alur Acara <span class="text-danger">*</span></label>
                                <textarea name="rangkaian_kegiatan" class="form-control summernote-editor" rows="4" placeholder="Contoh: Kegiatan diawali dengan sambutan..." required>{!! old('rangkaian_kegiatan') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Uraikan urutan acara dari awal hingga akhir, termasuk waktu dan kegiatan di setiap sesi.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profil Peserta <span class="text-danger">*</span></label>
                                <textarea name="profil_peserta" class="form-control summernote-editor" rows="3" placeholder="Contoh: Mayoritas adalah nelayan lokal..." required>{!! old('profil_peserta') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Jelaskan latar belakang peserta: profesi, asal daerah, rentang usia, atau kelompok sasaran.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hasil dan Output Kegiatan -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark">
                            <i class="fas fa-chart-line mr-1"></i> Hasil dan Output Kegiatan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hasil yang Dicapai <span class="text-danger">*</span></label>
                                <textarea name="hasil_dicapai" class="form-control summernote-editor" rows="4" placeholder="Contoh: Seluruh bibit berhasil ditanam dengan metode baru..." required>{!! old('hasil_dicapai') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Jelaskan pencapaian utama yang diperoleh dari pelaksanaan kegiatan ini.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Output Nyata <span class="text-danger">*</span></label>
                                <textarea name="output_nyata" class="form-control summernote-editor" rows="4" placeholder="Contoh: Tersedianya 1000 bibit tertanam dan dokumen kesepakatan warga..." required>{!! old('output_nyata') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Sebutkan produk/hasil fisik yang dihasilkan, seperti dokumen, data, atau barang yang terukur.</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Dampak Awal yang Terlihat <span class="text-danger">*</span></label>
                                <textarea name="dampak_awal" class="form-control summernote-editor" rows="4" placeholder="Contoh: Warga mulai secara mandiri menjaga area tanam..." required>{!! old('dampak_awal') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Deskripsikan perubahan awal yang sudah terlihat di masyarakat atau lingkungan setelah kegiatan.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <a href="{{ (isset($isLaporanLangsung) && $isLaporanLangsung) ? route('laporan_kegiatan.index') : route('rencana_kegiatan.show', $rencanaKegiatan?->uuid ?? $rencanaKegiatan?->id) }}" class="btn btn-secondary text-white float-left"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                        <div class="float-right d-flex">
                            <button type="button" class="btn btn-secondary text-white mr-2 btn-save-draft">
                                <i class="fas fa-save mr-1"></i> Simpan Draft
                            </button>
                            <button type="button" class="btn bg-navy text-white btn-next" data-next="step-2">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Kendala dan Evaluasi -->
            <div class="wizard-step" id="step-2" style="display: none;">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Kendala dan Evaluasi
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kendala yang Dihadapi <span class="text-danger">*</span></label>
                                <textarea name="kendala" class="form-control summernote-editor" rows="4" placeholder="Contoh: Cuaca buruk sempat menunda penanaman selama 2 jam..." required>{!! old('kendala') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Sebutkan hambatan atau masalah yang muncul selama pelaksanaan kegiatan. Isi "Tidak ada" jika tidak ada kendala.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Solusi yang Dilakukan <span class="text-danger">*</span></label>
                                <textarea name="solusi" class="form-control summernote-editor" rows="4" placeholder="Contoh: Memindahkan area tanam sementara ke sisi timur pantai..." required>{!! old('solusi') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Jelaskan langkah-langkah yang diambil untuk mengatasi kendala tersebut.</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Catatan Evaluasi & Rekomendasi <span class="text-danger">*</span></label>
                                <textarea name="evaluasi_rekomendasi" class="form-control summernote-editor" rows="4" placeholder="Contoh: Ke depannya perlu persiapan terpal untuk antisipasi hujan..." required>{!! old('evaluasi_rekomendasi') !!}</textarea>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Berikan penilaian keseluruhan dan saran perbaikan untuk kegiatan serupa di masa depan.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <button type="button" class="btn btn-secondary text-white btn-prev float-left" data-prev="step-1"><i class="fas fa-arrow-left mr-1"></i> Sebelumnya</button>
                        <div class="float-right d-flex">
                            <button type="button" class="btn btn-secondary text-white mr-2 btn-save-draft">
                                <i class="fas fa-save mr-1"></i> Simpan Draft
                            </button>
                            <button type="button" class="btn bg-navy text-white btn-next" data-next="step-3">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Dokumentasi Kegiatan -->
            <div class="wizard-step" id="step-3" style="display: none;">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark">
                            <i class="fas fa-file-upload mr-1"></i> Lampiran Dokumentasi Kegiatan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Foto Kegiatan <span class="text-danger">*</span></label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="foto_kegiatan[]" class="custom-file-input custom-img-input" id="fotoInput" accept="image/jpeg,image/jpg,image/png" multiple>
                                    <label class="custom-file-label" for="fotoInput">Pilih foto...</label>
                                </div>
                                <small class="text-muted">Maksimal 10 foto (JPG/PNG), Max 3MB/foto.</small>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Unggah foto dokumentasi pelaksanaan kegiatan seperti suasana acara, narasumber, dan peserta.</small>
                                <div id="preview-foto_kegiatan" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Daftar Hadir <span class="text-danger">*</span></label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="daftar_hadir[]" class="custom-file-input custom-doc-input" id="daftarHadirInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" multiple>
                                    <label class="custom-file-label" for="daftarHadirInput">Pilih file...</label>
                                </div>
                                <small class="text-muted">Maksimal 5 file (PDF/DOC/XLS/JPG/PNG), Max 5MB/file.</small>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Unggah scan/foto daftar hadir yang sudah ditandatangani oleh peserta kegiatan.</small>
                                <div id="preview-daftar_hadir" class="d-flex flex-column gap-1 mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Notulen</label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="notulen[]" class="custom-file-input custom-doc-input" id="notulenInput" accept=".pdf,.doc,.docx" multiple>
                                    <label class="custom-file-label" for="notulenInput">Pilih file...</label>
                                </div>
                                <small class="text-muted">Maksimal 3 file (PDF/DOC), Max 5MB/file.</small>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Unggah catatan hasil rapat/diskusi selama kegiatan berlangsung (opsional).</small>
                                <div id="preview-notulen" class="d-flex flex-column gap-1 mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Materi <span class="text-danger">*</span></label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="materi[]" class="custom-file-input custom-doc-input" id="materiInput" accept=".pdf,.ppt,.pptx,.doc,.docx" multiple>
                                    <label class="custom-file-label" for="materiInput">Pilih file...</label>
                                </div>
                                <small class="text-muted">Maksimal 5 file (PDF/PPT/DOC), Max 10MB/file.</small>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Unggah materi presentasi, modul, atau bahan ajar yang digunakan saat kegiatan.</small>
                                <div id="preview-materi" class="d-flex flex-column gap-1 mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Berita Acara <span class="text-danger">*</span></label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="berita_acara[]" class="custom-file-input custom-doc-input" id="beritaAcaraInput" accept=".pdf,.doc,.docx" multiple required>
                                    <label class="custom-file-label" for="beritaAcaraInput">Pilih file...</label>
                                </div>
                                <small class="text-muted">Maksimal 3 file (PDF/DOC), Max 5MB/file.</small>
                                <small class="text-muted d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Unggah berita acara resmi pelaksanaan kegiatan.</small>
                                <div id="preview-berita_acara" class="d-flex flex-column gap-1 mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <button type="button" class="btn btn-secondary text-white btn-prev float-left" data-prev="step-2"><i class="fas fa-arrow-left mr-1"></i> Sebelumnya</button>
                        
                        <div class="float-right d-flex">
                            <button type="button" class="btn btn-secondary text-white mr-2 btn-save-draft">
                                <i class="fas fa-save mr-1"></i> Simpan Draft
                            </button>
                            <button type="submit" name="action" value="diajukan" class="btn bg-navy text-white">
                                <i class="fas fa-paper-plane mr-1"></i> Kirim Laporan ke Admin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <style>
            .step-circle { transition: all 0.3s ease; }
            .step-text { font-size: 0.85rem; margin-top: 5px; }
            .progress { overflow: visible; background-color: #e9ecef; }
            .preview-img-wrapper { position: relative; display: inline-block; margin-right: 10px; margin-bottom: 10px; }
            .preview-img-wrapper img { max-width: 150px; max-height: 110px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #ddd; }
            .preview-file-item { display: flex; align-items: center; padding: 8px 12px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 5px; font-size: 0.9rem; }
            .preview-file-item > i:first-child { font-size: 1.2rem; color: #6c757d; margin-right: 10px; }
            .gap-2 { gap: 0.5rem; }
            .gap-1 { gap: 0.25rem; }
        </style>
    @endpush

    @push('scripts')
        <script src="/public/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                bsCustomFileInput.init();

                // --- WIZARD NAVIGATION ---
                let currentStepIndex = 1;
                const totalSteps = 3;

                function showStep(stepIndex) {
                    $('.wizard-step').hide();
                    $('#step-' + stepIndex).fadeIn(300);

                    for (let i = 1; i <= totalSteps; i++) {
                        let indicator = $('#indicator-' + i);
                        let circle = indicator.find('.step-circle');
                        let text = indicator.find('.step-text');
                        
                        if (i < stepIndex) {
                            circle.removeClass('bg-secondary bg-primary text-muted').addClass('bg-success text-white');
                            circle.html('<i class="fas fa-check"></i>');
                            text.removeClass('text-muted text-primary').addClass('text-success');
                        } else if (i === stepIndex) {
                            circle.removeClass('bg-secondary bg-success text-muted').addClass('bg-primary text-white');
                            circle.html(i);
                            text.removeClass('text-muted text-success').addClass('text-primary');
                        } else {
                            circle.removeClass('bg-primary bg-success text-white').addClass('bg-secondary text-white');
                            circle.html(i);
                            text.removeClass('text-primary text-success').addClass('text-muted');
                        }
                    }

                    let progressPercent = ((stepIndex - 1) / (totalSteps - 1)) * 100;
                    $('#wizard-progress').css('width', progressPercent + '%');
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                $('.btn-next').click(function() {
                    let nextStepId = $(this).data('next');
                    let nextIndex = parseInt(nextStepId.split('-')[1]);
                    
                    let currentStep = $(this).closest('.wizard-step');
                    let isValid = true;
                    
                    // 1. Validate normal required inputs/selects (visible ones)
                    let requiredInputs = currentStep.find('input[required]:visible, select[required]:visible, textarea[required]:not(.summernote-editor)');
                    requiredInputs.each(function() {
                        if (!this.checkValidity()) {
                            isValid = false;
                            this.reportValidity();
                            return false; // break loop
                        }
                    });

                    if (!isValid) return false;

                    // 2. Validate Summernote required editors in current step
                    currentStep.find('textarea.summernote-editor[required]').each(function() {
                        let $el = $(this);
                        let code = $el.summernote('code');
                        let cleanText = $('<div>').html(code).text().trim();
                        if (!cleanText || cleanText === '') {
                            isValid = false;
                            $el.val('');
                            let labelText = $el.closest('.mb-3').find('label.form-label').first().text().replace('*', '').trim() || 'Kolom isian';
                            alert('Mohon lengkapi ' + labelText + ' terlebih dahulu.');
                            $el.summernote('focus');
                            return false; // break loop
                        } else {
                            $el.val(code);
                        }
                    });

                    if (isValid) {
                        currentStepIndex = nextIndex;
                        showStep(currentStepIndex);
                    }
                });

                $('.btn-save-draft').click(function(e) {
                    e.preventDefault();
                    let form = document.getElementById('laporan-kegiatan-form');
                    let judulInput = document.querySelector('input[name="judul_kegiatan"]');
                    
                    if (judulInput && !judulInput.value.trim()) {
                        alert('Mohon isi Judul Kegiatan terlebih dahulu untuk menyimpan sebagai draft.');
                        showStep(1);
                        judulInput.focus();
                        return false;
                    }

                    $('#form-action').val('draft');
                    if (form) {
                        form.noValidate = true;
                        form.submit();
                    }
                });

                $('.btn-prev').click(function() {
                    let prevStepId = $(this).data('prev');
                    currentStepIndex = parseInt(prevStepId.split('-')[1]);
                    showStep(currentStepIndex);
                });

                showStep(currentStepIndex);

                // --- FILE UPLOAD BUFFER & PREVIEW MANAGER ---
                const fileBuffers = {
                    foto_kegiatan: [],
                    daftar_hadir: [],
                    notulen: [],
                    materi: [],
                    berita_acara: []
                };

                const inputIdMap = {
                    foto_kegiatan: 'fotoInput',
                    daftar_hadir: 'daftarHadirInput',
                    notulen: 'notulenInput',
                    materi: 'materiInput',
                    berita_acara: 'beritaAcaraInput'
                };

                const fieldConfigs = {
                    foto_kegiatan: { maxFiles: 10, maxSize: 3 * 1024 * 1024, sizeLabel: '3MB' },
                    daftar_hadir:  { maxFiles: 5,  maxSize: 5 * 1024 * 1024, sizeLabel: '5MB' },
                    notulen:       { maxFiles: 3,  maxSize: 5 * 1024 * 1024, sizeLabel: '5MB' },
                    materi:        { maxFiles: 5,  maxSize: 10 * 1024 * 1024, sizeLabel: '10MB' },
                    berita_acara:  { maxFiles: 3,  maxSize: 5 * 1024 * 1024, sizeLabel: '5MB' }
                };

                function handleFileUpload(fieldName, inputElement, isImage) {
                    const files = Array.from(inputElement.files);
                    const config = fieldConfigs[fieldName] || { maxFiles: 10, maxSize: 5 * 1024 * 1024, sizeLabel: '5MB' };
                    const currentBuffer = fileBuffers[fieldName];

                    if (currentBuffer.length + files.length > config.maxFiles) {
                        alert(`Maksimal ${config.maxFiles} file. Saat ini ada ${currentBuffer.length} file.`);
                        syncInputAndRender(fieldName, inputElement, isImage);
                        return;
                    }

                    files.forEach(file => {
                        if (file.size > config.maxSize) {
                            alert(`File "${file.name}" terlalu besar. Maksimal ukuran ${config.sizeLabel}/file.`);
                            return;
                        }

                        if (!currentBuffer.some(f => f.name === file.name && f.size === file.size)) {
                            currentBuffer.push(file);
                        }
                    });

                    syncInputAndRender(fieldName, inputElement, isImage);
                }

                window.removeFileFromBuffer = function(fieldName, index) {
                    fileBuffers[fieldName].splice(index, 1);
                    const inputElement = document.getElementById(inputIdMap[fieldName]);
                    if (inputElement) {
                        syncInputAndRender(fieldName, inputElement, fieldName === 'foto_kegiatan');
                    }
                };

                function syncInputAndRender(fieldName, inputElement, isImage) {
                    const dt = new DataTransfer();
                    fileBuffers[fieldName].forEach(file => dt.items.add(file));
                    inputElement.files = dt.files;

                    const previewContainer = document.getElementById('preview-' + fieldName);
                    if (!previewContainer) return;
                    previewContainer.innerHTML = '';

                    if (isImage) {
                        fileBuffers[fieldName].forEach((file, idx) => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const wrapper = document.createElement('div');
                                wrapper.className = 'position-relative preview-img-wrapper mr-2 mb-2';
                                wrapper.style.display = 'inline-block';
                                wrapper.innerHTML = `
                                    <img src="${e.target.result}" style="max-width:110px; max-height:85px; object-fit:cover; border-radius:6px; border:1px solid #ddd; box-shadow:0 2px 4px rgba(0,0,0,0.1);" title="${file.name}">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute shadow"
                                            style="top:-6px; right:-6px; border-radius:50%; width:22px; height:22px; padding:0; display:flex; align-items:center; justify-content:center; z-index:10;"
                                            onclick="removeFileFromBuffer('${fieldName}', ${idx})" title="Hapus foto ini">
                                        <i class="fas fa-times" style="font-size:11px;"></i>
                                    </button>
                                `;
                                previewContainer.appendChild(wrapper);
                            };
                            reader.readAsDataURL(file);
                        });
                    } else {
                        fileBuffers[fieldName].forEach((file, idx) => {
                            let icon = 'fa-file-alt text-secondary';
                            const nameLower = file.name.toLowerCase();
                            if (nameLower.endsWith('.pdf')) icon = 'fa-file-pdf text-danger';
                            else if (nameLower.endsWith('.doc') || nameLower.endsWith('.docx')) icon = 'fa-file-word text-primary';
                            else if (nameLower.endsWith('.xls') || nameLower.endsWith('.xlsx')) icon = 'fa-file-excel text-success';
                            else if (nameLower.endsWith('.ppt') || nameLower.endsWith('.pptx')) icon = 'fa-file-powerpoint text-warning';

                            const div = document.createElement('div');
                            div.className = 'preview-file-item position-relative p-2 mb-2 border rounded bg-white shadow-sm';
                            div.style.paddingRight = '25px';
                            div.innerHTML = `
                                <div class="d-flex align-items-center text-truncate mr-2" style="max-width: 90%;">
                                    <i class="fas ${icon} mr-2" style="font-size:1.2rem;"></i>
                                    <div class="text-truncate">
                                        <div class="text-truncate font-weight-bold" style="font-size:0.85rem;" title="${file.name}">${file.name}</div>
                                        <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger position-absolute shadow"
                                        style="top:-6px; right:-6px; border-radius:50%; width:22px; height:22px; padding:0; display:flex; align-items:center; justify-content:center; z-index:10;"
                                        onclick="removeFileFromBuffer('${fieldName}', ${idx})" title="Hapus file ini">
                                    <i class="fas fa-times text-white" style="color:#ffffff !important; font-size:11px !important; line-height:1 !important; margin:0 !important;"></i>
                                </button>
                            `;
                            previewContainer.appendChild(div);
                        });
                    }
                }

                $('.custom-img-input').on('change', function() {
                    const fieldName = $(this).attr('name').replace('[]', '');
                    handleFileUpload(fieldName, this, true);
                });

                $('.custom-doc-input').on('change', function() {
                    const fieldName = $(this).attr('name').replace('[]', '');
                    handleFileUpload(fieldName, this, false);
                });
            });

            // Wait for jQuery & Initialize Summernote
            function waitForJQuery() {
                if (typeof $ !== 'undefined') {
                    $.getScript('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js', function() {
                        $('.summernote-editor').each(function() {
                            $(this).summernote({
                                placeholder: $(this).attr('placeholder'),
                                toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['fontname', ['fontname']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']]
                            ],
                            height: 120
                            });
                        });
                    });
                } else {
                    setTimeout(waitForJQuery, 100);
                }
            }
            waitForJQuery();
        </script>
    @endpush
@endsection