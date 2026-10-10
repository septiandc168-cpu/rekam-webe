<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $items = DB::table('rencana_kegiatans')
                ->where('nama_kegiatan', 'like', '%Kemah Konservasi Bahari Kendawangan%')
                ->where('status', 'ditolak')
                ->get();

            foreach ($items as $item) {
                // Hapus foto jika ada
                if (!empty($item->foto)) {
                    $fotos = json_decode($item->foto, true);
                    if (is_array($fotos)) {
                        foreach ($fotos as $foto) {
                            $path = is_array($foto) ? ($foto['path'] ?? null) : (is_string($foto) ? $foto : null);
                            if ($path && Storage::disk('public')->exists($path)) {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    }
                }

                // Hapus dokumen jika ada
                if (!empty($item->dokumen)) {
                    $dokumens = json_decode($item->dokumen, true);
                    if (is_array($dokumens)) {
                        foreach ($dokumens as $dokumen) {
                            $path = is_array($dokumen) ? ($dokumen['path'] ?? null) : (is_string($dokumen) ? $dokumen : null);
                            if ($path && Storage::disk('public')->exists($path)) {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    }
                }

                // Hapus anggaran jika ada
                if (!empty($item->anggaran_kegiatan)) {
                    $anggaran = json_decode($item->anggaran_kegiatan, true);
                    if (is_array($anggaran) && isset($anggaran['path'])) {
                        if (Storage::disk('public')->exists($anggaran['path'])) {
                            Storage::disk('public')->delete($anggaran['path']);
                        }
                    }
                }

                $uuid = $item->uuid;

                // Hapus record dari rencana_kegiatans
                DB::table('rencana_kegiatans')->where('id', $item->id)->delete();

                // Bersihkan notifikasi dan log aktivitas terkait
                if ($uuid) {
                    DB::table('notifications')->where('data', 'like', '%' . $uuid . '%')->delete();
                    DB::table('activity_log')->where('subject_id', $uuid)->delete();
                }
            }
        } catch (\Throwable $e) {
            // Silently continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation
    }
};
