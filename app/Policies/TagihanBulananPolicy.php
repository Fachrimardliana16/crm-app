<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TagihanBulanan;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagihanBulananPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_tagihan::bulanan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TagihanBulanan $tagihanBulanan): bool
    {
        return $user->can('view_tagihan::bulanan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_tagihan::bulanan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TagihanBulanan $tagihanBulanan): bool
    {
        return $user->can('update_tagihan::bulanan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TagihanBulanan $tagihanBulanan): bool
    {
        return $user->can('delete_tagihan::bulanan');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_tagihan::bulanan');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, TagihanBulanan $tagihanBulanan): bool
    {
        return $user->can('force_delete_tagihan::bulanan');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_tagihan::bulanan');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, TagihanBulanan $tagihanBulanan): bool
    {
        return $user->can('restore_tagihan::bulanan');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_tagihan::bulanan');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, TagihanBulanan $tagihanBulanan): bool
    {
        return $user->can('replicate_tagihan::bulanan');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_tagihan::bulanan');
    }
}
