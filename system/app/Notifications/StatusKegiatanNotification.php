<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class StatusKegiatanNotification extends Notification
{
    use Queueable;

    public $id_kegiatan;
    public $judul_kegiatan;
    public $status_baru;
    public $keterangan;
    public $updated_at;

    /**
     * Create a new notification instance.
     */
    public function __construct($kegiatanUuid, $judul_kegiatan, $status_baru, $keterangan = null, $updated_at = null)
    {
        $this->id_kegiatan = $kegiatanUuid; // Use UUID instead of ID
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
            'id_kegiatan' => $this->id_kegiatan,
            'judul_kegiatan' => $this->judul_kegiatan,
            'status_baru' => $this->status_baru,
            'keterangan' => $this->keterangan,
            'updated_at' => $this->updated_at,
            'message' => "Kegiatan {$this->judul_kegiatan} telah diubah menjadi {$this->status_baru}"
        ];
    }
}
