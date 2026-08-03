<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RencanaKegiatan;
use App\Models\LaporanKegiatan;
use Illuminate\Support\Facades\DB;

class DeleteSpecificRecords extends Command
{
    protected $signature = 'data:delete-specific';
    protected $description = 'Delete specific Rencana Kegiatan records and their notifications';

    public function handle()
    {
        $this->info('Starting deletion of specific records...');
        $this->newLine();

        // Delete Rencana Kegiatan records
        $rencanaToDelete = [
            'Rapat Kelompok Kerja Reviu Rencana Pengelolaan dan Zonasi Kawasan Konservasi Perairan Daerah Kendawangan dan Perairan Sekitarnya',
            'Kemah Konservasi Bahari Kendawangan 2026'
        ];

        foreach ($rencanaToDelete as $title) {
            $this->info("Processing Rencana: {$title}");
            
            // Find Rencana Kegiatan
            $rencana = RencanaKegiatan::where('nama_kegiatan', 'LIKE', "%{$title}%")->first();
            
            if ($rencana) {
                $rencanaUuid = $rencana->uuid;
                $this->warn("Found Rencana Kegiatan: {$rencana->nama_kegiatan}");
                
                // Delete related Laporan Kegiatan
                $laporanCount = LaporanKegiatan::where('rencana_kegiatan_id', $rencanaUuid)->count();
                if ($laporanCount > 0) {
                    $this->info("  - Found {$laporanCount} related Laporan Kegiatan");
                    if ($this->confirm("    Delete these Laporan?", true)) {
                        LaporanKegiatan::where('rencana_kegiatan_id', $rencanaUuid)->delete();
                        $this->info("    ✓ Deleted {$laporanCount} Laporan");
                    }
                }
                
                // Delete related notifications
                $notifCount = DB::table('notifications')
                    ->where('data', 'LIKE', "%{$rencana->nama_kegiatan}%")
                    ->count();
                    
                if ($notifCount > 0) {
                    $this->info("  - Found {$notifCount} related notifications");
                    if ($this->confirm("    Delete these notifications?", true)) {
                        DB::table('notifications')
                            ->where('data', 'LIKE', "%{$rencana->nama_kegiatan}%")
                            ->delete();
                        $this->info("    ✓ Deleted {$notifCount} notifications");
                    }
                }
                
                // Delete Rencana Kegiatan
                if ($this->confirm("  Delete this Rencana Kegiatan?", true)) {
                    $rencana->delete();
                    $this->info("  ✓ Deleted Rencana Kegiatan");
                }
                
                $this->newLine();
            } else {
                $this->info("Not found: {$title}");
                $this->newLine();
            }
        }

        // Delete standalone Laporan Kegiatan
        $this->info("Processing standalone Laporan Kegiatan...");
        $laporanToDelete = [
            'Penanganan dan Pemeriksaan Dugong Mati di Perairan Kendawangan'
        ];

        foreach ($laporanToDelete as $title) {
            $this->info("Processing Laporan: {$title}");
            
            $laporan = LaporanKegiatan::where('nama_laporan', 'LIKE', "%{$title}%")->first();
            
            if ($laporan) {
                $this->warn("Found Laporan: {$laporan->nama_laporan}");
                
                // Delete related notifications
                $notifCount = DB::table('notifications')
                    ->where('data', 'LIKE', "%{$laporan->nama_laporan}%")
                    ->count();
                    
                if ($notifCount > 0) {
                    $this->info("  - Found {$notifCount} related notifications");
                    if ($this->confirm("    Delete these notifications?", true)) {
                        DB::table('notifications')
                            ->where('data', 'LIKE', "%{$laporan->nama_laporan}%")
                            ->delete();
                        $this->info("    ✓ Deleted {$notifCount} notifications");
                    }
                }
                
                // Delete Laporan
                if ($this->confirm("  Delete this Laporan Kegiatan?", true)) {
                    $laporan->delete();
                    $this->info("  ✓ Deleted Laporan Kegiatan");
                }
                
                $this->newLine();
            } else {
                $this->info("Not found: {$title}");
                $this->newLine();
            }
        }

        $this->info('Deletion completed!');
        return 0;
    }
}
