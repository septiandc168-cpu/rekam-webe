<?php

namespace App\Exports;

use App\Models\RencanaKegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RencanaKegiatanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
