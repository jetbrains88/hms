<?php

namespace App\Policies;

use App\Models\Diagnosis;
use App\Models\User;

class DiagnosisPolicy
{
    /**
     * Determine if user can view any diagnoses
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_diagnoses');
    }

    /**
     * Determine if user can view the diagnosis
     */
    public function view(User $user, Diagnosis $diagnosis): bool
    {
        if (!$user->hasPermission('view_diagnoses')) {
            return false;
        }

        // Super admin can view all
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Check branch access
        if (!$user->branches()->where('branch_id', $diagnosis->branch_id)->exists()) {
            return false;
        }

        // Role-specific access rules
        if ($user->hasRole('doctor')) {
            // Doctors can view diagnoses they created OR for patients they're treating
            return $diagnosis->doctor_id === $user->id || 
                   $diagnosis->visit->doctor_id === $user->id ||
                   $user->hasPermission('view_all_diagnoses');
        }

        if ($user->hasRole('nurse')) {
            // Nurses can view diagnoses for patients they're caring for
            return true;
        }

        if ($user->hasRole('pharmacy')) {
            // Pharmacists can view diagnoses related to prescriptions
            return $diagnosis->has_prescription;
        }

        return false;
    }

    /**
     * Determine if user can create diagnoses
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_diagnoses') && $user->hasRole('doctor');
    }

    /**
     * Determine if user can update the diagnosis
     */
    public function update(User $user, Diagnosis $diagnosis): bool
    {
        if (!$user->hasPermission('edit_diagnoses')) {
            return false;
        }

        // Super admin can update all
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Check branch access
        if (!$user->branches()->where('branch_id', $diagnosis->branch_id)->exists()) {
            return false;
        }

        // Only the doctor who created the diagnosis can update it
        return $diagnosis->doctor_id === $user->id;
    }

    /**
     * Determine if user can delete the diagnosis
     */
    public function delete(User $user, Diagnosis $diagnosis): bool
    {
        if (!$user->hasPermission('delete_diagnoses')) {
            return false;
        }

        // Only super admin and admin can delete diagnoses
        return $user->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * Determine if user can add prescriptions to this diagnosis
     */
    public function addPrescription(User $user, Diagnosis $diagnosis): bool
    {
        if (!$user->hasPermission('create_prescriptions')) {
            return false;
        }

        // Only the doctor who created the diagnosis can add prescriptions
        return $diagnosis->doctor_id === $user->id;
    }

    /**
     * Determine if user can view prescriptions for this diagnosis
     */
    public function viewPrescriptions(User $user, Diagnosis $diagnosis): bool
    {
        if (!$user->hasPermission('view_prescriptions')) {
            return false;
        }

        // Check branch access
        if (!$user->branches()->where('branch_id', $diagnosis->branch_id)->exists()) {
            return false;
        }

        // Doctors can view prescriptions they created
        if ($user->hasRole('doctor') && $diagnosis->doctor_id === $user->id) {
            return true;
        }

        // Pharmacists can view prescriptions for dispensing
        if ($user->hasRole('pharmacy')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can mark diagnosis as chronic
     */
    public function markChronic(User $user, Diagnosis $diagnosis): bool
    {
        // Only the diagnosing doctor can mark as chronic
        return $diagnosis->doctor_id === $user->id;
    }

    /**
     * Determine if user can view patient diagnosis history
     */
    public function viewPatientHistory(User $user, int $patientId): bool
    {
        if (!$user->hasPermission('view_patient_medical_history')) {
            return false;
        }

        // All clinical staff can view patient history
        return $user->hasAnyRole(['doctor', 'nurse', 'admin', 'super_admin']);
    }

    /**
     * Determine if user can export diagnosis report
     */
    public function export(User $user, Diagnosis $diagnosis): bool
    {
        if (!$user->hasPermission('view_reports')) {
            return false;
        }

        // Check branch access
        return $user->branches()->where('branch_id', $diagnosis->branch_id)->exists();
    }

    /**
     * Determine if user can add lab orders based on diagnosis
     */
    public function addLabOrder(User $user, Diagnosis $diagnosis): bool
    {
        if (!$user->hasPermission('create_lab_orders')) {
            return false;
        }

        // Only the diagnosing doctor can order labs
        return $diagnosis->doctor_id === $user->id;
    }

    /**
     * Determine if user can update severity of diagnosis
     */
    public function updateSeverity(User $user, Diagnosis $diagnosis): bool
    {
        // Only the diagnosing doctor can update severity
        return $diagnosis->doctor_id === $user->id;
    }

    /**
     * Determine if user can set follow-up date
     */
    public function setFollowup(User $user, Diagnosis $diagnosis): bool
    {
        // Only the diagnosing doctor can set follow-up
        return $diagnosis->doctor_id === $user->id;
    }

    /**
     * Get all diagnoses for a patient that user can access
     */
    public function accessiblePatientDiagnoses(User $user, int $patientId): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        if ($user->hasRole('doctor')) {
            // Doctors can access diagnoses for patients they've treated
            return Diagnosis::whereHas('visit', function ($query) use ($patientId) {
                    $query->where('patient_id', $patientId);
                })
                ->where('doctor_id', $user->id)
                ->exists();
        }

        if ($user->hasRole('nurse')) {
            // Nurses can access diagnoses for patients in their branch
            return Diagnosis::whereHas('visit', function ($query) use ($patientId, $user) {
                    $query->where('patient_id', $patientId)
                          ->whereIn('branch_id', $user->branches()->pluck('branch_id'));
                })
                ->exists();
        }

        return false;
    }
}