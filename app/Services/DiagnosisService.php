<?php

namespace App\Services;

use App\Models\Diagnosis;
use App\Models\Visit;
use Illuminate\Support\Str;

class DiagnosisService
{
    /**
     * Create a diagnosis for a visit
     */
    public function createDiagnosis(int $visitId, int $doctorId, array $data): Diagnosis
    {
        $visit = Visit::findOrFail($visitId);
        
        $diagnosis = Diagnosis::create([
            'uuid' => (string) Str::uuid(),
            'branch_id' => $visit->branch_id,
            'visit_id' => $visitId,
            'doctor_id' => $doctorId,
            'symptoms' => $data['symptoms'] ?? null,
            'diagnosis' => $data['diagnosis'] ?? null,
            'doctor_notes' => $data['doctor_notes'] ?? null,
            'recommendations' => $data['recommendations'] ?? null,
            'followup_date' => $data['followup_date'] ?? null,
            'is_chronic' => $data['is_chronic'] ?? false,
            'is_urgent' => $data['is_urgent'] ?? false,
            'severity' => $data['severity'] ?? 'mild',
            'has_prescription' => $data['has_prescription'] ?? (!empty($data['prescriptions'])),
        ]);
        
        return $diagnosis;
    }
    
    /**
     * Update diagnosis
     */
    public function updateDiagnosis(Diagnosis $diagnosis, array $data): Diagnosis
    {
        $diagnosis->update([
            'symptoms' => $data['symptoms'] ?? $diagnosis->symptoms,
            'diagnosis' => $data['diagnosis'] ?? $diagnosis->diagnosis,
            'doctor_notes' => $data['doctor_notes'] ?? $diagnosis->doctor_notes,
            'recommendations' => $data['recommendations'] ?? $diagnosis->recommendations,
            'followup_date' => $data['followup_date'] ?? $diagnosis->followup_date,
            'is_chronic' => $data['is_chronic'] ?? $diagnosis->is_chronic,
            'is_urgent' => $data['is_urgent'] ?? $diagnosis->is_urgent,
            'severity' => $data['severity'] ?? $diagnosis->severity,
        ]);
        
        return $diagnosis;
    }
    
    /**
     * Get diagnoses for a patient
     */
    public function getPatientDiagnoses(int $patientId)
    {
        return Diagnosis::whereHas('visit', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
            ->with(['visit', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Get chronic conditions for a patient
     */
    public function getChronicConditions(int $patientId)
    {
        return Diagnosis::whereHas('visit', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
            ->where('is_chronic', true)
            ->with(['visit', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}