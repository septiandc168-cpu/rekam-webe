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
        return $user->isAdmin() || $user->isAnggota();
    }

    /**
     * Determine whether user can view model.
     */
    public function view(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Admin can view laporan except draft
        if ($user->isAdmin()) {
            return $laporanKegiatan->status !== \App\Models\LaporanKegiatan::STATUS_DRAFT;
        }

        // Anggota can only view their own laporan
        if ($user->isAnggota()) {
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
        // Only anggota can create laporan
        return $user->isAnggota();
    }

    /**
     * Determine whether user can update model.
     */
    public function update(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAnggota()) {
            return $laporanKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAnggota()) {
            return $laporanKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAnggota()) {
            return $laporanKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAnggota()) {
            return $laporanKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can print laporan.
     */
    public function print(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Both admin and supervisor can print
        return $user->isAdmin() || $user->isAnggota();
    }
}
