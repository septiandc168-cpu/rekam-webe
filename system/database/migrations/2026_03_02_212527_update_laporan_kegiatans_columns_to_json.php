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
            $table->json('daftar_hadir')->nullable()->change();
            $table->json('notulen')->nullable()->change();
            $table->json('materi')->nullable()->change();
            $table->json('berita_acara')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kegiatans', function (Blueprint $table) {
            $table->string('daftar_hadir')->nullable()->change();
            $table->string('notulen')->nullable()->change();
            $table->string('materi')->nullable()->change();
            $table->string('berita_acara')->nullable()->change();
        });
    }
};
