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
            if (!Schema::hasColumn('laporan_kegiatans', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('lokasi_kegiatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            if (Schema::hasColumn('laporan_kegiatans', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
        });
    }
};
