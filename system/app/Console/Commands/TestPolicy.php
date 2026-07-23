<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\RencanaKegiatan;

class TestPolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test policy permissions for supervisor and admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing RencanaKegiatan Policy...');
        
        // Get users
        $supervisor = User::whereHas('role', function($query) {
            $query->where('role_name', 'admin');
        })->first();
        
        $admin = User::whereHas('role', function($query) {
            $query->where('role_name', 'anggota');
        })->first();
        
        if (!$supervisor) {
            $this->error('No supervisor user found');
            return;
        }
        
        if (!$admin) {
            $this->error('No admin user found');
            return;
        }
        
        // Get a rencana kegiatan for testing
        $rencana = RencanaKegiatan::first();
        
        if (!$rencana) {
            $this->error('No rencana kegiatan found for testing');
            return;
        }
        
        $this->info("Testing with Rencana Kegiatan ID: {$rencana->id}");
        
        // Test supervisor permissions
        $this->info("\n=== SUPERVISOR PERMISSIONS ===");
        $this->info("Can create: " . ($supervisor->can('create', RencanaKegiatan::class) ? 'YES' : 'NO'));
        $this->info("Can delete this rencana: " . ($supervisor->can('delete', $rencana) ? 'YES' : 'NO'));
        $this->info("Can restore this rencana: " . ($supervisor->can('restore', $rencana) ? 'YES' : 'NO'));
        $this->info("Can force delete this rencana: " . ($supervisor->can('forceDelete', $rencana) ? 'YES' : 'NO'));
        
        // Test admin permissions
        $this->info("\n=== ADMIN PERMISSIONS ===");
        $this->info("Can create: " . ($admin->can('create', RencanaKegiatan::class) ? 'YES' : 'NO'));
        $this->info("Can delete this rencana: " . ($admin->can('delete', $rencana) ? 'YES' : 'NO'));
        $this->info("Can restore this rencana: " . ($admin->can('restore', $rencana) ? 'YES' : 'NO'));
        $this->info("Can force delete this rencana: " . ($admin->can('forceDelete', $rencana) ? 'YES' : 'NO'));
        
        // Test admin with own rencana
        if ($rencana->user_id === $admin->id) {
            $this->info("\n=== ADMIN WITH OWN RENCANA ===");
            $this->info("Can delete own rencana: " . ($admin->can('delete', $rencana) ? 'YES' : 'NO'));
        }
        
        $this->info("\nPolicy test completed!");
    }
}
