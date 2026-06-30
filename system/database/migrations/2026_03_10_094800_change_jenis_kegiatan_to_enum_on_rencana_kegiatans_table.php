<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, update any existing data that doesn't match the enum values
        DB::table('rencana_kegiatans')
            ->whereNotIn('jenis_kegiatan', ['konservasi', 'usaha masyarakat', 'edukasi', 'lainnya'])
            ->whereNotNull('jenis_kegiatan')
            ->update(['jenis_kegiatan' => 'lainnya']);
            
        // Then change the column to enum
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->enum('jenis_kegiatan', ['konservasi', 'usaha masyarakat', 'edukasi', 'lainnya'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->string('jenis_kegiatan')->nullable()->change();
        });
    }
};
