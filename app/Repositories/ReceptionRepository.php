<?php

namespace App\Repositories;

use App\Models\Office;
use App\Models\Patient;
use App\Models\Visit;
use App\Models\Vital;
use Illuminate\Support\Facades\Log;

class ReceptionRepository
{
    public function findOrCreatePatient(array $data)
    {
        // First, try to find by CNIC
        if (!empty($data['cnic'])) {
            $patient = Patient::where('cnic', $data['cnic'])->first();
            if ($patient) {
                Log::info('Found existing patient by CNIC', [
                    'patient_id' => $patient->id,
                    'cnic' => $data['cnic']
                ]);
                return $this->updatePatient($patient->id, $data);
            }
        }

        // Then try by phone
        if (!empty($data['phone'])) {
            $patient = Patient::where('phone', $data['phone'])->first();
            if ($patient) {
                Log::info('Found existing patient by phone', [
                    'patient_id' => $patient->id,
                    'phone' => $data['phone']
                ]);
                return $this->updatePatient($patient->id, $data);
            }
        }

        // If not found, create new patient
        return $this->createPatient($data);
    }


    public function updatePatient($patientId, array $data)
    {
        $patient = Patient::findOrFail($patientId);

        Log::info('Updating patient information', [
            'patient_id' => $patientId,
            'name' => $data['name'] ?? null
        ]);

        $updateData = [
            'name' => $data['name'] ?? $patient->name,
            'emergency_contact' => $data['emergency_contact'] ?? $patient->emergency_contact,
            'address' => $data['address'] ?? $patient->address,
            'blood_group' => $data['blood_group'] ?? $patient->blood_group,
            'allergies' => $data['allergies'] ?? $patient->allergies,
            'chronic_conditions' => $data['chronic_conditions'] ?? $patient->chronic_conditions,
            'medical_history' => $data['medical_history'] ?? $patient->medical_history,
        ];

        // Only update NHMP fields if patient is NHMP or becoming NHMP
        if ($data['is_nhmp'] ?? false) {
            $updateData['is_nhmp'] = true;
            $updateData['designation_id'] = $data['designation_id'] ?? $patient->designation_id;
            $updateData['office_id'] = $data['office_id'] ?? $patient->office_id;
            $updateData['rank'] = $data['rank'] ?? $patient->rank;
        }

        $patient->update($updateData);

        return $patient;
    }

    public function createPatient(array $data)
    {
        Log::info('Creating new patient', [
            'name' => $data['name'] ?? null,
            'cnic' => $data['cnic'] ?? null,
            'phone' => $data['phone'] ?? null
        ]);

        // Generate EMRN
        $latestEMrn = Patient::orderBy('id', 'desc')->first();
        $eMrnNumber = $latestEMrn ? (int)str_replace('EMRN-', '', $latestEMrn->emrn) + 1 : 1;
        $emrn = 'EMRN-' . str_pad($eMrnNumber, 5, '0', STR_PAD_LEFT);

        $patientData = [
            'name' => $data['name'] ?? null,
            'cnic' => $data['cnic'] ?? null,
            'phone' => $data['phone'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'blood_group' => $data['blood_group'] ?? null,
            'allergies' => $data['allergies'] ?? null,
            'chronic_conditions' => $data['chronic_conditions'] ?? null,
            'medical_history' => $data['medical_history'] ?? null,
            'is_nhmp' => $data['is_nhmp'] ?? false,
            'designation_id' => $data['is_nhmp'] ? ($data['designation_id'] ?? null) : null,
            'office_id' => $data['is_nhmp'] ? ($data['office_id'] ?? null) : null,
            'rank' => $data['rank'] ?? null,
            'is_active' => true,
            'emrn' => $emrn,
        ];

        return Patient::create($patientData);
    }

    public function findPatient($patientId): Patient
    {
        return Patient::find($patientId);
    }

    public function getHierarchicalOffice($officeId): Office
    {
        return Office::find($officeId);
    }

    public function findExistingPatient($cnic = null, $phone = null): ?Patient
    {
        Log::info('findExistingPatient: CNIC: ' . $cnic . ' :: Phone: ' . $phone);
        $query = Patient::query();


        if ($cnic) {
            $query->orWhere('cnic', $cnic);
        }

        if ($phone) {
            $query->orWhere('phone', $phone);
        }

        return $query->first();
    }

    public function createVisit($patientId, $queueToken, array $additionalData = [])
    {
        $visitData = array_merge([
            'patient_id' => $patientId,
            'queue_token' => $queueToken,
            'status' => 'waiting',
            'visit_type' => 'routine',
            'created_at' => now(),
            'updated_at' => now(),
        ], $additionalData);

        return Visit::create($visitData);
    }

    public function updateVisitStatus($visitId, array $data): Visit|array
    {
        $visit = Visit::findOrFail($visitId);
        $visit->update($data);

        // Update vitals if provided
        if (isset($data['temperature']) || isset($data['notes'])) {
            $vitals = $visit->latestVital;
            if ($vitals) {
                $vitals->update($data);
            } else {
                $this->createVitals($visitId, $data);
            }
        }

        return $visit;
    }

    public function createVitals($visitId, array $data)
    {
        $vitalsData = array_merge(['visit_id' => $visitId], $data);
        return Vital::create($vitalsData);
    }

    public function getVisitWithVitals($visitId): Visit|array|null
    {
        return Visit::with(['patient', 'latestVital'])->orderBy('created_at', 'desc')->find($visitId);
    }

    public function getDashboardStatistics(): array
    {
        $totalPatients = Patient::count();
        $todayPatients = Visit::whereDate('created_at', today())->count();
        $waitingPatients = Visit::where('status', 'waiting')->count();
        $waitingPatientsList = Visit::with('patient', 'latestVital')
            ->where('status', 'waiting')
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'totalPatients' => $totalPatients,
            'todayPatients' => $todayPatients,
            'waitingPatients' => $waitingPatients,
            'waitingPatientsList' => $waitingPatientsList,
        ];
    }

    public function searchPatients($searchTerm)
    {
        return Patient::where('name', 'like', "%{$searchTerm}%")
            ->orWhere('emrn', 'like', "%{$searchTerm}%")
            ->orWhere('phone', 'like', "%{$searchTerm}%")
            ->orWhere('cnic', 'like', "%{$searchTerm}%")
            ->select('id', 'name', 'emrn', 'phone', 'dob', 'gender', 'blood_group', 'allergies', 'chronic_conditions', 'medical_history', 'address', 'cnic', 'is_nhmp', 'designation_id', 'office_id')
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function findPatientByPhone($phone): ?Patient
    {
        return Patient::where('phone', $phone)->first();
    }

    public function getLatestQueueToken()
    {
        $latestVisit = Visit::orderBy('id', 'desc')->first();
        return $latestVisit ? $latestVisit->queue_token : null;
    }
}
