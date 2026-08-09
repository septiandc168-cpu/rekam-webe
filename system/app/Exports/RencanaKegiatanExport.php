<?php

namespace App\Exports;

use App\Models\LaporanKegiatan;
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
    protected $jenis;
    protected $search;
    private $rowNumber = 0;

    public function __construct($tahun, $bulan, $status, $userId, $jenis = null, $search = null)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->status = $status;
        $this->userId = $userId;
        $this->jenis = $jenis;
        $this->search = $search;
    }

    public function collection()
    {
        $query = LaporanKegiatan::with(['user', 'rencanaKegiatan'])
            ->whereIn('status', [
                LaporanKegiatan::STATUS_FINAL,
                LaporanKegiatan::STATUS_DIAJUKAN,
                LaporanKegiatan::STATUS_REVISI,
            ]);

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
        if ($this->jenis) {
            $jenis = $this->jenis;
            if ($jenis === 'langsung') {
                $query->whereNull('rencana_kegiatan_id');
            } else {
                $query->where(function ($q) use ($jenis) {
                    $q->whereHas('rencanaKegiatan', fn($r) => $r->where('jenis_kegiatan', $jenis));
                    if ($jenis === 'lainnya') {
                        $q->orWhereNull('rencana_kegiatan_id');
                    }
                });
            }
        }
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('lokasi_kegiatan', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('rencanaKegiatan', function ($r) use ($search) {
                      $r->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('desa', 'like', "%{$search}%")
                        ->orWhere('penanggung_jawab', 'like', "%{$search}%");
                  });
            });
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
        $target = (int) ($laporan->target_peserta ?: ($rencana ? ($rencana->estimasi_peserta ?: 0) : 0));
        $realisasi = (int) ($laporan->realisasi_peserta ?: 0);

        $statusText = match($laporan->status) {
            'final'    => 'Laporan Final',
            'diajukan' => 'Laporan Diajukan',
            'revisi'   => 'Laporan Revisi',
            default    => 'Draft',
        };

        return [
            $this->rowNumber,
            $namaKegiatan,
            $jenisKegiatan,
            $lokasi,
            $tglPelaksanaan,
            $pembuat,
            $target,
            $realisasi,
            $statusText,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $range = 'A1:I' . $lastRow;

        // Gaya default seluruh tabel (Font: Times New Roman, ukuran 11)
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'size' => 11,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Perataan tengah kolom No, Jenis, Tanggal, Target, Realisasi, Status
        $sheet->getStyle('A1:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C1:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E1:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G1:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I1:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Gaya khusus baris Header (Baris 1)
        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF001f3f'], // Navy Blue
            ],
        ]);

        // Membekukan Header agar tidak ikut ter-scroll
        $sheet->freezePane('A2');

        // Baris Total Penjumlahan di paling bawah
        $totalRow = $lastRow + 1;
        $sheet->setCellValue('A' . $totalRow, 'TOTAL REALISASI');
        $sheet->mergeCells('A' . $totalRow . ':F' . $totalRow);
        $sheet->setCellValue('G' . $totalRow, '=SUM(G2:G' . $lastRow . ')');
        $sheet->setCellValue('H' . $totalRow, '=SUM(H2:H' . $lastRow . ')');

        $sheet->getRowDimension($totalRow)->setRowHeight(25);
        $sheet->getStyle('A' . $totalRow . ':I' . $totalRow)->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'size' => 11,
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFF2F4F7'], // Soft gray
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G' . $totalRow . ':H' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 38,  // Nama Kegiatan
            'C' => 22,  // Jenis Kegiatan
            'D' => 25,  // Desa/Lokasi
            'E' => 22,  // Tanggal Pelaksanaan
            'F' => 26,  // Penanggung Jawab
            'G' => 16,  // Target Peserta
            'H' => 18,  // Realisasi Peserta
            'I' => 20,  // Status Laporan
        ];
    }
}
