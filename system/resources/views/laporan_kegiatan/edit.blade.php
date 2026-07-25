@extends('layouts.adminlte')

@section('content_title', 'Edit Laporan Kegiatan')

@section('content')
    <div class="container pb-5 text-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-file-signature text-dark mr-2"></i> Form Edit Laporan Kegiatan</h5>
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

        @if(!$laporanKegiatan->isDarurat())
            <!-- CALLOUT: Header Konteks Rencana Kegiatan -->
            <div class="card shadow-sm mb-4 bg-white p-3" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                <h6 class="text-dark fw-bold mb-3"><i class="fas fa-info-circle mr-1 text-secondary"></i> Konteks Rencana Kegiatan</h6>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Nama Kegiatan:</strong><br>
                        <span class="text-dark">{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</span>
                    </div>
                    <div class="col-md-4">
                        <strong>Waktu & Lokasi Rencana:</strong><br>
                        <span class="text-dark">
                            {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_selesai)->translatedFormat('d M Y') }}<br>
                            Di {{ $laporanKegiatan->rencanaKegiatan->desa }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <strong>Target Peserta:</strong><br>
                        <span class="text-dark">{{ $laporanKegiatan->rencanaKegiatan->estimasi_peserta ?? '-' }} Orang</span>
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

        <form id="laporan-kegiatan-form" action="{{ route('laporan_kegiatan.update', $laporanKegiatan->uuid ?? $laporanKegiatan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                        @if($laporanKegiatan->isDarurat())
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                                    <input type="text" name="judul_kegiatan" class="form-control" placeholder="Masukkan judul kegiatan..." required value="{{ old('judul_kegiatan', $laporanKegiatan->judul_kegiatan) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Lokasi Kegiatan <span class="text-danger">*</span></label>
                                    <input type="text" name="lokasi_kegiatan" class="form-control" placeholder="Masukkan lokasi kejadian..." required value="{{ old('lokasi_kegiatan', $laporanKegiatan->lokasi_kegiatan) }}">
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Realisasi Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="realisasi_tanggal_mulai" class="form-control" required value="{{ old('realisasi_tanggal_mulai', $laporanKegiatan->realisasi_tanggal_mulai) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Realisasi Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="realisasi_tanggal_selesai" class="form-control" required value="{{ old('realisasi_tanggal_selesai', $laporanKegiatan->realisasi_tanggal_selesai) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Realisasi Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="realisasi_peserta" class="form-control" placeholder="Contoh: 45" min="0" required value="{{ old('realisasi_peserta', $laporanKegiatan->realisasi_peserta) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rangkaian Kegiatan / Alur Acara <span class="text-danger">*</span></label>
                                <textarea name="rangkaian_kegiatan" class="form-control summernote-editor" rows="4" placeholder="Contoh: Kegiatan diawali dengan sambutan..." required>{!! old('rangkaian_kegiatan', $laporanKegiatan->rangkaian_kegiatan) !!}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profil Peserta <span class="text-danger">*</span></label>
                                <textarea name="profil_peserta" class="form-control summernote-editor" rows="3" placeholder="Contoh: Mayoritas adalah nelayan lokal..." required>{!! old('profil_peserta', $laporanKegiatan->profil_peserta) !!}</textarea>
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
                                <textarea name="hasil_dicapai" class="form-control summernote-editor" rows="4" placeholder="Contoh: Seluruh bibit berhasil ditanam dengan metode baru..." required>{!! old('hasil_dicapai', $laporanKegiatan->hasil_dicapai) !!}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Output Nyata <span class="text-danger">*</span></label>
                                <textarea name="output_nyata" class="form-control summernote-editor" rows="4" placeholder="Contoh: Tersedianya 1000 bibit tertanam dan dokumen kesepakatan warga..." required>{!! old('output_nyata', $laporanKegiatan->output_nyata) !!}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Dampak Awal yang Terlihat <span class="text-danger">*</span></label>
                                <textarea name="dampak_awal" class="form-control summernote-editor" rows="4" placeholder="Contoh: Warga mulai secara mandiri menjaga area tanam..." required>{!! old('dampak_awal', $laporanKegiatan->dampak_awal) !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <a href="{{ route('laporan_kegiatan.show', $laporanKegiatan->uuid ?? $laporanKegiatan->id) }}" class="btn btn-secondary text-white float-left"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                        <button type="button" class="btn bg-navy text-white btn-next float-right" data-next="step-2">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
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
                                <label class="form-label">Kendala yang Dihadapi</label>
                                <textarea name="kendala" class="form-control summernote-editor" rows="4" placeholder="Contoh: Cuaca buruk sempat menunda penanaman selama 2 jam...">{!! old('kendala', $laporanKegiatan->kendala) !!}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Solusi yang Dilakukan</label>
                                <textarea name="solusi" class="form-control summernote-editor" rows="4" placeholder="Contoh: Memindahkan area tanam sementara ke sisi timur pantai...">{!! old('solusi', $laporanKegiatan->solusi) !!}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Catatan Evaluasi & Rekomendasi</label>
                                <textarea name="evaluasi_rekomendasi" class="form-control summernote-editor" rows="4" placeholder="Contoh: Ke depannya perlu persiapan terpal untuk antisipasi hujan...">{!! old('evaluasi_rekomendasi', $laporanKegiatan->evaluasi_rekomendasi) !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <button type="button" class="btn btn-secondary text-white btn-prev float-left" data-prev="step-1"><i class="fas fa-arrow-left mr-1"></i> Sebelumnya</button>
                        <button type="button" class="btn bg-navy text-white btn-next float-right" data-next="step-3">Selanjutnya <i class="fas fa-arrow-right ml-1"></i></button>
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
                                @php $fotos = !empty($laporanKegiatan->foto_kegiatan) ? (is_string($laporanKegiatan->foto_kegiatan) ? json_decode($laporanKegiatan->foto_kegiatan, true) : $laporanKegiatan->foto_kegiatan) : []; @endphp
                                @if(is_array($fotos) && count($fotos) > 0)
                                    <div class="mb-2">
                                        <p class="mb-1 fw-bold text-muted" style="font-size: 0.85rem;"><i class="fas fa-camera text-primary mr-1"></i> Foto saat ini:</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($fotos as $foto)
                                                @php $path = is_array($foto) ? $foto['path'] : $foto; @endphp
                                                <div class="preview-img-wrapper" style="margin:0;">
                                                    <a href="/public/storage/app/{{ $path }}" target="_blank">
                                                        <img src="/public/storage/app/{{ $path }}" style="max-width: 100px; height: 75px;">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <label class="form-label fw-bold mt-2">Foto Kegiatan</label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="foto_kegiatan[]" class="custom-file-input custom-img-input" id="fotoInput" accept="image/jpeg,image/jpg,image/png" multiple>
                                    <label class="custom-file-label" for="fotoInput">Biarkan kosong jika tidak diubah...</label>
                                </div>
                                <small class="text-muted">Maksimal 10 foto (JPG/PNG), Max 3MB/foto.</small>
                                <div id="preview-foto_kegiatan" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                @php $daftar_hadir = !empty($laporanKegiatan->daftar_hadir) ? (is_string($laporanKegiatan->daftar_hadir) ? json_decode($laporanKegiatan->daftar_hadir, true) : $laporanKegiatan->daftar_hadir) : []; @endphp
                                @if(is_array($daftar_hadir) && count($daftar_hadir) > 0)
                                    <div class="mb-2">
                                        <p class="mb-1 fw-bold text-muted" style="font-size: 0.85rem;"><i class="fas fa-file-alt text-info mr-1"></i> Dokumen saat ini:</p>
                                        @foreach($daftar_hadir as $file)
                                            @php
                                                $path = is_array($file) ? $file['path'] : $file;
                                                $name = is_array($file) ? $file['original_name'] : basename($file);
                                            @endphp
                                            <div class="preview-file-item bg-white">
                                                <i class="fas fa-file-pdf text-danger"></i> <a href="/public/storage/app/{{ $path }}" target="_blank" class="text-truncate">{{ $name }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <label class="form-label fw-bold mt-2">Daftar Hadir</label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="daftar_hadir[]" class="custom-file-input custom-doc-input" id="daftarHadirInput" accept=".pdf,.doc,.docx,.xls,.xlsx" multiple>
                                    <label class="custom-file-label" for="daftarHadirInput">Biarkan kosong jika tidak diubah...</label>
                                </div>
                                <small class="text-muted">Maksimal 10 file (PDF/DOC/XLS), Max 3MB/file.</small>
                                <div id="preview-daftar_hadir" class="d-flex flex-column gap-1 mt-2"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                @php $notulen = !empty($laporanKegiatan->notulen) ? (is_string($laporanKegiatan->notulen) ? json_decode($laporanKegiatan->notulen, true) : $laporanKegiatan->notulen) : []; @endphp
                                @if(is_array($notulen) && count($notulen) > 0)
                                    <div class="mb-2">
                                        <p class="mb-1 fw-bold text-muted" style="font-size: 0.85rem;"><i class="fas fa-file-alt text-info mr-1"></i> Dokumen saat ini:</p>
                                        @foreach($notulen as $file)
                                            @php
                                                $path = is_array($file) ? $file['path'] : $file;
                                                $name = is_array($file) ? $file['original_name'] : basename($file);
                                            @endphp
                                            <div class="preview-file-item bg-white">
                                                <i class="fas fa-file-pdf text-danger"></i> <a href="/public/storage/app/{{ $path }}" target="_blank" class="text-truncate">{{ $name }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <label class="form-label fw-bold mt-2">Notulen</label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="notulen[]" class="custom-file-input custom-doc-input" id="notulenInput" accept=".pdf,.doc,.docx" multiple>
                                    <label class="custom-file-label" for="notulenInput">Biarkan kosong jika tidak diubah...</label>
                                </div>
                                <small class="text-muted">Maksimal 10 file (PDF/DOC), Max 3MB/file.</small>
                                <div id="preview-notulen" class="d-flex flex-column gap-1 mt-2"></div>
                            </div>

                            
                            <div class="col-md-6 mb-4">
                                @php $materi = !empty($laporanKegiatan->materi) ? (is_string($laporanKegiatan->materi) ? json_decode($laporanKegiatan->materi, true) : $laporanKegiatan->materi) : []; @endphp
                                @if(is_array($materi) && count($materi) > 0)
                                    <div class="mb-2">
                                        <p class="mb-1 fw-bold text-muted" style="font-size: 0.85rem;"><i class="fas fa-file-alt text-info mr-1"></i> Dokumen saat ini:</p>
                                        @foreach($materi as $file)
                                            @php
                                                $path = is_array($file) ? $file['path'] : $file;
                                                $name = is_array($file) ? $file['original_name'] : basename($file);
                                            @endphp
                                            <div class="preview-file-item bg-white">
                                                <i class="fas fa-file-powerpoint text-warning"></i> <a href="/public/storage/app/{{ $path }}" target="_blank" class="text-truncate">{{ $name }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <label class="form-label fw-bold mt-2">Materi</label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="materi[]" class="custom-file-input custom-doc-input" id="materiInput" accept=".pdf,.ppt,.pptx,.doc,.docx" multiple>
                                    <label class="custom-file-label" for="materiInput">Biarkan kosong jika tidak diubah...</label>
                                </div>
                                <small class="text-muted">Maksimal 10 file (PDF/PPT/DOC), Max 3MB/file.</small>
                                <div id="preview-materi" class="d-flex flex-column gap-1 mt-2"></div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                @php $berita_acara = !empty($laporanKegiatan->berita_acara) ? (is_string($laporanKegiatan->berita_acara) ? json_decode($laporanKegiatan->berita_acara, true) : $laporanKegiatan->berita_acara) : []; @endphp
                                @if(is_array($berita_acara) && count($berita_acara) > 0)
                                    <div class="mb-2">
                                        <p class="mb-1 fw-bold text-muted" style="font-size: 0.85rem;"><i class="fas fa-file-alt text-info mr-1"></i> Dokumen saat ini:</p>
                                        @foreach($berita_acara as $file)
                                            @php
                                                $path = is_array($file) ? $file['path'] : $file;
                                                $name = is_array($file) ? $file['original_name'] : basename($file);
                                            @endphp
                                            <div class="preview-file-item bg-white">
                                                <i class="fas fa-file-pdf text-danger"></i> <a href="/public/storage/app/{{ $path }}" target="_blank" class="text-truncate">{{ $name }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <label class="form-label fw-bold mt-2">Berita Acara</label>
                                <div class="custom-file mb-1">
                                    <input type="file" name="berita_acara[]" class="custom-file-input custom-doc-input" id="beritaAcaraInput" accept=".pdf,.doc,.docx" multiple>
                                    <label class="custom-file-label" for="beritaAcaraInput">Biarkan kosong jika tidak diubah...</label>
                                </div>
                                <small class="text-muted">Maksimal 10 file (PDF/DOC), Max 3MB/file.</small>
                                <div id="preview-berita_acara" class="d-flex flex-column gap-1 mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white clearfix">
                        <button type="button" class="btn btn-secondary text-white btn-prev float-left" data-prev="step-2"><i class="fas fa-arrow-left mr-1"></i> Sebelumnya</button>
                        
                        
                        <div class="float-right">
                            @if($laporanKegiatan->status === 'draft' || $laporanKegiatan->status === 'revisi')
                                <button type="submit" name="action" value="draft" class="btn btn-secondary text-white mr-2">
                                    <i class="fas fa-save mr-1"></i> Simpan Draft
                                </button>
                                <button type="submit" name="action" value="diajukan" class="btn bg-navy text-white">
                                    <i class="fas fa-paper-plane mr-1"></i> Ajukan Laporan
                                </button>
                            @else
                                <button type="submit" name="action" value="update" class="btn bg-navy text-white">
                                    <i class="fas fa-save mr-1"></i> Perbarui Laporan
                                </button>
                            @endif
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
            .preview-file-item i { font-size: 1.2rem; color: #6c757d; margin-right: 10px; }
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
                    
                    // Simple Frontend Validation on current step before moving
                    let currentStep = $(this).closest('.wizard-step');
                    let requiredInputs = currentStep.find('input[required], select[required], textarea[required]');
                    let isValid = true;
                    
                    requiredInputs.each(function() {
                        if (!this.checkValidity()) {
                            isValid = false;
                            this.reportValidity();
                            return false; // break loop
                        }
                    });

                    if (isValid) {
                        currentStepIndex = nextIndex;
                        showStep(currentStepIndex);
                    }
                });

                $('.btn-prev').click(function() {
                    let prevStepId = $(this).data('prev');
                    currentStepIndex = parseInt(prevStepId.split('-')[1]);
                    showStep(currentStepIndex);
                });

                showStep(currentStepIndex);

                // --- FILE UPLOAD PREVIEW ---
                function createImagePreview(input, previewContainer) {
                    const files = input.files;
                    previewContainer.innerHTML = '';
                    
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (!file.type.startsWith('image/')) continue;
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'preview-img-wrapper';
                            wrapper.innerHTML = `
                                <img src="${e.target.result}" title="${file.name}">
                            `;
                            previewContainer.appendChild(wrapper);
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
                        div.className = 'preview-file-item';
                        
                        let icon = 'fa-file-alt';
                        if(file.name.endsWith('.pdf')) icon = 'fa-file-pdf text-danger';
                        else if(file.name.endsWith('.doc') || file.name.endsWith('.docx')) icon = 'fa-file-word text-primary';
                        else if(file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) icon = 'fa-file-excel text-success';
                        
                        div.innerHTML = `
                            <i class="fas ${icon}"></i>
                            <span class="text-truncate" style="max-width: 85%;" title="${file.name}">${file.name}</span>
                        `;
                        previewContainer.appendChild(div);
                    }
                }

                $('.custom-img-input').on('change', function() {
                    const targetId = $(this).attr('name').replace('[]', '');
                    createImagePreview(this, document.getElementById('preview-' + targetId));
                });

                $('.custom-doc-input').on('change', function() {
                    let targetId = $(this).attr('name').replace('[]', '');
                    createFilePreview(this, document.getElementById('preview-' + targetId));
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