<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RencanaKegiatan;
use App\Models\LaporanKegiatan;
use App\Notifications\KegiatanActivityNotification;
use App\Notifications\LaporanActivityNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->admin = User::factory()->create([
            'role_id' => 2 // Assuming role_id 2 is admin
        ]);
        
        $this->supervisor = User::factory()->create([
            'role_id' => 1 // Assuming role_id 1 is supervisor
        ]);
    }

    /** @test */
    public function supervisor_receives_notification_when_admin_adds_rencana_kegiatan()
    {
        Notification::fake();

        // Create rencana kegiatan as admin
        $this->actingAs($this->admin);
        $rencana = RencanaKegiatan::factory()->create([
            'user_id' => $this->admin->id,
            'nama_kegiatan' => 'Test Kegiatan'
        ]);

        // Check if supervisor received notification
        Notification::assertSentTo($this->supervisor, KegiatanActivityNotification::class, function ($notification) use ($rencana) {
            return $notification->judul_kegiatan === 'Test Kegiatan' 
                && $notification->aksi === 'ditambahkan'
                && $notification->user_name === $this->admin->name;
        });
    }

    /** @test */
    public function supervisor_receives_notification_when_admin_edits_rencana_kegiatan()
    {
        Notification::fake();

        // Create rencana kegiatan first
        $rencana = RencanaKegiatan::factory()->create([
            'user_id' => $this->admin->id
        ]);

        // Edit as admin
        $this->actingAs($this->admin);
        $rencana->update(['nama_kegiatan' => 'Updated Kegiatan']);

        // Check if supervisor received notification
        Notification::assertSentTo($this->supervisor, KegiatanActivityNotification::class, function ($notification) {
            return $notification->aksi === 'diedit'
                && $notification->user_name === $this->admin->name;
        });
    }

    /** @test */
    public function supervisor_receives_notification_when_admin_deletes_rencana_kegiatan()
    {
        Notification::fake();

        // Create rencana kegiatan first
        $rencana = RencanaKegiatan::factory()->create([
            'user_id' => $this->admin->id,
            'nama_kegiatan' => 'Test Kegiatan'
        ]);

        // Delete as admin
        $this->actingAs($this->admin);
        $rencana->delete();

        // Check if supervisor received notification
        Notification::assertSentTo($this->supervisor, KegiatanActivityNotification::class, function ($notification) {
            return $notification->judul_kegiatan === 'Test Kegiatan' 
                && $notification->aksi === 'dihapus'
                && $notification->user_name === $this->admin->name;
        });
    }

    /** @test */
    public function supervisor_receives_notification_when_admin_adds_laporan_kegiatan()
    {
        Notification::fake();

        // Create rencana kegiatan first
        $rencana = RencanaKegiatan::factory()->create([
            'user_id' => $this->admin->id,
            'nama_kegiatan' => 'Test Kegiatan'
        ]);

        // Create laporan kegiatan as admin
        $this->actingAs($this->admin);
        $laporan = LaporanKegiatan::factory()->create([
            'user_id' => $this->admin->id,
            'rencana_kegiatan_id' => $rencana->id,
            'metode_pelaksanaan' => 'Test Metode'
        ]);

        // Check if supervisor received notification
        Notification::assertSentTo($this->supervisor, LaporanActivityNotification::class, function ($notification) use ($rencana, $laporan) {
            return $notification->judul_laporan === 'Test Metode'
                && $notification->judul_kegiatan === 'Test Kegiatan'
                && $notification->aksi === 'ditambahkan'
                && $notification->user_name === $this->admin->name;
        });
    }

    /** @test */
    public function supervisor_receives_notification_when_admin_edits_laporan_kegiatan()
    {
        Notification::fake();

        // Create rencana and laporan kegiatan first
        $rencana = RencanaKegiatan::factory()->create([
            'user_id' => $this->admin->id,
            'nama_kegiatan' => 'Test Kegiatan'
        ]);
        
        $laporan = LaporanKegiatan::factory()->create([
            'user_id' => $this->admin->id,
            'rencana_kegiatan_id' => $rencana->id,
            'metode_pelaksanaan' => 'Original Metode'
        ]);

        // Edit laporan as admin
        $this->actingAs($this->admin);
        $laporan->update(['metode_pelaksanaan' => 'Updated Metode']);

        // Check if supervisor received notification
        Notification::assertSentTo($this->supervisor, LaporanActivityNotification::class, function ($notification) {
            return $notification->aksi === 'diedit'
                && $notification->user_name === $this->admin->name;
        });
    }

    /** @test */
    public function supervisor_receives_notification_when_admin_deletes_laporan_kegiatan()
    {
        Notification::fake();

        // Create rencana and laporan kegiatan first
        $rencana = RencanaKegiatan::factory()->create([
            'user_id' => $this->admin->id,
            'nama_kegiatan' => 'Test Kegiatan'
        ]);
        
        $laporan = LaporanKegiatan::factory()->create([
            'user_id' => $this->admin->id,
            'rencana_kegiatan_id' => $rencana->id,
            'metode_pelaksanaan' => 'Test Metode'
        ]);

        // Delete laporan as admin
        $this->actingAs($this->admin);
        $laporan->delete();

        // Check if supervisor received notification
        Notification::assertSentTo($this->supervisor, LaporanActivityNotification::class, function ($notification) {
            return $notification->judul_laporan === 'Test Metode'
                && $notification->judul_kegiatan === 'Test Kegiatan'
                && $notification->aksi === 'dihapus'
                && $notification->user_name === $this->admin->name;
        });
    }

    /** @test */
    public function admin_does_not_receive_notification_for_own_actions()
    {
        Notification::fake();

        // Create rencana kegiatan as admin
        $this->actingAs($this->admin);
        $rencana = RencanaKegiatan::factory()->create([
            'user_id' => $this->admin->id
        ]);

        // Check that admin did not receive notification
        Notification::assertNotSentTo($this->admin, KegiatanActivityNotification::class);
    }
}
