<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify ENUM to include 'draft' and set it as default
        DB::statement("ALTER TABLE rencana_kegiatans MODIFY COLUMN status ENUM('draft', 'diajukan', 'disetujui', 'revisi', 'ditolak', 'selesai') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For down, we don't know what to do with 'draft' records if we remove the 'draft' status.
        // It's safer to just set default back to 'diajukan' and hope there are no 'draft' records.
        // Or update 'draft' to 'diajukan' before changing the enum.
        DB::statement("UPDATE rencana_kegiatans SET status = 'diajukan' WHERE status = 'draft'");
        DB::statement("ALTER TABLE rencana_kegiatans MODIFY COLUMN status ENUM('diajukan', 'disetujui', 'revisi', 'ditolak', 'selesai') DEFAULT 'diajukan'");
    }
};
