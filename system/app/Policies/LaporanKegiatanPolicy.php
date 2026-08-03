<?php

namespace App\Policies;

use App\Models\LaporanKegiatan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LaporanKegiatanPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Both admin and supervisor can view list
        return in_array($user->role->role_name, ['anggota', 'admin']);
    }

    /**
     * Determine whether user can view model.
     */
    public function view(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Admin can view laporan except draft/revisi
        if ($user->role->role_name === 'admin') {
            return !in_array($laporanKegiatan->status, [\App\Models\LaporanKegiatan::STATUS_DRAFT, \App\Models\LaporanKegiatan::STATUS_REVISI]);
        }

        // Anggota can only view their own laporan
        if ($user->role->role_name === 'anggota') {
            if ($laporanKegiatan->user_id === $user->id) {
                return true;
            }
            // Transparansi terkontrol: bisa melihat punya orang lain asalkan bukan draft/revisi
            return !in_array($laporanKegiatan->status, [\App\Models\LaporanKegiatan::STATUS_DRAFT, \App\Models\LaporanKegiatan::STATUS_REVISI]);
        }

        return false;
    }

    /**
     * Determine whether user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin can create laporan
        return $user->role->role_name === 'anggota';
    }

    /**
     * Determine whether user can update model.
     */
    public function update(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Only admin can update laporan
        if ($user->role->role_name === 'anggota') {
            return $laporanKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Only admin can delete laporan
        if ($user->role->role_name === 'anggota') {
            return $laporanKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Only admin can restore laporan
        return $user->role->role_name === 'anggota';
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Only admin can force delete laporan
        return $user->role->role_name === 'anggota';
    }

    /**
     * Determine whether user can print laporan.
     */
    public function print(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Both admin and supervisor can print
        return in_array($user->role->role_name, ['anggota', 'admin']);
    }
}
