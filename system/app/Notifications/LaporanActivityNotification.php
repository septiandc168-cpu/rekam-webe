<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class LaporanActivityNotification extends Notification
{

    public $id_laporan;
    public $id_kegiatan;
    public $judul_laporan;
    public $judul_kegiatan;
    public $aksi;
    public $user_name;
    public $keterangan;
    public $created_at;

    /**
     * Create a new notification instance.
     */
    public function __construct($laporanUuid, $kegiatanUuid, $judul_laporan, $judul_kegiatan, $aksi, $user_name, $keterangan = null, $created_at = null)
    {
        $this->id_laporan = $laporanUuid;
        $this->id_kegiatan = $kegiatanUuid;
        $this->judul_laporan = $judul_laporan;
        $this->judul_kegiatan = $judul_kegiatan;
        $this->aksi = $aksi; // 'ditambahkan', 'diedit', 'dihapus'
        $this->user_name = $user_name;
        $this->keterangan = $keterangan;
        $this->created_at = $created_at ?? now();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $judulDisplay = !empty(trim($this->judul_laporan ?? '')) 
            ? $this->judul_laporan 
            : (!empty(trim($this->judul_kegiatan ?? '')) ? $this->judul_kegiatan : 'Laporan Kegiatan');

        $message = "Laporan kegiatan '{$judulDisplay}' {$this->aksi} oleh {$this->user_name}";
        
        return [
            'id_laporan' => $this->id_laporan,
            'id_kegiatan' => $this->id_kegiatan,
            'judul_laporan' => $judulDisplay,
            'judul_kegiatan' => $this->judul_kegiatan ?? $judulDisplay,
            'aksi' => $this->aksi,
            'user_name' => $this->user_name,
            'keterangan' => $this->keterangan,
            'message' => $message,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
        ];
    }
}
