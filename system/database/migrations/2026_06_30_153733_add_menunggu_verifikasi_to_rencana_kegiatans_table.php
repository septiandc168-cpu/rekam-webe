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
        // Pastikan menyertakan semua status lama beserta status baru
        DB::statement("ALTER TABLE rencana_kegiatans MODIFY COLUMN status ENUM('diajukan', 'disetujui', 'revisi', 'ditolak', 'menunggu_verifikasi', 'selesai') DEFAULT 'diajukan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE rencana_kegiatans MODIFY COLUMN status ENUM('diajukan', 'disetujui', 'revisi', 'ditolak', 'selesai') DEFAULT 'diajukan'");
    }
};
