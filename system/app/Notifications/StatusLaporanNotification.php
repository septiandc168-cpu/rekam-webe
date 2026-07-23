<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusLaporanNotification extends Notification
{
    use Queueable;

    public $id_laporan;
    public $judul_kegiatan;
    public $status_baru;
    public $keterangan;
    public $updated_at;

    /**
     * Create a new notification instance.
     */
    public function __construct($laporanUuid, $judul_kegiatan, $status_baru, $keterangan = null, $updated_at = null)
    {
        $this->id_laporan = $laporanUuid;
        $this->judul_kegiatan = $judul_kegiatan;
        $this->status_baru = $status_baru;
        $this->keterangan = $keterangan;
        $this->updated_at = $updated_at ?? now();
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
        return [
            'id_laporan' => $this->id_laporan,
            'judul_kegiatan' => $this->judul_kegiatan,
            'status_baru' => $this->status_baru,
            'keterangan' => $this->keterangan,
            'updated_at' => $this->updated_at,
            'message' => "Laporan kegiatan {$this->judul_kegiatan} telah diubah menjadi {$this->status_baru}",
            'type' => 'laporan_kegiatan'
        ];
    }
}
