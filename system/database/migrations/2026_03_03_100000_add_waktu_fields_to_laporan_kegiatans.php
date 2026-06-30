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
        // Check if waktu_mulai exists, if not add it
        if (!Schema::hasColumn('laporan_kegiatans', 'waktu_mulai')) {
            Schema::table('laporan_kegiatans', function (Blueprint $table) {
                $table->time('waktu_mulai')->nullable()->after('metode_pelaksanaan');
            });
        }

        // Check if waktu_selesai exists, if not add it
        if (!Schema::hasColumn('laporan_kegiatans', 'waktu_selesai')) {
            Schema::table('laporan_kegiatans', function (Blueprint $table) {
                $table->time('waktu_selesai')->nullable()->after('waktu_mulai');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->dropColumn(['waktu_mulai', 'waktu_selesai']);
        });
    }
};
