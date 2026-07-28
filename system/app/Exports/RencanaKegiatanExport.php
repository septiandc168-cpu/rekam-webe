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
        $query = RencanaKegiatan::with('user');

        if ($this->tahun) {
            $query->whereYear('tanggal_mulai', $this->tahun);
        }
        if ($this->bulan) {
            $query->whereMonth('tanggal_mulai', $this->bulan);
        }
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('tanggal_mulai', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kegiatan',
            'Jenis Kegiatan',
            'Desa/Lokasi',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Penanggung Jawab',
            'Kelompok/Komunitas',
            'Estimasi Peserta',
            'Status',
            'Penyusun',
        ];
    }

    public function map($rencana): array
    {
        $this->rowNumber++;

        // Map status label
        $statusLabels = [
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai',
            'draft' => 'Draft',
            'revisi' => 'Revisi'
        ];
        $statusFormatted = $statusLabels[$rencana->status] ?? ucfirst($rencana->status);

        return [
            $this->rowNumber,
            $rencana->nama_kegiatan,
            $rencana->getJenisKegiatanLabel(),
            $rencana->desa ?: '-',
            $rencana->tanggal_mulai ? $rencana->tanggal_mulai->format('d/m/Y') : '-',
            $rencana->tanggal_selesai ? $rencana->tanggal_selesai->format('d/m/Y') : '-',
            $rencana->penanggung_jawab ?: '-',
            $rencana->kelompok ?: '-',
            $rencana->estimasi_peserta ?: 0,
            $statusFormatted,
            $rencana->user ? $rencana->user->name : 'Tidak diketahui',
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
