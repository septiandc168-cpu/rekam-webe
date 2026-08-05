<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memverifikasi bahwa user adalah admin.
 * (Nama kelas dipertahankan agar tidak perlu mengubah registrasi di bootstrap/app.php)
 */
class VerifyIsSupervisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role_id = $request->user()->role_id;
        $adminRoleId = Role::where('role_name', 'admin')->first()->id;

        if ($role_id != $adminRoleId) {
            Alert::error('Gagal', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->route('home');
        }

        return $next($request);
    }
}
