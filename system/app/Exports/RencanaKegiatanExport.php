<?php

namespace App\Exports;

use App\Models\RencanaKegiatan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class RencanaKegiatanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $bulan;
    protected $tahun;
    protected $status;
    protected $userId;
    private $rowNumber = 0; // Untuk auto-increment nomor urut

    public function __construct($bulan, $tahun, $status = null, $userId = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->status = $status;
        $this->userId = $userId;
    }

    /**
     * Menggunakan FromQuery agar query dieksekusi secara chunk oleh library (Hemat Memori)
     */
    public function query()
    {
        $query = RencanaKegiatan::query()
            ->with('user') // Eager Loading relasi user untuk mencegah N+1
            ->whereYear('tanggal_mulai', $this->tahun)
            ->whereMonth('tanggal_mulai', $this->bulan);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('tanggal_mulai', 'asc');
    }

    /**
     * Definisi Header (Baris pertama Excel)
     */
    public function headings(): array
    {
        return [
            'No', 
            'Judul Kegiatan', 
            'Tanggal Pelaksanaan', 
            'Penanggung Jawab', 
            'Lokasi', 
            'Status'
        ];
    }

    /**
     * Mapping data dari Database ke Kolom Excel
     */
    public function map($row): array
    {
        $this->rowNumber++;

        // Memformat rentang tanggal (d-m-Y)
        $tanggalPelaksanaan = '-';
        if ($row->tanggal_mulai && $row->tanggal_selesai) {
            $tanggalPelaksanaan = Carbon::parse($row->tanggal_mulai)->format('d-m-Y') . ' s/d ' . Carbon::parse($row->tanggal_selesai)->format('d-m-Y');
        } elseif ($row->tanggal_mulai) {
            $tanggalPelaksanaan = Carbon::parse($row->tanggal_mulai)->format('d-m-Y');
        }

        return [
            $this->rowNumber,
            $row->nama_kegiatan, 
            $tanggalPelaksanaan,
            $row->user ? $row->user->name : 'Tidak Diketahui',
            $row->desa ?? '-',
            strtoupper($row->status)
        ];
    }

    /**
     * Styling baris atau kolom Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Baris pertama (1) = Header Tabel
            1    => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
            ],
        ];
    }
}
