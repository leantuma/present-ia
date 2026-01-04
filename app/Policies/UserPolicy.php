<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     * Admin and supervisor can view employees of their company.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isSupervisor();
    }

    /**
     * Determine whether the user can view the model.
     * Admin and supervisor can view employees of their company.
     */
    public function view(User $user, User $model): bool
    {
        // Only allow viewing employees (not admins or supervisors)
        if ($model->role !== 'employee') {
            return false;
        }

        // Must belong to the same company
        return ($user->isAdmin() || $user->isSupervisor()) 
            && $user->company_id === $model->company_id;
    }

    /**
     * Determine whether the user can create models.
     * Admin and supervisor can create employees in their company.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSupervisor();
    }

    /**
     * Determine whether the user can update the model.
     * Admin and supervisor can update employees of their company.
     */
    public function update(User $user, User $model): bool
    {
        // Only allow updating employees
        if ($model->role !== 'employee') {
            return false;
        }

        // Must belong to the same company
        return ($user->isAdmin() || $user->isSupervisor()) 
            && $user->company_id === $model->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Admin and supervisor can soft delete employees of their company.
     */
    public function delete(User $user, User $model): bool
    {
        // Only allow deleting employees
        if ($model->role !== 'employee') {
            return false;
        }

        // Must belong to the same company
        return ($user->isAdmin() || $user->isSupervisor()) 
            && $user->company_id === $model->company_id;
    }

    /**
     * Determine whether the user can restore the model.
     * Admin and supervisor can restore employees of their company.
     */
    public function restore(User $user, User $model): bool
    {
        // Only allow restoring employees
        if ($model->role !== 'employee') {
            return false;
        }

        // Must belong to the same company
        return ($user->isAdmin() || $user->isSupervisor()) 
            && $user->company_id === $model->company_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Not allowed for admin/supervisor (only soft delete).
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
