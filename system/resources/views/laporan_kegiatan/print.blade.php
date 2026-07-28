@extends('layouts.print')

@section('content')
    <div class="container-fluid">
        <!-- Watermark dihapus sesuai request -->
        
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
        <table class="kop-surat mb-4" style="width: 100%; border-bottom: 4px double black; margin-bottom: 20px;">
            <tr>
                <td style="width: 120px; text-align: left; vertical-align: middle;">
                    <img src="/public/adminlte/dist/img/logo_webe.png" alt="Logo" style="width: 100px;">
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <h2 class="fw-bold mb-1" style="font-family: 'Times New Roman', Times, serif; font-size: 24px; color: black; margin-bottom: 2px;">YAYASAN WEBE KONSERVASI KETAPANG</h2>
                    <h4 class="mb-1" style="font-family: 'Times New Roman', Times, serif; font-size: 16px; color: black; margin-bottom: 2px;">REKAM WEBE - SISTEM PELAPORAN KEGIATAN</h4>
                    <p class="mb-0" style="font-family: 'Times New Roman', Times, serif; font-size: 12px; color: black;">
                        JL RM Sudiono, No.49A, Ketapang, Kalimantan Barat<br>
                        Email: yayasanwebe@gmail.com | Telp/WA: +62 813 6022 733
                    </p>
                </td>
                <td style="width: 120px;"></td> <!-- Ruang kosong penyeimbang -->
            </tr>
        </table>

        <!-- Judul untuk Print -->
        <div class="print-title-section mb-4 text-center">
            <h3 style="font-family: 'Times New Roman', Times, serif; text-decoration: underline; font-weight: bold; font-size: 18px; margin-bottom: 5px;">LAPORAN KEGIATAN</h3>
            <h4 style="font-family: 'Times New Roman', Times, serif; font-size: 14px; font-weight: normal;">{{ $laporanKegiatan->isDarurat() ? $laporanKegiatan->judul_kegiatan : $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</h4>
            @if($laporanKegiatan->isDarurat())
                <span class="badge bg-info mt-1"><i class="fas fa-exclamation-triangle mr-1"></i> Laporan Tanggap Darurat</span>
            @endif
        </div>

        @if(!$laporanKegiatan->isDarurat())
        <!-- Informasi Rencana Kegiatan -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-navy text-white">
                        <h5 class="mb-0" style="font-family: \'Times New Roman\', Times, serif; font-weight: bold; font-size: 12pt;">
                            <i class="fas fa-info-circle mr-1"></i>
                            Informasi Rencana Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="200" class="label-field"><strong>Nama Kegiatan</strong></td><td width="20">:</td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Jenis Kegiatan</strong></td><td width="20">:</td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->getJenisKegiatanLabel() }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Tujuan</strong></td><td width="20">:</td>
                                        <td class="value-field">{!! strip_tags($laporanKegiatan->rencanaKegiatan->tujuan) ?: '-' !!}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Penanggung Jawab</strong></td><td width="20">:</td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->penanggung_jawab ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Kelompok</strong></td><td width="20">:</td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->kelompok ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Tanggal Laporan</strong></td><td width="20">:</td>
                                        <td class="value-field">{{ $laporanKegiatan->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Detail Pelaksanaan Kegiatan -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-navy text-white">
                        <h5 class="mb-0" style="font-family: \'Times New Roman\', Times, serif; font-weight: bold; font-size: 12pt;">
                            <i class="fas fa-tasks mr-1"></i>
                            Detail Pelaksanaan Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="200" class="label-field"><strong>Tanggal Pelaksanaan</strong></td><td width="20">:</td>
                                <td class="value-field">
                                    {{ $laporanKegiatan->isDarurat() ? \Carbon\Carbon::parse($laporanKegiatan->realisasi_tanggal_mulai)->translatedFormat('d F Y') : ($laporanKegiatan->rencanaKegiatan->tanggal_mulai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->translatedFormat('d F Y') : '-') }}
                                    @if ($laporanKegiatan->isDarurat() ? ($laporanKegiatan->realisasi_tanggal_selesai && $laporanKegiatan->realisasi_tanggal_selesai != $laporanKegiatan->realisasi_tanggal_mulai) : ($laporanKegiatan->rencanaKegiatan->tanggal_selesai && $laporanKegiatan->rencanaKegiatan->tanggal_selesai != $laporanKegiatan->rencanaKegiatan->tanggal_mulai))
                                        s/d {{ \Carbon\Carbon::parse($laporanKegiatan->isDarurat() ? $laporanKegiatan->realisasi_tanggal_selesai : $laporanKegiatan->rencanaKegiatan->tanggal_selesai)->translatedFormat('d F Y') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="label-field"><strong>Realisasi Tanggal Pelaksanaan</strong></td><td width="20">:</td>
                                <td class="value-field">
                                    {{ $laporanKegiatan->realisasi_tanggal_mulai ? \Carbon\Carbon::parse($laporanKegiatan->realisasi_tanggal_mulai)->translatedFormat('d F Y') : '-' }}
                                    @if ($laporanKegiatan->realisasi_tanggal_selesai && $laporanKegiatan->realisasi_tanggal_selesai != $laporanKegiatan->realisasi_tanggal_mulai)
                                        s/d {{ \Carbon\Carbon::parse($laporanKegiatan->realisasi_tanggal_selesai)->translatedFormat('d F Y') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="label-field"><strong>Lokasi</strong></td><td width="20">:</td>
                                <td class="value-field">{{ $laporanKegiatan->isDarurat() ? $laporanKegiatan->lokasi_kegiatan : ($laporanKegiatan->rencanaKegiatan->desa ?: '-') }}</td>
                            </tr>
                            <tr>
                                <td class="label-field"><strong>Waktu Pelaksanaan</strong></td><td width="20">:</td>
                                <td class="value-field">
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
                                <td class="label-field"><strong>Rangkaian Kegiatan</strong></td><td width="20">:</td>
                                <td class="value-field">{!! $laporanKegiatan->rangkaian_kegiatan !!}</td>
                            </tr>
                            <tr>
                                <td class="label-field"><strong>Target Peserta</strong></td><td width="20">:</td>
                                <td class="value-field">{{ $laporanKegiatan->isDarurat() ? '-' : ($laporanKegiatan->rencanaKegiatan->estimasi_peserta ?? '-') }} orang</td>
                            </tr>
                            <tr>
                                <td class="label-field"><strong>Realisasi Peserta</strong></td><td width="20">:</td>
                                <td class="value-field">{{ $laporanKegiatan->realisasi_peserta }} orang</td>
                            </tr>
                            <tr>
                                <td class="label-field"><strong>Profil Peserta</strong></td><td width="20">:</td>
                                <td class="value-field">{!! $laporanKegiatan->profil_peserta !!}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil dan Output Kegiatan -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-navy text-white">
                        <h5 class="mb-0" style="font-family: \'Times New Roman\', Times, serif; font-weight: bold; font-size: 12pt;">
                            <i class="fas fa-chart-line mr-1"></i>
                            Hasil dan Output Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="section-title"><i class="fas fa-check-circle mr-2"></i>Hasil yang Dicapai</h6>
                            <div class="content-box">
                                {!! $laporanKegiatan->hasil_dicapai !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="section-title"><i class="fas fa-box mr-2"></i>Output Nyata</h6>
                            <div class="content-box">
                                {!! $laporanKegiatan->output_nyata !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="section-title"><i class="fas fa-leaf mr-2"></i>Dampak Awal yang Terlihat</h6>
                            <div class="content-box">
                                {!! $laporanKegiatan->dampak_awal !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kendala dan Evaluasi -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-navy text-white">
                        <h5 class="mb-0" style="font-family: \'Times New Roman\', Times, serif; font-weight: bold; font-size: 12pt;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Kendala dan Evaluasi
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($laporanKegiatan->kendala)
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-exclamation-circle mr-2"></i>Kendala yang Dihadapi</h6>
                                <div class="content-box">
                                    {!! $laporanKegiatan->kendala !!}
                                </div>
                            </div>
                        @endif

                        @if($laporanKegiatan->solusi)
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-lightbulb mr-2"></i>Solusi yang Dilakukan</h6>
                                <div class="content-box">
                                    {!! $laporanKegiatan->solusi !!}
                                </div>
                            </div>
                        @endif

                        @if($laporanKegiatan->evaluasi_rekomendasi)
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-clipboard-check mr-2"></i>Evaluasi dan Rekomendasi</h6>
                                <div class="content-box">
                                    {!! $laporanKegiatan->evaluasi_rekomendasi !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumentasi Kegiatan -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-navy text-white">
                        <h5 class="mb-0" style="font-family: \'Times New Roman\', Times, serif; font-weight: bold; font-size: 12pt;">
                            <i class="fas fa-file-upload mr-1"></i>
                            Dokumentasi Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Foto Kegiatan -->
                        @if (!empty($laporanKegiatan->foto_kegiatan))
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-images mr-2"></i>Foto Kegiatan</h6>
                                <div class="documentation-grid">
                                    @foreach ($laporanKegiatan->foto_kegiatan as $index => $foto_kegiatan)
                                        @php
                                            // Handle both old format (string) and new format (array)
                                            $filePath = is_array($foto_kegiatan) ? $foto_kegiatan['path'] : $foto_kegiatan;
                                            $fileName = is_array($foto_kegiatan) ? $foto_kegiatan['original_name'] : basename($foto_kegiatan);
                                        @endphp
                                        <div class="doc-item">
                                            <div class="doc-image-container">
                                                <img src="/public/storage/app/{{ $filePath }}"
                                                    class="doc-image"
                                                    alt="{{ $fileName }}">
                                            </div>
                                            <div class="doc-caption">
                                                {{ $fileName }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Daftar Hadir -->
                        @if (!empty($laporanKegiatan->daftar_hadir))
                            <div class="mb-4 page-break-before">
                                <h6 class="section-title"><i class="fas fa-users mr-2"></i>Daftar Hadir</h6>
                                <div class="file-list">
                                    @foreach ($laporanKegiatan->daftar_hadir as $index => $daftar_hadir)
                                        @php
                                            // Handle both old format (string) and new format (array)
                                            $filePath = is_array($daftar_hadir) ? $daftar_hadir['path'] : $daftar_hadir;
                                            $fileName = is_array($daftar_hadir) ? $daftar_hadir['original_name'] : basename($daftar_hadir);
                                        @endphp
                                        <div class="file-item">
                                            <i class="fas fa-file-alt mr-2"></i>
                                            {{ $fileName }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Notulen -->
                        @if (!empty($laporanKegiatan->notulen))
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-file-alt mr-2"></i>Notulen</h6>
                                <div class="file-list">
                                    @foreach ($laporanKegiatan->notulen as $index => $notulen)
                                        @php
                                            // Handle both old format (string) and new format (array)
                                            $filePath = is_array($notulen) ? $notulen['path'] : $notulen;
                                            $fileName = is_array($notulen) ? $notulen['original_name'] : basename($notulen);
                                        @endphp
                                        <div class="file-item">
                                            <i class="fas fa-file-alt mr-2"></i>
                                            {{ $fileName }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Materi -->
                        @if (!empty($laporanKegiatan->materi))
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-book mr-2"></i>Materi</h6>
                                <div class="file-list">
                                    @foreach ($laporanKegiatan->materi as $index => $materi)
                                        @php
                                            // Handle both old format (string) and new format (array)
                                            $filePath = is_array($materi) ? $materi['path'] : $materi;
                                            $fileName = is_array($materi) ? $materi['original_name'] : basename($materi);
                                        @endphp
                                        <div class="file-item">
                                            <i class="fas fa-file-alt mr-2"></i>
                                            {{ $fileName }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Berita Acara -->
                        @if (!empty($laporanKegiatan->berita_acara))
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-file-contract mr-2"></i>Berita Acara</h6>
                                <div class="file-list">
                                    @foreach ($laporanKegiatan->berita_acara as $index => $berita_acara)
                                        @php
                                            // Handle both old format (string) and new format (array)
                                            $filePath = is_array($berita_acara) ? $berita_acara['path'] : $berita_acara;
                                            $fileName = is_array($berita_acara) ? $berita_acara['original_name'] : basename($berita_acara);
                                        @endphp
                                        <div class="file-item">
                                            <i class="fas fa-file-alt mr-2"></i>
                                            {{ $fileName }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Kolom Tanda Tangan -->
        <div class="signature-block no-break mt-5">
            <div class="float-right text-center" style="width: 300px; padding-right: 20px;">
                <p class="mb-5" style="color: black;">
                    Ketapang, {{ $tanggalIndo }}<br>
                    Penanggung Jawab Kegiatan
                </p>
                <br><br><br>
                <p class="mb-0 fw-bold" style="color: black;">{{ $laporanKegiatan->rencanaKegiatan->penanggung_jawab ?: '........................................' }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <!-- Footer Web (tidak muncul saat print) -->
        <!-- <div class="web-footer no-print mt-5">
            <div class="col-12 text-center">
                <hr>
                <p class="text-muted">
                    <small>
                        Dicetak oleh: {{ auth()->user()->name }}<br>
                        Rekam WeBe
                    </small>
                </p>
            </div>
        </div> -->
    </div>

    <style>

        
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
            padding: 8px 12px !important;
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            width: 180px !important;
        }
        
        .value-field {
            padding: 8px 12px !important;
            vertical-align: top !important;
            border: 1px solid #dee2e6 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            hyphens: auto !important;
            line-height: 1.5 !important;
        }
        .table-borderless td {
            border: none !important;
            padding: 4px 8px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
        }

        .table-borderless td:first-child {
            font-weight: bold;
            width: 150px;
        }

        .content-box {
            padding: 0 !important;
            background-color: transparent !important;
            border: none !important;
            margin-bottom: 15px !important;
            text-align: justify !important;
            line-height: 1.6 !important;
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
                padding: 8px 12px !important;
            }

            .table-borderless tr {
                page-break-inside: avoid;
            }

            .label-field {
                font-weight: bold !important;
                vertical-align: top !important;
                padding: 8px 12px !important;
                background-color: transparent !important;
                border: none !important;
                width: 180px !important;
            }
            
            .value-field {
                padding: 8px 12px !important;
                vertical-align: top !important;
                border: none !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto !important;
                line-height: 1.5 !important;
            }
            .table-borderless td {
                border: none !important;
                padding: 4px 8px;
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
            }

            .table-borderless td:first-child {
                font-weight: bold;
                width: 150px;
            }

            .content-box {
            padding: 0 !important;
            background-color: transparent !important;
            border: none !important;
            margin-bottom: 15px !important;
            text-align: justify !important;
            line-height: 1.6 !important;
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
