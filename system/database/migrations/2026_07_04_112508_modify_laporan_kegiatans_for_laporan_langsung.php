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
            $table->uuid('rencana_kegiatan_id')->nullable()->change();
            $table->string('judul_kegiatan')->nullable()->after('rencana_kegiatan_id');
            $table->string('lokasi_kegiatan')->nullable()->after('judul_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->uuid('rencana_kegiatan_id')->nullable(false)->change();
            $table->dropColumn(['judul_kegiatan', 'lokasi_kegiatan']);
        });
    }
};
