<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kembalikan status rencana_kegiatan ke 'disetujui' jika laporan belum berstatus 'final' atau tidak ada
        DB::table('rencana_kegiatans')
            ->where('status', 'selesai')
            ->where(function ($query) {
                $query->whereNotExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('laporan_kegiatans')
                        ->whereColumn('laporan_kegiatans.rencana_kegiatan_id', 'rencana_kegiatans.id')
                        ->where('laporan_kegiatans.status', 'final');
                });
            })
            ->update([
                'status' => 'disetujui',
                'keterangan_status' => null
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback required for data correction
    }
};
