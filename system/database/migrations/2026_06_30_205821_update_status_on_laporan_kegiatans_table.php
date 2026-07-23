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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE laporan_kegiatans MODIFY COLUMN status ENUM('draft', 'diajukan', 'revisi', 'final') DEFAULT 'diajukan'");
        
        \Illuminate\Support\Facades\Schema::table('laporan_kegiatans', function (\Illuminate\Database\Schema\Blueprint $table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('laporan_kegiatans', 'catatan_evaluasi')) {
                $table->text('catatan_evaluasi')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE laporan_kegiatans MODIFY COLUMN status ENUM('draft', 'final') DEFAULT 'draft'");
    }
};
