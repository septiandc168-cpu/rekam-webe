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
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->date('realisasi_tanggal_mulai')->nullable()->after('rencana_kegiatan_id');
            $table->date('realisasi_tanggal_selesai')->nullable()->after('realisasi_tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->dropColumn(['realisasi_tanggal_mulai', 'realisasi_tanggal_selesai']);
        });
    }
};
