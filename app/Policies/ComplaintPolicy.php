<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    /**
     * Determine whether the user can view any complaints.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the specific complaint.
     * Pelapor can only view their own complaint.
     * Admin can view all.
     * Technician can view all or assigned complaints.
     */
    public function view(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin() || $user->isTechnician()) {
            return true;
        }

        return $complaint->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the complaint status/technician/tanggapan.
     * Admin can update all.
     * Technician can only update complaints assigned to them.
     */
    public function update(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTechnician()) {
            return $complaint->technician_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the complaint.
     * Admin can delete any complaint.
     * Pelapor can delete their own complaint if still pending.
     */
    public function delete(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $complaint->user_id === $user->id && $complaint->status === 'pending';
    }
}
