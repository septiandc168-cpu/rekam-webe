<?php

namespace App\Exports;

use App\Models\RencanaKegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RencanaKegiatanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnWidths
{
    protected $tahun;
    protected $bulan;
    protected $status;
    protected $userId;
    private $rowNumber = 0;

    public function __construct($tahun, $bulan, $status, $userId)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->status = $status;
        $this->userId = $userId;
    }

    public function collection()
    {
        $query = \App\Models\LaporanKegiatan::with(['user', 'rencanaKegiatan'])
            ->where('status', \App\Models\LaporanKegiatan::STATUS_FINAL);

        if ($this->tahun) {
            $tahun = $this->tahun;
            $query->where(function ($q) use ($tahun) {
                $q->whereYear('realisasi_tanggal_mulai', $tahun)
                  ->orWhereHas('rencanaKegiatan', fn($r) => $r->whereYear('tanggal_mulai', $tahun));
            });
        }
        if ($this->bulan) {
            $bulan = $this->bulan;
            $query->where(function ($q) use ($bulan) {
                $q->whereMonth('realisasi_tanggal_mulai', $bulan)
                  ->orWhereHas('rencanaKegiatan', fn($r) => $r->whereMonth('tanggal_mulai', $bulan));
            });
        }
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kegiatan',
            'Jenis Kegiatan',
            'Desa/Lokasi',
            'Tanggal Pelaksanaan',
            'Penanggung Jawab / Anggota',
            'Target Peserta',
            'Realisasi Peserta',
            'Status Laporan',
        ];
    }

    public function map($laporan): array
    {
        $this->rowNumber++;

        $rencana = $laporan->rencanaKegiatan;
        $namaKegiatan = $rencana ? $rencana->nama_kegiatan : ($laporan->judul_kegiatan ?: 'Laporan Langsung');
        $jenisKegiatan = $rencana ? $rencana->getJenisKegiatanLabel() : 'Laporan Langsung';
        $lokasi = $laporan->lokasi_kegiatan ?: ($rencana ? ($rencana->desa ?: '-') : '-');

        $tglMulai = $laporan->realisasi_tanggal_mulai ?: ($rencana ? $rencana->tanggal_mulai : $laporan->created_at);
        $tglSelesai = $laporan->realisasi_tanggal_selesai ?: ($rencana ? $rencana->tanggal_selesai : null);

        $tglPelaksanaan = $tglMulai ? \Carbon\Carbon::parse($tglMulai)->format('d/m/Y') : '-';
        if ($tglSelesai && \Carbon\Carbon::parse($tglSelesai)->format('d/m/Y') != $tglPelaksanaan) {
            $tglPelaksanaan .= ' s/d ' . \Carbon\Carbon::parse($tglSelesai)->format('d/m/Y');
        }

        $pembuat = $laporan->user ? $laporan->user->name : ($rencana && $rencana->user ? $rencana->user->name : '-');
        $target = $laporan->target_peserta ?: ($rencana ? ($rencana->estimasi_peserta ?: 0) : 0);
        $realisasi = $laporan->realisasi_peserta ?: 0;

        return [
            $this->rowNumber,
            $namaKegiatan,
            $jenisKegiatan,
            $lokasi,
            $tglPelaksanaan,
            $pembuat,
            $target,
            $realisasi,
            'Selesai (Laporan Final)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $range = 'A1:' . $lastColumn . $lastRow;

        // Gaya default seluruh tabel (Font: Times New Roman, ukuran 12)
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'size' => 12,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true, // Mengaktifkan text-wrap agar tidak terpotong
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Hitam solid
                ],
            ],
        ]);

        // Merapikan posisi tengah (center) pada kolom tertentu (No, Tanggal, Status)
        $sheet->getStyle('A1:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E1:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I1:J' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Gaya khusus baris Header (Baris 1)
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Teks putih
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF001f3f'], // Warna dasar bg-navy (Biru dongker) khas AdminLTE
            ],
        ]);
        
        // Membekukan Header agar tidak ikut ter-scroll
        $sheet->freezePane('A2');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 35,  // Nama Kegiatan
            'C' => 20,  // Jenis Kegiatan
            'D' => 25,  // Desa/Lokasi
            'E' => 15,  // Tgl Mulai
            'F' => 15,  // Tgl Selesai
            'G' => 20,  // Penanggung Jawab
            'H' => 25,  // Kelompok/Komunitas
            'I' => 15,  // Estimasi Peserta
            'J' => 15,  // Status
            'K' => 25,  // Penyusun
        ];
    }
}
