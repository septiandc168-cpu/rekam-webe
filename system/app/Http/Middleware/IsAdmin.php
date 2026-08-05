<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware IsAdmin (sebenarnya IsAnggota)
 * Memastikan user yang mengakses route adalah anggota (bukan admin).
 * Digunakan untuk route create/store rencana dan laporan kegiatan.
 * Nama "isAdmin" dipertahankan agar tidak perlu mengubah registrasi di seluruh route/bootstrap.
 */
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role->role_name !== 'anggota') {
            abort(403, 'Unauthorized. Hanya anggota yang dapat melakukan aksi ini.');
        }

        return $next($request);
    }
}
