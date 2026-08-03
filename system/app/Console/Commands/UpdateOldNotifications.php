<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\LaporanKegiatan;

class UpdateOldNotifications extends Command
{
    protected $signature = 'notifications:update-old';
    protected $description = 'Update old laporan kegiatan notifications with missing titles';

    public function handle()
    {
        $this->info('Starting notification update...');
        $this->newLine();

        // Get all notifications with type 'laporan_kegiatan'
        $notifications = DB::table('notifications')
            ->whereRaw("JSON_EXTRACT(data, '$.type') = 'laporan_kegiatan'")
            ->get();

        $updated = 0;
        $skipped = 0;

        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            
            // Check if judul_laporan is null or empty
            if (empty($data['judul_laporan']) || $data['judul_laporan'] === null) {
                // Try to get the laporan from database
                $laporan = LaporanKegiatan::where('uuid', $data['id_laporan'])->first();
                
                if ($laporan) {
                    // Get judul from rencana or judul_kegiatan
                    $judul = null;
                    if ($laporan->rencanaKegiatan) {
                        $judul = $laporan->rencanaKegiatan->nama_kegiatan;
                    } elseif ($laporan->judul_kegiatan) {
                        $judul = $laporan->judul_kegiatan;
                    }
                    
                    if ($judul) {
                        // Update data
                        $data['judul_laporan'] = $judul;
                        $data['judul_kegiatan'] = $judul;
                        
                        // Rebuild message
                        $aksi = $data['aksi'] ?? 'diajukan';
                        $userName = $data['user_name'] ?? 'Unknown';
                        $data['message'] = "Laporan kegiatan '{$judul}' {$aksi} oleh {$userName}";
                        
                        // Update notification
                        DB::table('notifications')
                            ->where('id', $notification->id)
                            ->update(['data' => json_encode($data)]);
                        
                        $this->info("✓ Updated notification {$notification->id} with title: {$judul}");
                        $updated++;
                    } else {
                        $this->warn("✗ Skipped notification {$notification->id} - No title found");
                        $skipped++;
                    }
                } else {
                    $this->warn("✗ Skipped notification {$notification->id} - Laporan not found");
                    $skipped++;
                }
            } else {
                $this->line("- Skipped notification {$notification->id} - Already has title");
                $skipped++;
            }
        }

        $this->newLine();
        $this->info('========================================');
        $this->info('Update completed!');
        $this->info("Updated: {$updated} notifications");
        $this->info("Skipped: {$skipped} notifications");
        $this->info('========================================');

        return 0;
    }
}
