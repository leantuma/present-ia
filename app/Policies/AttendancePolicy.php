<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttendancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view attendances
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        // Users can view their own attendances, admins/supervisors can view all in their company
        return $user->company_id === $attendance->company_id &&
               ($user->id === $attendance->user_id || $user->isAdmin() || $user->isSupervisor());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All users can check in/out
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        // Only admins and supervisors can update attendances
        return $user->company_id === $attendance->company_id &&
               ($user->isAdmin() || $user->isSupervisor());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin() && $user->company_id === $attendance->company_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin() && $user->company_id === $attendance->company_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin() && $user->company_id === $attendance->company_id;
    }
}
