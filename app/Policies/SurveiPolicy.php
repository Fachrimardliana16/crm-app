<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Survei;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_survei');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Survei $survei): bool
    {
        return $user->can('view_survei');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_survei');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Survei $survei): bool
    {
        return $user->can('update_survei');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Survei $survei): bool
    {
        return $user->can('delete_survei');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_survei');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Survei $survei): bool
    {
        return $user->can('force_delete_survei');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_survei');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Survei $survei): bool
    {
        return $user->can('restore_survei');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_survei');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Survei $survei): bool
    {
        return $user->can('replicate_survei');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_survei');
    }
}
