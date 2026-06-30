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
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->enum('status', ['diajukan', 'disetujui', 'revisi', 'ditolak', 'selesai'])->default('diajukan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->enum('status', ['diajukan', 'disetujui', 'revisi', 'ditolak', 'selesai'])->default('diajukan')->change();
        });
    }
};
