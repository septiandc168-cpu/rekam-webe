<?php

namespace App\Policies;

use App\Models\RencanaKegiatan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RencanaKegiatanPolicy
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
    public function view(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Admin can view rencana kegiatan except draft
        if ($user->isAdmin()) {
            return $rencanaKegiatan->status !== \App\Models\RencanaKegiatan::STATUS_DRAFT;
        }

        // Anggota can only view their own rencana kegiatan
        if ($user->isAnggota()) {
            if ($rencanaKegiatan->user_id === $user->id) {
                return true;
            }
            // Transparansi terkontrol: bisa melihat punya orang lain asalkan bukan draft/revisi
            return !in_array($rencanaKegiatan->status, [\App\Models\RencanaKegiatan::STATUS_DRAFT, \App\Models\RencanaKegiatan::STATUS_REVISI]);
        }

        return false;
    }

    /**
     * Determine whether user can create models.
     */
    public function create(User $user): bool
    {
        // Only anggota can create rencana kegiatan
        return $user->isAnggota();
    }

    /**
     * Determine whether user can update model.
     */
    public function update(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Admin can update any rencana kegiatan
        if ($user->isAdmin()) {
            return true;
        }

        // Anggota can only update their own rencana kegiatan
        // with status 'draft' or 'revisi'
        if ($user->isAnggota()) {
            return $rencanaKegiatan->user_id === $user->id && 
                   in_array($rencanaKegiatan->status, [
                       RencanaKegiatan::STATUS_DRAFT,
                       RencanaKegiatan::STATUS_REVISI,
                   ]);
        }

        return false;
    }

    /**
     * Determine whether user can change status.
     */
    public function changeStatus(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only admin can change status
        return $user->isAdmin();
    }

    /**
     * Determine whether user can update status.
     */
    public function updateStatus(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only admin can update status
        return $user->isAdmin();
    }

    /**
     * Determine whether user can delete model.
     * Anggota CANNOT delete rencana that are already approved, completed,
     * or pending verification — these are considered "locked" states.
     */
    public function delete(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        if ($user->isAnggota()) {
            return $rencanaKegiatan->user_id === $user->id
                && $rencanaKegiatan->status === RencanaKegiatan::STATUS_DRAFT;
        }

        return false;
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        if ($user->isAnggota()) {
            return $rencanaKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can permanently delete model.
     * Same locked-status protection as soft delete.
     */
    public function forceDelete(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        if ($user->isAnggota()) {
            return $rencanaKegiatan->user_id === $user->id
                && $rencanaKegiatan->status === RencanaKegiatan::STATUS_DRAFT;
        }

        return false;
    }
}
