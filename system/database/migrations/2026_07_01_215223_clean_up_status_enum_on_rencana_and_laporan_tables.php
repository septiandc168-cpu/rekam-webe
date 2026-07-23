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
        // 1. Bersihkan ENUM di tabel rencana_kegiatans
        // Hapus 'menunggu_verifikasi' dan 'revisi_laporan' dari definisi.
        DB::statement("ALTER TABLE rencana_kegiatans MODIFY COLUMN status ENUM('diajukan', 'disetujui', 'revisi', 'ditolak', 'selesai') DEFAULT 'diajukan'");

        // 2. Pastikan ENUM di tabel laporan_kegiatans sudah sesuai dan bersih
        DB::statement("ALTER TABLE laporan_kegiatans MODIFY COLUMN status ENUM('draft', 'diajukan', 'revisi', 'final') DEFAULT 'diajukan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke kondisi semula (Rollback)
        DB::statement("ALTER TABLE rencana_kegiatans MODIFY COLUMN status ENUM('diajukan', 'disetujui', 'revisi', 'ditolak', 'menunggu_verifikasi', 'revisi_laporan', 'selesai') DEFAULT 'diajukan'");
        
        DB::statement("ALTER TABLE laporan_kegiatans MODIFY COLUMN status ENUM('draft', 'final') DEFAULT 'draft'");
    }
};
