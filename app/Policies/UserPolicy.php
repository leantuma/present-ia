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
     * Admin and supervisor can view employees, admins and supervisors of their company.
     */
    public function view(User $user, User $model): bool
    {
        // Allow viewing employees, admins and supervisors (not superadmin)
        if ($model->role === 'superadmin') {
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
     * Admin can update employees, admins and supervisors of their company.
     * Supervisor can only update employees (not change roles).
     */
    public function update(User $user, User $model): bool
    {
        // Must belong to the same company
        if ($user->company_id !== $model->company_id) {
            return false;
        }

        // Supervisor can only update employees (not admins or supervisors)
        if ($user->isSupervisor() && $model->role !== 'employee') {
            return false;
        }

        // Admin can update employees, admins and supervisors (not superadmin)
        if ($user->isAdmin()) {
            return $model->role !== 'superadmin';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Admin can soft delete employees, admins and supervisors of their company.
     * Supervisor can only soft delete employees.
     */
    public function delete(User $user, User $model): bool
    {
        // Must belong to the same company
        if ($user->company_id !== $model->company_id) {
            return false;
        }

        // Cannot delete superadmin
        if ($model->role === 'superadmin') {
            return false;
        }

        // Admin cannot delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        // Supervisor can only delete employees
        if ($user->isSupervisor() && $model->role !== 'employee') {
            return false;
        }

        // Admin can delete employees, admins and supervisors
        return $user->isAdmin() || $user->isSupervisor();
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

    /**
     * Determine whether the user can change the role of a model.
     * Only Admin can change roles.
     */
    public function changeRole(User $user, User $model): bool
    {
        // Only Admin can change roles
        if (!$user->isAdmin()) {
            return false;
        }

        // Must belong to the same company
        if ($user->company_id !== $model->company_id) {
            return false;
        }

        // Cannot change role of superadmin
        if ($model->role === 'superadmin') {
            return false;
        }

        // Admin cannot change their own role
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }
}
