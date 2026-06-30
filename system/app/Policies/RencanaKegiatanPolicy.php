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
        return in_array($user->role->role_name, ['admin', 'supervisor']);
    }

    /**
     * Determine whether user can view model.
     */
    public function view(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Supervisor can view any rencana kegiatan
        if ($user->role->role_name === 'supervisor') {
            return true;
        }

        // Admin can only view their own rencana kegiatan
        if ($user->role->role_name === 'admin') {
            return $rencanaKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin can create rencana kegiatan
        return $user->role->role_name === 'admin';
    }

    /**
     * Determine whether user can update model.
     */
    public function update(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Supervisor can update any rencana kegiatan
        if ($user->role->role_name === 'supervisor') {
            return true;
        }

        // Admin can only update their own rencana kegiatan with status 'ditolak' or 'revisi'
        if ($user->role->role_name === 'admin') {
            return $rencanaKegiatan->user_id === $user->id && 
                   in_array($rencanaKegiatan->status, [RencanaKegiatan::STATUS_DITOLAK, RencanaKegiatan::STATUS_REVISI]);
        }

        return false;
    }

    /**
     * Determine whether user can change status.
     */
    public function changeStatus(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only supervisor can change status
        return $user->role->role_name === 'supervisor';
    }

    /**
     * Determine whether user can update status.
     */
    public function updateStatus(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only supervisor can update status
        return $user->role->role_name === 'supervisor';
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only admin can delete rencana kegiatan
        if ($user->role->role_name === 'admin') {
            return $rencanaKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only admin can restore rencana kegiatan
        if ($user->role->role_name === 'admin') {
            return $rencanaKegiatan->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(User $user, RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only admin can force delete rencana kegiatan
        if ($user->role->role_name === 'admin') {
            return $rencanaKegiatan->user_id === $user->id;
        }

        return false;
    }
}
