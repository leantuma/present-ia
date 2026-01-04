<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;

class LeavePolicy
{
    /**
     * Determine whether the user can view any models.
     * Admin, supervisor and employees can view leaves of their company.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view leaves
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Employee can view their own leaves, admin/supervisor can view all leaves of their company.
     */
    public function view(User $user, Leave $leave): bool
    {
        // Must belong to the same company
        if ($user->company_id !== $leave->company_id) {
            return false;
        }

        // Employee can only view their own leaves
        if ($user->isEmployee()) {
            return $user->id === $leave->user_id;
        }

        // Admin and supervisor can view all leaves of their company
        return $user->isAdmin() || $user->isSupervisor();
    }

    /**
     * Determine whether the user can create models.
     * All authenticated users can create leave requests.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Only admin/supervisor can update (approve/reject) leaves.
     */
    public function update(User $user, Leave $leave): bool
    {
        // Must belong to the same company
        if ($user->company_id !== $leave->company_id) {
            return false;
        }

        // Only admin and supervisor can update
        return $user->isAdmin() || $user->isSupervisor();
    }

    /**
     * Determine whether the user can delete the model.
     * Only admin/supervisor can delete leaves.
     */
    public function delete(User $user, Leave $leave): bool
    {
        // Must belong to the same company
        if ($user->company_id !== $leave->company_id) {
            return false;
        }

        // Only admin and supervisor can delete
        return $user->isAdmin() || $user->isSupervisor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Leave $leave): bool
    {
        // Must belong to the same company
        if ($user->company_id !== $leave->company_id) {
            return false;
        }

        // Only admin and supervisor can restore
        return $user->isAdmin() || $user->isSupervisor();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Leave $leave): bool
    {
        return false; // Not allowed
    }

    /**
     * Determine whether the user can approve the leave.
     * Only admin/supervisor can approve.
     */
    public function approve(User $user, Leave $leave): bool
    {
        // Must belong to the same company
        if ($user->company_id !== $leave->company_id) {
            return false;
        }

        // Only admin and supervisor can approve
        return ($user->isAdmin() || $user->isSupervisor()) && $leave->isPending();
    }

    /**
     * Determine whether the user can reject the leave.
     * Only admin/supervisor can reject.
     */
    public function reject(User $user, Leave $leave): bool
    {
        // Must belong to the same company
        if ($user->company_id !== $leave->company_id) {
            return false;
        }

        // Only admin and supervisor can reject
        return ($user->isAdmin() || $user->isSupervisor()) && $leave->isPending();
    }
}
