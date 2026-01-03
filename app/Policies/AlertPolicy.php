<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AlertPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All users can view alerts
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Alert $alert): bool
    {
        return $user->company_id === $alert->company_id &&
               ($user->id === $alert->user_id || $user->isAdmin() || $user->isSupervisor() || is_null($alert->user_id));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // Alerts are system-generated
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Alert $alert): bool
    {
        // Users can mark their own alerts as read, admins/supervisors can update any
        return $user->company_id === $alert->company_id &&
               ($user->id === $alert->user_id || $user->isAdmin() || $user->isSupervisor());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alert $alert): bool
    {
        return ($user->isAdmin() || $user->isSupervisor()) &&
               $user->company_id === $alert->company_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Alert $alert): bool
    {
        return $user->isAdmin() && $user->company_id === $alert->company_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Alert $alert): bool
    {
        return $user->isAdmin() && $user->company_id === $alert->company_id;
    }
}
