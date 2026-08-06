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
    }
}
