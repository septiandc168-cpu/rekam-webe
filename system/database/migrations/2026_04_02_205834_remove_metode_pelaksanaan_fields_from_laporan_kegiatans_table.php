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
            $table->dropColumn(['metode_pelaksanaan', 'metode_pelaksanaan_lainnya']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->string('metode_pelaksanaan')->nullable()->after('realisasi_tanggal_selesai');
            $table->string('metode_pelaksanaan_lainnya')->nullable()->after('metode_pelaksanaan');
        });
    }
};
