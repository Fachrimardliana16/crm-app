<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BacaanMeter;
use Illuminate\Auth\Access\HandlesAuthorization;

class BacaanMeterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_bacaan::meter');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BacaanMeter $bacaanMeter): bool
    {
        return $user->can('view_bacaan::meter');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_bacaan::meter');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BacaanMeter $bacaanMeter): bool
    {
        return $user->can('update_bacaan::meter');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BacaanMeter $bacaanMeter): bool
    {
        return $user->can('delete_bacaan::meter');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_bacaan::meter');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, BacaanMeter $bacaanMeter): bool
    {
        return $user->can('force_delete_bacaan::meter');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_bacaan::meter');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, BacaanMeter $bacaanMeter): bool
    {
        return $user->can('restore_bacaan::meter');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_bacaan::meter');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, BacaanMeter $bacaanMeter): bool
    {
        return $user->can('replicate_bacaan::meter');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_bacaan::meter');
    }
}
