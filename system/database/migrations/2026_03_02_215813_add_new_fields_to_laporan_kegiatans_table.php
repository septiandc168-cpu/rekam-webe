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
            // Update metode_pelaksanaan dari varchar ke enum
            $table->dropColumn('metode_pelaksanaan');
        });

        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->enum('metode_pelaksanaan', ['workshop', 'fgd', 'sosialisasi', 'penanaman', 'patroli', 'lainnya'])->nullable()->after('waktu_pelaksanaan');
            
            // Rename metode_lainnya to metode_pelaksanaan_lainnya
            $table->renameColumn('metode_lainnya', 'metode_pelaksanaan_lainnya');
            
            // Rename hasil_yang_dicapai to hasil_dicapai
            $table->renameColumn('hasil_yang_dicapai', 'hasil_dicapai');
            
            // Rename kendala_dihadapi to kendala
            $table->renameColumn('kendala_dihadapi', 'kendala');
            
            // Rename solusi_dilakukan to solusi
            $table->renameColumn('solusi_dilakukan', 'solusi');
            
            // Rename catatan_evaluasi to evaluasi_rekomendasi
            $table->renameColumn('catatan_evaluasi', 'evaluasi_rekomendasi');
            
            // Rename foto_kegiatan to dokumentasi
            $table->renameColumn('foto_kegiatan', 'dokumentasi');
            
            // Update profil_peserta from json to text
            $table->dropColumn('profil_peserta');
        });

        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->text('profil_peserta')->nullable()->after('realisasi_peserta');
            
            // Status untuk draft/final
            $table->enum('status', ['draft', 'final'])->default('draft')->after('evaluasi_rekomendasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('profil_peserta');
        });

        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            // Reverse renames
            $table->renameColumn('dokumentasi', 'foto_kegiatan');
            $table->renameColumn('evaluasi_rekomendasi', 'catatan_evaluasi');
            $table->renameColumn('solusi', 'solusi_dilakukan');
            $table->renameColumn('kendala', 'kendala_dihadapi');
            $table->renameColumn('hasil_dicapai', 'hasil_yang_dicapai');
            $table->renameColumn('metode_pelaksanaan_lainnya', 'metode_lainnya');
            
            // Drop enum metode_pelaksanaan
            $table->dropColumn('metode_pelaksanaan');
        });

        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            // Add back varchar metode_pelaksanaan
            $table->string('metode_pelaksanaan')->nullable()->after('waktu_pelaksanaan');
            
            // Add back json profil_peserta
            $table->json('profil_peserta')->nullable()->after('realisasi_peserta');
        });
    }
};
