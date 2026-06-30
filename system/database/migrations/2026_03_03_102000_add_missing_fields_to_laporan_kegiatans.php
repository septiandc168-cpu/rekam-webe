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
            // Add metode_pelaksanaan if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'metode_pelaksanaan')) {
                $table->enum('metode_pelaksanaan', ['workshop', 'fgd', 'sosialisasi', 'penanaman', 'patroli', 'lainnya'])->nullable()->after('waktu_selesai');
            }
            
            // Add metode_pelaksanaan_lainnya if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'metode_pelaksanaan_lainnya')) {
                $table->string('metode_pelaksanaan_lainnya')->nullable()->after('metode_pelaksanaan');
            }
            
            // Add rangkaian_kegiatan if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'rangkaian_kegiatan')) {
                $table->text('rangkaian_kegiatan')->nullable()->after('metode_pelaksanaan_lainnya');
            }
            
            // Add realisasi_peserta if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'realisasi_peserta')) {
                $table->integer('realisasi_peserta')->nullable()->after('rangkaian_kegiatan');
            }
            
            // Add profil_peserta if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'profil_peserta')) {
                $table->text('profil_peserta')->nullable()->after('realisasi_peserta');
            }
            
            // Add hasil_dicapai if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'hasil_dicapai')) {
                $table->text('hasil_dicapai')->nullable()->after('profil_peserta');
            }
            
            // Add output_nyata if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'output_nyata')) {
                $table->text('output_nyata')->nullable()->after('hasil_dicapai');
            }
            
            // Add dampak_awal if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'dampak_awal')) {
                $table->text('dampak_awal')->nullable()->after('output_nyata');
            }
            
            // Add kendala if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'kendala')) {
                $table->text('kendala')->nullable()->after('dampak_awal');
            }
            
            // Add solusi if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'solusi')) {
                $table->text('solusi')->nullable()->after('kendala');
            }
            
            // Add evaluasi_rekomendasi if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'evaluasi_rekomendasi')) {
                $table->text('evaluasi_rekomendasi')->nullable()->after('solusi');
            }
            
            // Add status if not exists
            if (!Schema::hasColumn('laporan_kegiatans', 'status')) {
                $table->enum('status', ['draft', 'final'])->default('draft')->after('evaluasi_rekomendasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->dropColumn([
                'metode_pelaksanaan',
                'metode_pelaksanaan_lainnya',
                'rangkaian_kegiatan',
                'realisasi_peserta',
                'profil_peserta',
                'hasil_dicapai',
                'output_nyata',
                'dampak_awal',
                'kendala',
                'solusi',
                'evaluasi_rekomendasi',
                'status'
            ]);
        });
    }
};
