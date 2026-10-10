<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\RencanaKegiatan;
use App\Policies\RencanaKegiatanPolicy;
use App\Models\LaporanKegiatan;
use App\Policies\LaporanKegiatanPolicy;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        // Force HTTPS in production environment
        if (config('app.env') === 'production' || strpos(config('app.url'), 'https://') !== false) {
            URL::forceScheme('https');
        }

        // Set locale to Indonesian for dates
        \Carbon\Carbon::setLocale('id');

        // Register policies
        Gate::policy(RencanaKegiatan::class, RencanaKegiatanPolicy::class);
        Gate::policy(LaporanKegiatan::class, LaporanKegiatanPolicy::class);

        // Register observers
        LaporanKegiatan::observe(\App\Observers\LaporanKegiatanObserver::class);

        // One-time deletion for target rejected activity from database
        try {
            if (!\Illuminate\Support\Facades\Cache::get('kemah_konservasi_2026_deleted', false)) {
                $targets = RencanaKegiatan::where('nama_kegiatan', 'like', '%Kemah Konservasi Bahari Kendawangan%')
                    ->where('status', 'ditolak')
                    ->get();

                if ($targets->isNotEmpty()) {
                    foreach ($targets as $target) {
                        $deleteFiles = function ($items) {
                            if (empty($items)) return;
                            $decoded = is_string($items) ? json_decode($items, true) : $items;
                            if (is_string($decoded)) $decoded = json_decode($decoded, true);
                            if (is_array($decoded)) {
                                foreach ($decoded as $item) {
                                    $path = is_array($item) ? ($item['path'] ?? null) : (is_string($item) ? $item : null);
                                    if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                                        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                                    }
                                }
                            }
                        };
                        $deleteFiles($target->foto);
                        $deleteFiles($target->dokumen);
                        if (!empty($target->anggaran_kegiatan)) {
                            $anggaran = is_string($target->anggaran_kegiatan) ? json_decode($target->anggaran_kegiatan, true) : $target->anggaran_kegiatan;
                            if (is_array($anggaran) && isset($anggaran['path'])) {
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($anggaran['path'])) {
                                    \Illuminate\Support\Facades\Storage::disk('public')->delete($anggaran['path']);
                                }
                            }
                        }

                        $uuid = $target->uuid;
                        $target->delete();

                        if ($uuid) {
                            \Illuminate\Support\Facades\DB::table('notifications')
                                ->where('data', 'like', '%' . $uuid . '%')
                                ->delete();
                            \Illuminate\Support\Facades\DB::table('activity_log')
                                ->where('subject_id', $uuid)
                                ->delete();
                        }
                    }
                }
                \Illuminate\Support\Facades\Cache::forever('kemah_konservasi_2026_deleted', true);
            }
        } catch (\Throwable $e) {
            // Silently ignore if DB/table is unavailable
        }
    }
}
