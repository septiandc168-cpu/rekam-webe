<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class KegiatanActivityNotification extends Notification
{

    public $id_kegiatan;
    public $judul_kegiatan;
    public $aksi;
    public $user_name;
    public $keterangan;
    public $created_at;

    /**
     * Create a new notification instance.
     */
    public function __construct($kegiatanUuid, $judul_kegiatan, $aksi, $user_name, $keterangan = null, $created_at = null)
    {
        $this->id_kegiatan = $kegiatanUuid;
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
        $message = "Rencana kegiatan '{$this->judul_kegiatan}' {$this->aksi} oleh {$this->user_name}";
        
        return [
            'id_kegiatan' => $this->id_kegiatan,
            'judul_kegiatan' => $this->judul_kegiatan,
            'aksi' => $this->aksi,
            'user_name' => $this->user_name,
            'keterangan' => $this->keterangan,
            'created_at' => $this->created_at,
            'message' => $message,
            'type' => 'rencana_kegiatan'
        ];
    }
}
