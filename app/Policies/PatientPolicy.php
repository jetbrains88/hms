<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    /**
     * Determine if user can view any patients
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_patients');
    }

    /**
     * Determine if user can view the patient
     */
    public function view(User $user, Patient $patient): bool
    {
        if (!$user->hasPermission('view_patients')) {
            return false;
        }
        
        // Super admin can view all
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        // Check branch access
        return $user->branches()->where('branch_id', $patient->branch_id)->exists();
    }

    /**
     * Determine if user can create patients
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_patients');
    }

    /**
     * Determine if user can update the patient
     */
    public function update(User $user, Patient $patient): bool
    {
        if (!$user->hasPermission('edit_patients')) {
            return false;
        }
        
        // Super admin can update all
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        // Check branch access
        return $user->branches()->where('branch_id', $patient->branch_id)->exists();
    }

    /**
     * Determine if user can delete the patient
     */
    public function delete(User $user, Patient $patient): bool
    {
        if (!$user->hasPermission('delete_patients')) {
            return false;
        }
        
        // Only super admin and admin can delete
        return $user->hasAnyRole(['super_admin', 'admin']);
    }
}