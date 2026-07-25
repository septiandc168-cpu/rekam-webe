<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rencana Kegiatan</title>
    <style>
        @page { margin: 28px 32px; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.45; }
        h1 { font-size: 18px; margin: 0 0 4px; text-align: center; }
        h2 { border-bottom: 1px solid #1f2937; font-size: 13px; margin: 20px 0 8px; padding-bottom: 4px; }
        .subtitle { color: #4b5563; margin: 0; text-align: center; }
        table { border-collapse: collapse; width: 100%; }
        td { padding: 5px 0; vertical-align: top; }
        td.label { color: #4b5563; width: 34%; }
        .content { white-space: pre-line; }
        .attachments { margin: 0; padding-left: 18px; }
        .footer { border-top: 1px solid #d1d5db; color: #6b7280; font-size: 9px; margin-top: 28px; padding-top: 8px; text-align: right; }
    </style>
</head>
<body>
    <h1>RENCANA KEGIATAN</h1>
    <p class="subtitle">Yayasan WeBe Konservasi Ketapang</p>

    <h2>Informasi Kegiatan</h2>
    <table>
        <tr><td class="label">Nama Kegiatan</td><td>: {{ $rencanaKegiatan->nama_kegiatan ?: '-' }}</td></tr>
        <tr><td class="label">Jenis Kegiatan</td><td>: {{ $rencanaKegiatan->getJenisKegiatanLabel() }}</td></tr>
        <tr><td class="label">Status</td><td>: {{ ucfirst($rencanaKegiatan->status) }}</td></tr>
        <tr><td class="label">Tanggal Pelaksanaan</td><td>: {{ $rencanaKegiatan->tanggal_mulai?->translatedFormat('d F Y') ?? '-' }}@if($rencanaKegiatan->tanggal_selesai && $rencanaKegiatan->tanggal_selesai != $rencanaKegiatan->tanggal_mulai) s/d {{ $rencanaKegiatan->tanggal_selesai->translatedFormat('d F Y') }}@endif</td></tr>
        <tr><td class="label">Waktu</td><td>: {{ $rencanaKegiatan->waktu_mulai?->format('H:i') ?? '-' }}@if($rencanaKegiatan->waktu_selesai) - {{ $rencanaKegiatan->waktu_selesai->format('H:i') }}@endif WIB</td></tr>
        <tr><td class="label">Lokasi</td><td>: {{ $rencanaKegiatan->desa ?: '-' }}</td></tr>
        <tr><td class="label">Koordinat</td><td>: {{ $rencanaKegiatan->lat ?: '-' }}, {{ $rencanaKegiatan->lng ?: '-' }}</td></tr>
        <tr><td class="label">Penanggung Jawab</td><td>: {{ $rencanaKegiatan->penanggung_jawab ?: '-' }}</td></tr>
        <tr><td class="label">Kelompok / Komunitas</td><td>: {{ $rencanaKegiatan->kelompok ?: '-' }}</td></tr>
        <tr><td class="label">Estimasi Peserta</td><td>: {{ $rencanaKegiatan->estimasi_peserta ?: 0 }} orang</td></tr>
        <tr><td class="label">Penyusun</td><td>: {{ $rencanaKegiatan->user?->name ?: '-' }}</td></tr>
    </table>

    <h2>Deskripsi Kegiatan</h2>
    <div class="content">{{ trim(strip_tags($rencanaKegiatan->deskripsi ?? '')) ?: '-' }}</div>

    <h2>Tujuan Kegiatan</h2>
    <div class="content">{{ trim(strip_tags($rencanaKegiatan->tujuan ?? '')) ?: '-' }}</div>

    <h2>Rincian Kebutuhan</h2>
    <div class="content">{{ trim(strip_tags($rencanaKegiatan->rincian_kebutuhan ?? '')) ?: '-' }}</div>

    @php
        $dokumen = is_array($rencanaKegiatan->dokumen) ? $rencanaKegiatan->dokumen : [];
    @endphp
    @if (!empty($rencanaKegiatan->anggaran_kegiatan) || count($dokumen))
        <h2>Lampiran</h2>
        <ul class="attachments">
            @if (!empty($rencanaKegiatan->anggaran_kegiatan))
                @php $anggaran = is_array($rencanaKegiatan->anggaran_kegiatan) ? $rencanaKegiatan->anggaran_kegiatan : ['path' => $rencanaKegiatan->anggaran_kegiatan]; @endphp
                <li>Anggaran: {{ $anggaran['original_name'] ?? basename($anggaran['path'] ?? '') }}</li>
            @endif
            @foreach ($dokumen as $file)
                @php $lampiran = is_array($file) ? $file : ['path' => $file]; @endphp
                <li>{{ $lampiran['original_name'] ?? basename($lampiran['path'] ?? '') }}</li>
            @endforeach
        </ul>
    @endif

    <div class="footer">Dicetak pada {{ now()->translatedFormat('d F Y H:i') }} WIB</div>
</body>
</html>
