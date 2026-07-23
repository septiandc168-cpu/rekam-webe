<?php

namespace App\Observers;

use App\Models\LaporanKegiatan;

class LaporanKegiatanObserver
{
    /**
     * Handle the LaporanKegiatan "created" event.
     */
    public function created(LaporanKegiatan $laporanKegiatan): void
    {
        //
    }

    /**
     * Handle the LaporanKegiatan "updated" event.
     */
    public function updated(LaporanKegiatan $laporanKegiatan): void
    {
        // Logika "Final Closure" (mengubah Rencana menjadi selesai saat Laporan final)
        // telah dipindahkan ke LaporanKegiatanController menggunakan DB::transaction
    }

    /**
     * Handle the LaporanKegiatan "deleted" event.
     */
    public function deleted(LaporanKegiatan $laporanKegiatan): void
    {
        //
    }

    /**
     * Handle the LaporanKegiatan "restored" event.
     */
    public function restored(LaporanKegiatan $laporanKegiatan): void
    {
        //
    }

    /**
     * Handle the LaporanKegiatan "force deleted" event.
     */
    public function forceDeleted(LaporanKegiatan $laporanKegiatan): void
    {
        //
    }
}
