<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All users can view schedules
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        return $user->company_id === $schedule->company_id;
    }

    /**
     * Determine whether the user can create models.
     * Only Admin can create schedules.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     * Only Admin can update schedules.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return $user->isAdmin() && $user->company_id === $schedule->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        return $user->isAdmin() && $user->company_id === $schedule->company_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schedule $schedule): bool
    {
        return $user->isAdmin() && $user->company_id === $schedule->company_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schedule $schedule): bool
    {
        return $user->isAdmin() && $user->company_id === $schedule->company_id;
    }
}
