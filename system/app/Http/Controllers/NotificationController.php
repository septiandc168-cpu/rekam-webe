<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
     */
    public function index()
    {
        $user = auth()->user();

        // Get all notifications for the user, ordered by latest first
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Konfigurasi SweetAlert untuk delete dengan warna danger
        $confirm = [
            'title' => 'Hapus Semua Notifikasi?',
            'text' => 'Apakah Anda yakin ingin menghapus semua notifikasi? Data yang dihapus tidak dapat dikembalikan.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#dc3545',
            'cancelButtonColor' => '#6c757d',
            'confirmButtonText' => 'Ya, Hapus',
            'cancelButtonText' => 'Batal'
        ];

        session()->flash('alert.delete', json_encode($confirm, JSON_UNESCAPED_SLASHES));

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $user = auth()->user();

        // Find the notification that belongs to the user
        $notification = $user->notifications()
            ->where('id', $id)
            ->firstOrFail();

        // Mark as read
        $notification->markAsRead();
        
        // Get notification data
        $data = $notification->data;
        $kegiatanUuid = $data['id_kegiatan'] ?? null;
        $laporanUuid = $data['id_laporan'] ?? null;
        $notificationType = $data['type'] ?? null;
        
        // Debug logging
        \Log::info('Notification read attempt', [
            'notification_id' => $notification->id,
            'kegiatan_uuid' => $kegiatanUuid,
            'laporan_uuid' => $laporanUuid,
            'notification_type' => $notificationType,
            'data' => $data
        ]);

        // Redirect based on notification type
        if ($notificationType === 'laporan_kegiatan' && $laporanUuid) {
            // Check if laporan exists
            $laporan = \App\Models\LaporanKegiatan::find($laporanUuid);
            if (!$laporan) {
                \Log::error('Laporan not found with UUID', ['uuid' => $laporanUuid]);
                return redirect()->back()->with('error', 'Laporan tidak ditemukan.');
            }
            
            \Log::info('Redirecting to laporan', ['uuid' => $laporanUuid, 'route' => route('laporan_kegiatan.show', ['laporan_kegiatan' => $laporanUuid])]);
            
            // Redirect to laporan detail page using UUID
            return redirect()->route('laporan_kegiatan.show', ['laporan_kegiatan' => $laporanUuid]);
        } elseif ($kegiatanUuid) {
            // Check if kegiatan exists
            $kegiatan = \App\Models\RencanaKegiatan::find($kegiatanUuid);
            if (!$kegiatan) {
                \Log::error('Kegiatan not found with UUID', ['uuid' => $kegiatanUuid]);
                return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
            }
            
            \Log::info('Redirecting to kegiatan', ['uuid' => $kegiatanUuid, 'route' => route('rencana_kegiatan.show', ['rencana_kegiatan' => $kegiatanUuid])]);
            
            // Redirect to kegiatan detail page using UUID
            return redirect()->route('rencana_kegiatan.show', ['rencana_kegiatan' => $kegiatanUuid]);
        }

        // If no kegiatan ID, redirect back
        return redirect()->back();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = auth()->user();

        // Mark all unread notifications as read
        $user->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Delete all notifications for the authenticated user.
     */
    public function deleteAll()
    {
        $user = auth()->user();

        // Delete all notifications for the user
        $deletedCount = $user->notifications()->delete();

        return redirect()->back()->with('success', "Semua notifikasi ($deletedCount) telah dihapus.");
    }

    /**
     * Get unread notifications count for API response.
     */
    public function unreadCount()
    {
        $user = auth()->user();
        $count = $user->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }
}
