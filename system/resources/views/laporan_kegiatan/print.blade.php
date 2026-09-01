@extends('layouts.print')

@section('content')
    <div class="container-fluid">
        <!-- Watermark Logo -->
        <div class="watermark-container">
            <img src="/public/adminlte/dist/img/logo_webe.png" class="watermark-logo" alt="Watermark Logo">
        </div>

        <!-- Header Print (menggunakan CSS @page) -->
        @php
    $bulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    $tanggalIndo = now()->format('d') . ' ' . $bulan[now()->format('F')] . ' ' . now()->format('Y');

    $formatTextList = function ($text) {
        if (empty($text)) return '-';
        
        // If text already contains HTML list tags like <ol>, <ul>, <p>, <br>
        if (strip_tags($text) !== $text) {
            return $text;
        }

        // Try splitting numbered points like "1. ", "2. ", "3. " or "1) ", "2) "
        $pattern = '/(?:\r?\n|\s)*(?=\d+[\.\)]\s+)/';
        $items = preg_split($pattern, trim($text), -1, PREG_SPLIT_NO_EMPTY);

        if (count($items) > 1) {
            $html = '<ol style="margin: 0; padding-left: 18px; text-align: justify; line-height: 1.5;">';
            foreach ($items as $item) {
                $cleanItem = preg_replace('/^\d+[\.\)]\s*/', '', trim($item));
                if (!empty($cleanItem)) {
                    $html .= '<li style="margin-bottom: 4px;">' . e($cleanItem) . '</li>';
                }
            }
            $html .= '</ol>';
            return $html;
        }

        // If split by lines (\n)
        $lines = array_filter(array_map('trim', explode("\n", trim($text))));
        if (count($lines) > 1) {
            $html = '<ul style="margin: 0; padding-left: 18px; text-align: justify; line-height: 1.5; list-style-type: disc;">';
            foreach ($lines as $line) {
                $html .= '<li style="margin-bottom: 4px;">' . e($line) . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }

        return '<div style="text-align: justify; line-height: 1.5;">' . nl2br(e($text)) . '</div>';
    };
@endphp

        <!-- Header Web (tidak muncul saat print) -->
        <div class="web-header no-print mb-4">
            <div class="col-12 text-center">
                <h2 class="text-dark" style="font-family: 'Times New Roman', Times, serif; font-weight: bold;">LAPORAN KEGIATAN</h2>
                <h3 style="font-family: 'Times New Roman', Times, serif;">{{ $laporanKegiatan->isDarurat() ? $laporanKegiatan->judul_kegiatan : $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</h3>
                <p class="text-muted">Tanggal Cetak: {{ $tanggalIndo }}</p>
            </div>
        </div>


        <!-- Kop Surat -->
        <table class="kop-surat mb-4" style="width: 100%; border-bottom: 3px solid black; margin-bottom: 10px;">
            <tr>
                <td style="width: 15%; text-align: center;">
                    <img src="/public/adminlte/dist/img/logo_webe.png" alt="Logo Yayasan WeBe" style="width: 90px; height: auto;">
                </td>
                <td style="width: 85%; text-align: center;">
                    <h3 style="margin: 0; font-family: 'Times New Roman', Times, serif; font-weight: bold; font-size: 16pt;">YAYASAN WEBE KONSORSIUM KONSULTAN MANAGEMENT</h3>
                    <h4 style="margin: 5px 0 0 0; font-family: 'Times New Roman', Times, serif; font-weight: bold; font-size: 14pt;">REKAM WEBE</h4>
                    <p style="margin: 5px 0 0 0; font-family: 'Times New Roman', Times, serif; font-size: 10pt;">Jl. Pelabuhan Kiri Kendawangan, Kab. Ketapang, Kalimantan Barat</p>
                    <p style="margin: 2px 0 0 0; font-family: 'Times New Roman', Times, serif; font-size: 10pt;">Email: info@rekamwebe.org | Website: www.rekamwebe.org</p>
                </td>
            </tr>
        </table>

        <!-- Header Print (muncul di halaman pertama) -->
        <div class="first-page-header">
            <div class="document-header">
                <h2>LAPORAN KEGIATAN</h2>
                <h3>{{ $laporanKegiatan->isDarurat() ? $laporanKegiatan->judul_kegiatan : $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</h3>
            </div>
        </div>

        <!-- Informasi Umum -->
        <div style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; font-weight: bold; margin-bottom: 8px; margin-top: 15px;">Informasi Umum</div>
        <table class="table-borderless mb-4" style="width: 100%; border-collapse: collapse; margin-left: 30px;">
            <tr>
                <td style="width: 220px; vertical-align: top; padding: 2px 0;">Jenis Laporan</td>
                <td style="width: 15px; text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">
                    @if($laporanKegiatan->isDarurat())
                        <span class="badge badge-warning">Laporan Langsung</span>
                    @else
                        <span class="badge badge-info">Laporan Rencana Kegiatan</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Penanggung Jawab</td>
                <td style="text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">{{ $laporanKegiatan->user->name }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Status Laporan</td>
                <td style="text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">{{ ucfirst($laporanKegiatan->status) }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Tanggal Dibuat</td>
                <td style="text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">{{ $laporanKegiatan->created_at->format('d/m/Y') }}</td>
            </tr>
        </table>

        @if (!$laporanKegiatan->isDarurat())
        <!-- Informasi Rencana Kegiatan -->
        <div style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; font-weight: bold; margin-bottom: 8px; margin-top: 15px;">Informasi Rencana Kegiatan</div>
        <table class="table-borderless mb-4" style="width: 100%; border-collapse: collapse; margin-left: 30px;">
            <tr>
                <td style="width: 220px; vertical-align: top; padding: 2px 0;">Nama Rencana Kegiatan</td>
                <td style="width: 15px; text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Target Desa</td>
                <td style="text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">{{ $laporanKegiatan->rencanaKegiatan->desa }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Tanggal Rencana</td>
                <td style="text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">
                    {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->format('d/m/Y') }} 
                    s/d 
                    {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_selesai)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Estimasi Peserta</td>
                <td style="text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">{{ $laporanKegiatan->rencanaKegiatan->estimasi_peserta ?? '-' }} orang</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 2px 0;">Tanggal Disetujui</td>
                <td style="text-align: center; vertical-align: top; padding: 2px 0;">:</td>
                <td style="vertical-align: top; padding: 2px 0;">{{ $laporanKegiatan->created_at->format('d/m/Y') }}</td>
            </tr>
        </table>
        @endif

        <!-- Detail Pelaksanaan Kegiatan -->
        <div style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; font-weight: bold; margin-bottom: 8px; margin-top: 15px;">Detail Pelaksanaan Kegiatan</div>
        <table class="table-borderless mb-4" style="width: 100%; border-collapse: collapse; margin-left: 30px;">
            <tr>
                <td style="width: 220px; vertical-align: top; padding: 3px 0;">Tanggal Pelaksanaan</td>
                <td style="width: 15px; text-align: center; vertical-align: top; padding: 3px 0;">:</td>
                <td style="vertical-align: top; padding: 3px 0;">
                    {{ $laporanKegiatan->isDarurat() ? \Carbon\Carbon::parse($laporanKegiatan->realisasi_tanggal_mulai)->translatedFormat('d F Y') : ($laporanKegiatan->rencanaKegiatan->tanggal_mulai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->translatedFormat('d F Y') : '-') }}
                    @if ($laporanKegiatan->isDarurat() ? ($laporanKegiatan->realisasi_tanggal_selesai && $laporanKegiatan->realisasi_tanggal_selesai != $laporanKegiatan->realisasi_tanggal_mulai) : ($laporanKegiatan->rencanaKegiatan->tanggal_selesai && $laporanKegiatan->rencanaKegiatan->tanggal_selesai != $laporanKegiatan->rencanaKegiatan->tanggal_mulai))
                        s/d {{ \Carbon\Carbon::parse($laporanKegiatan->isDarurat() ? $laporanKegiatan->realisasi_tanggal_selesai : $laporanKegiatan->rencanaKegiatan->tanggal_selesai)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 3px 0;">Realisasi Tanggal Pelaksanaan</td>
                <td style="text-align: center; vertical-align: top; padding: 3px 0;">:</td>
                <td style="vertical-align: top; padding: 3px 0;">
                    {{ $laporanKegiatan->realisasi_tanggal_mulai ? \Carbon\Carbon::parse($laporanKegiatan->realisasi_tanggal_mulai)->translatedFormat('d F Y') : '-' }}
                    @if ($laporanKegiatan->realisasi_tanggal_selesai && $laporanKegiatan->realisasi_tanggal_selesai != $laporanKegiatan->realisasi_tanggal_mulai)
                        s/d {{ \Carbon\Carbon::parse($laporanKegiatan->realisasi_tanggal_selesai)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 3px 0;">Lokasi</td>
                <td style="text-align: center; vertical-align: top; padding: 3px 0;">:</td>
                <td style="vertical-align: top; padding: 3px 0;">{{ $laporanKegiatan->isDarurat() ? $laporanKegiatan->lokasi_kegiatan : ($laporanKegiatan->rencanaKegiatan->desa ?: '-') }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 3px 0;">Waktu Pelaksanaan</td>
                <td style="text-align: center; vertical-align: top; padding: 3px 0;">:</td>
                <td style="vertical-align: top; padding: 3px 0;">
                    @if ($laporanKegiatan->isDarurat())
                        Menyesuaikan
                    @elseif ($laporanKegiatan->rencanaKegiatan->waktu_mulai && $laporanKegiatan->rencanaKegiatan->waktu_selesai)
                        {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->waktu_selesai)->format('H:i') }}
                    @elseif ($laporanKegiatan->rencanaKegiatan->waktu_mulai)
                        {{ \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->waktu_mulai)->format('H:i') }}
                    @else
                        Belum ditentukan
                    @endif
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 4px 0;">Rangkaian Kegiatan</td>
                <td style="text-align: center; vertical-align: top; padding: 4px 0;">:</td>
                <td style="vertical-align: top; padding: 4px 0;">{!! $formatTextList($laporanKegiatan->rangkaian_kegiatan) !!}</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 3px 0;">Target Peserta</td>
                <td style="text-align: center; vertical-align: top; padding: 3px 0;">:</td>
                <td style="vertical-align: top; padding: 3px 0;">{{ $laporanKegiatan->isDarurat() ? '-' : ($laporanKegiatan->rencanaKegiatan->estimasi_peserta ?? '-') }} orang</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 3px 0;">Realisasi Peserta</td>
                <td style="text-align: center; vertical-align: top; padding: 3px 0;">:</td>
                <td style="vertical-align: top; padding: 3px 0;">{{ $laporanKegiatan->realisasi_peserta }} orang</td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding: 4px 0;">Profil Peserta</td>
                <td style="text-align: center; vertical-align: top; padding: 4px 0;">:</td>
                <td style="vertical-align: top; padding: 4px 0;">{!! $formatTextList($laporanKegiatan->profil_peserta) !!}</td>
            </tr>
        </table>

        <!-- Hasil dan Output Kegiatan -->
        <div style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; font-weight: bold; margin-bottom: 12px; margin-top: 20px;">Hasil dan Output Kegiatan</div>

        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; margin-bottom: 5px;">Hasil yang Dicapai</div>
            <div>{!! $formatTextList($laporanKegiatan->hasil_dicapai) !!}</div>
        </div>

        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; margin-bottom: 5px;">Output Nyata</div>
            <div>{!! $formatTextList($laporanKegiatan->output_nyata) !!}</div>
        </div>

        <div style="margin-bottom: 15px;">
            <div style="font-weight: bold; margin-bottom: 5px;">Dampak Awal yang Terlihat</div>
            <div>{!! $formatTextList($laporanKegiatan->dampak_awal) !!}</div>
        </div>

        <!-- Kendala dan Evaluasi -->
        <div style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; font-weight: bold; margin-bottom: 12px; margin-top: 20px;">Kendala dan Evaluasi</div>
        
        @if($laporanKegiatan->kendala)
            <div style="margin-bottom: 15px;">
                <div style="font-weight: bold; margin-bottom: 5px;">Kendala yang Dihadapi</div>
                <div>{!! $formatTextList($laporanKegiatan->kendala) !!}</div>
            </div>
        @endif

        @if($laporanKegiatan->solusi)
            <div style="margin-bottom: 15px;">
                <div style="font-weight: bold; margin-bottom: 5px;">Solusi yang Dilakukan</div>
                <div>{!! $formatTextList($laporanKegiatan->solusi) !!}</div>
            </div>
        @endif

        @if($laporanKegiatan->evaluasi_rekomendasi)
            <div style="margin-bottom: 15px;">
                <div style="font-weight: bold; margin-bottom: 5px;">Evaluasi dan Rekomendasi</div>
                <div>{!! $formatTextList($laporanKegiatan->evaluasi_rekomendasi) !!}</div>
            </div>
        @endif

        <!-- Dokumentasi Kegiatan -->
        <div style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; font-weight: bold; margin-bottom: 12px; margin-top: 20px;">Dokumentasi Kegiatan</div>

        @if (!empty($laporanKegiatan->foto_kegiatan))
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold; margin-bottom: 3px;">Foto Kegiatan</div>
                <div class="documentation-grid">
                    @foreach ($laporanKegiatan->foto_kegiatan as $index => $foto_kegiatan)
                        @php
                            $filePath = is_array($foto_kegiatan) ? $foto_kegiatan['path'] : $foto_kegiatan;
                            $fileName = is_array($foto_kegiatan) ? $foto_kegiatan['original_name'] : basename($foto_kegiatan);
                        @endphp
                        <div class="doc-item">
                            <div class="doc-image-container">
                                <img src="/public/storage/app/{{ $filePath }}" class="doc-image" alt="{{ $fileName }}">
                            </div>
                            <div class="doc-caption">{{ $fileName }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (!empty($laporanKegiatan->daftar_hadir))
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold; margin-bottom: 3px;">Daftar Hadir</div>
                <div class="file-list">
                    @foreach ($laporanKegiatan->daftar_hadir as $index => $daftar_hadir)
                        @php
                            $filePath = is_array($daftar_hadir) ? $daftar_hadir['path'] : $daftar_hadir;
                            $fileName = is_array($daftar_hadir) ? $daftar_hadir['original_name'] : basename($daftar_hadir);
                        @endphp
                        <div style="padding: 2px 0px;">
                            <a href="/public/storage/app/{{ $filePath }}" target="_blank" style="color: blue; text-decoration: underline;">{{ $fileName }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (!empty($laporanKegiatan->notulen))
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold; margin-bottom: 3px;">Notulen</div>
                <div class="file-list">
                    @foreach ($laporanKegiatan->notulen as $index => $notulen)
                        @php
                            $filePath = is_array($notulen) ? $notulen['path'] : $notulen;
                            $fileName = is_array($notulen) ? $notulen['original_name'] : basename($notulen);
                        @endphp
                        <div style="padding: 2px 0px;">
                            <a href="/public/storage/app/{{ $filePath }}" target="_blank" style="color: blue; text-decoration: underline;">{{ $fileName }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (!empty($laporanKegiatan->materi))
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold; margin-bottom: 3px;">Materi</div>
                <div class="file-list">
                    @foreach ($laporanKegiatan->materi as $index => $materi)
                        @php
                            $filePath = is_array($materi) ? $materi['path'] : $materi;
                            $fileName = is_array($materi) ? $materi['original_name'] : basename($materi);
                        @endphp
                        <div style="padding: 2px 0px;">
                            <a href="/public/storage/app/{{ $filePath }}" target="_blank" style="color: blue; text-decoration: underline;">{{ $fileName }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (!empty($laporanKegiatan->berita_acara))
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold; margin-bottom: 3px;">Berita Acara</div>
                <div class="file-list">
                    @foreach ($laporanKegiatan->berita_acara as $index => $berita_acara)
                        @php
                            $filePath = is_array($berita_acara) ? $berita_acara['path'] : $berita_acara;
                            $fileName = is_array($berita_acara) ? $berita_acara['original_name'] : basename($berita_acara);
                        @endphp
                        <div style="padding: 2px 0px;">
                            <a href="/public/storage/app/{{ $filePath }}" target="_blank" style="color: blue; text-decoration: underline;">{{ $fileName }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif




    </div>

    <style>
        /* Watermark Styles */
        .watermark-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            display: none;
            overflow: hidden;
        }

        .watermark-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: auto;
            opacity: 0.03;
            mix-blend-mode: multiply;
            max-width: 80%;
            max-height: 80vh;
        }

/* Base styles for both print and screen */

        .garis-kop {
            border: none;
            border-bottom: 4px double black;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .no-break {
            page-break-inside: avoid;
        }

        /* 4. Pengamanan Render WYSIWYG untuk gambar di dalam konten */
        .content-box img, .value-field img {
            max-width: 100% !important;
            height: auto !important;
        }

        .label-field {
            font-weight: bold !important;
            vertical-align: top !important;
            padding: 0px 4px !important;
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            width: 180px !important;
        }

        .value-field {
            padding: 0px 4px !important;
            vertical-align: top !important;
            border: 1px solid #dee2e6 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            hyphens: auto !important;
            line-height: 1.2 !important;
        }

        .value-field p, .value-field ul, .value-field ol {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.4 !important;
        }

        .value-field ol {
            padding-left: 25px !important;
            margin-top: 5px !important;
            margin-left: 0 !important;
            list-style-position: outside !important;
        }

        .value-field ul {
            padding-left: 25px !important;
            margin-top: 5px !important;
            margin-left: 0 !important;
            list-style-position: outside !important;
        }

        .value-field li {
            margin-bottom: 4px !important;
            padding-left: 0 !important;
            margin-left: 0 !important;
        }
        
        /* Override any Summernote inline styles */
        td.value-field ol, td.value-field ul {
            padding-left: 25px !important;
            margin-left: 0 !important;
        }
        
        td.value-field li {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
        .table-borderless td {
            border: none !important;
            padding: 0px 4px; /* Sangat rapat */
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            vertical-align: top;
            line-height: 1.2 !important;
        }

        .table-borderless td:first-child {
            font-weight: normal;
            width: 150px;
        }

        .content-box {
            padding: 0 !important;
            background-color: transparent !important;
            border: none !important;
            margin-bottom: 15px !important;
            text-align: justify !important;
            line-height: 1.2 !important;
            word-wrap: break-word !important;
            font-family: \'Times New Roman\', Times, serif;
            font-size: 11pt;
        }

        .page-break-before {
            page-break-before: always !important;
        }



        .documentation-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
            page-break-inside: auto;
        }
        .doc-item {
            text-align: center;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }


        .doc-image-container {
            margin-bottom: 8px;
        }

        .doc-image {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .doc-caption {
            font-size: 11pt;
            color: black;
            font-style: italic;
        }

        .file-list {
            margin-top: 15px;
        }

        .file-item {
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 8px;
            font-size: 12pt;
        }

        @media print {
            /* Show watermark during print */
            .watermark-container {
                display: block !important;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
            }

            .watermark-logo {
                width: 350px !important;
                opacity: 0.15 !important;
                mix-blend-mode: multiply !important;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            /* Ensure content is above watermark */
            .container-fluid {
                position: relative;
                z-index: 1;
            }

            body {
                font-size: 12pt;
                line-height: 1.4;
                font-family: 'Times New Roman', Times, serif;
                color: black;
            }

            .card {
                border: none;
                page-break-inside: avoid;
                margin-bottom: 25px;
                box-shadow: none;
            }

            .card-header {
                background-color: transparent !important;
                color: black !important;
                padding: 12px 15px;
                font-weight: bold;
                font-size: 14pt;
            }

            .card-header.bg-success {
                background-color: transparent !important;
                color: black !important;
                font-weight: bold;
            }

            .card-header.bg-info {
                background-color: transparent !important;
                color: black !important;
                font-weight: bold;
            }

            .card-header.bg-warning {
                background-color: transparent !important;
                color: black !important;
                font-weight: bold;
            }

            .card-header.bg-dark {
                background-color: transparent !important;
                color: black !important;
                font-weight: bold;
            }

            .table-borderless {
                width: 100%;
                border-collapse: collapse;
            }

            .table-borderless td {
                border: none !important;
                padding: 0px 4px !important;
            }

            .table-borderless tr {
                page-break-inside: avoid;
            }

            .label-field {
                font-weight: bold !important;
                vertical-align: top !important;
                padding: 0px 4px !important;
                background-color: transparent !important;
                border: none !important;
                width: 180px !important;
            }

            .value-field {
                padding: 0px 4px !important;
                vertical-align: top !important;
                border: none !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto !important;
                line-height: 1.2 !important;
            }
            .value-field p, .value-field ul, .value-field ol {
                margin: 0 !important;
                padding: 0 !important;
                line-height: 1.4 !important;
            }

            .value-field ol {
                padding-left: 20px !important;
                margin-top: 5px !important;
            }

            .value-field li {
                margin-bottom: 3px !important;
                padding-left: 5px !important;
            }
            .table-borderless td {
                border: none !important;
                padding: 0px 4px; /* Sangat rapat */
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                vertical-align: top;
                line-height: 1.2 !important;
            }

            .table-borderless td:first-child {
                font-weight: normal;
                width: 150px;
            }

            .content-box {
            padding: 0 !important;
            background-color: transparent !important;
            border: none !important;
            margin-bottom: 15px !important;
            text-align: justify !important;
            line-height: 1.2 !important;
            word-wrap: break-word !important;
            font-family: \'Times New Roman\', Times, serif;
            font-size: 11pt;
        }
            }

            .page-break-before {
                page-break-before: always !important;
            }



        .documentation-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
            page-break-inside: auto;
        }
        .doc-item {
            text-align: center;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }


            .doc-image-container {
                margin-bottom: 8px;
            }

            .doc-image {
                max-width: 100%;
                height: 150px;
                object-fit: cover;
                border: none;
            }

            .doc-caption {
                font-size: 10pt;
                color: black;
                font-style: italic;
            }

            .file-list {
                margin-top: 15px;
            }

            .file-item {
                padding: 8px;
                background-color: transparent;
                border: none;
                border-radius: 0;
                margin-bottom: 6px;
                font-size: 11pt;
                page-break-inside: avoid;
            }

            .img-fluid {
                max-width: 100%;
                height: auto;
                border: none;
            }

            .no-print {
                display: none !important;
            }

            /* Print-specific overrides */
            .text-muted,
            .text-secondary,
            .text-primary,
            .text-success,
            .text-white,
            *[class*="text-"] {
                color: black !important;
            }

            /* Force all card header text to be black */
            .card-header *,
            .card-header h5,
            .card-header .mb-0,
            .card-header i {
                color: black !important;
            }

            /* Badge colors for print */
            .badge,
            .badge.bg-success,
            .badge.bg-primary,
            .badge.bg-secondary {
                background-color: #6c757d !important;
                color: white !important;
                border: none !important;
            }

            /* Override card header colors for monochrome printing */
            @media print and (monochrome) {
                .card-header,
                .card-header.bg-primary,
                .card-header.bg-success,
                .card-header.bg-info,
                .card-header.bg-warning,
                .card-header.bg-dark {
                    background-color: transparent !important;
                    color: black !important;
                    border: 2px solid black !important;
                }

                /* Force all text in card headers to be black */
                .card-header *,
                .card-header h5,
                .card-header .mb-0,
                .card-header i,
                .card-header.text-white *,
                .text-white {
                    color: black !important;
                }

                .section-title {
                    border-bottom: none !important;
                }
            }

            /* Page layout */
            .print-title-section {
                margin-bottom: 50px !important;
                margin-top: 30px !important;
                text-align: center;
            }

            .print-title-section h2 {
                font-size: 18pt;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .print-title-section h3 {
                font-size: 14pt;
                font-weight: bold;
                margin-bottom: 5px;
            }

            @page {
                margin: 2cm 1.5cm 2cm 1.5cm;
                size: A4;

                @top-left {
                    content: "{{ $tanggalIndo }}";
                    font-size: 11pt;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                    margin: 0;
                    padding-bottom: 15px;
                }

                @top-right {
                    content: "Yayasan WeBe Konservasi Ketapang";
                    font-size: 11pt;
                    font-weight: bold;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                    margin: 0;
                    padding-bottom: 15px;
                }

                @bottom-left {
                    content: "Rekam WeBe";
                    font-size: 10pt;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                    margin: 0;
                }

                @bottom-right {
                    content: counter(page);
                    font-size: 10pt;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                    margin: 0;
                }
            }
        }

        /* Web styles (untuk tampilan browser) */
        @media screen {
            .print-title-section {
                display: none !important;
            }

            .card {
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        }
    </style>

    <!-- Auto-print script removed from here as it is now handled by iframe in parent window -->
@endsection
