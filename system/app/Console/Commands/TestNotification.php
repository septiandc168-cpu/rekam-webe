<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\RencanaKegiatan;
use App\Notifications\StatusKegiatanNotification;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test notification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing notification system...');
        
        // Get admin user
        $admin = User::find(4);
        if (!$admin) {
            $this->error('Admin user not found');
            return;
        }
        
        // Get first rencana kegiatan
        $kegiatan = RencanaKegiatan::first();
        if (!$kegiatan) {
            $this->error('No rencana kegiatan found');
            return;
        }
        
        $this->info('Admin: ' . $admin->name);
        $this->info('Kegiatan: ' . $kegiatan->nama_kegiatan);
        $this->info('Kegiatan UUID: ' . $kegiatan->uuid);
        $this->info('Kegiatan ID: ' . $kegiatan->id);
        
        // Create notification
        $admin->notify(new StatusKegiatanNotification(
            $kegiatan->uuid,
            $kegiatan->nama_kegiatan,
            'disetujui',
            'Test keterangan notifikasi',
            now()
        ));
        
        $this->info('Notification created successfully!');
        
        // Check notifications
        $notifications = $admin->notifications()->latest()->take(1)->get();
        foreach ($notifications as $notification) {
            $this->info('Notification data: ' . json_encode($notification->data));
        }
    }
}
