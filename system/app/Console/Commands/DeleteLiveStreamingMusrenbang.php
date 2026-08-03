<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RencanaKegiatan;
use App\Models\LaporanKegiatan;
use Illuminate\Support\Facades\DB;

class DeleteLiveStreamingMusrenbang extends Command
{
    protected $signature = 'data:delete-live-streaming';
    protected $description = 'Delete all records with title "Live Streaming Musrenbang"';

    public function handle()
    {
        $this->info('Starting deletion of "Live Streaming Musrenbang" records...');
        $this->newLine();

        // Delete from Rencana Kegiatan
        $rencanaCount = RencanaKegiatan::where('nama_kegiatan', 'LIKE', '%Live Streaming Musrenbang%')->count();
        
        if ($rencanaCount > 0) {
            $this->warn("Found {$rencanaCount} Rencana Kegiatan records");
            
            if ($this->confirm('Delete these Rencana Kegiatan records?', true)) {
                $deleted = RencanaKegiatan::where('nama_kegiatan', 'LIKE', '%Live Streaming Musrenbang%')->delete();
                $this->info("✓ Deleted {$deleted} Rencana Kegiatan records");
            }
        } else {
            $this->info('No Rencana Kegiatan records found');
        }

        $this->newLine();

        // Delete from Laporan Kegiatan (check rencana_kegiatan relationship)
        $laporanCount = LaporanKegiatan::whereHas('rencanaKegiatan', function($query) {
            $query->where('nama_kegiatan', 'LIKE', '%Live Streaming Musrenbang%');
        })->count();
        
        if ($laporanCount > 0) {
            $this->warn("Found {$laporanCount} Laporan Kegiatan records");
            
            if ($this->confirm('Delete these Laporan Kegiatan records?', true)) {
                $deleted = LaporanKegiatan::whereHas('rencanaKegiatan', function($query) {
                    $query->where('nama_kegiatan', 'LIKE', '%Live Streaming Musrenbang%');
                })->delete();
                $this->info("✓ Deleted {$deleted} Laporan Kegiatan records");
            }
        } else {
            $this->info('No Laporan Kegiatan records found');
        }

        $this->newLine();
        $this->info('Deletion completed!');

        return 0;
    }
}
