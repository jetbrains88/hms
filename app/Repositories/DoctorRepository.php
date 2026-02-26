<?php

namespace App\Repositories;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class DoctorRepository
{
    public function getDoctorStats(int $doctorId): array
    {
        Log::info(
            [
                'doctorId: ' => $doctorId,
            ]
        );
        $date = Carbon::today();

        return [
            'total_patients_today' => Visit::where('doctor_id', $doctorId)
                ->whereDate('created_at', $date)
                ->distinct('patient_id')
                ->count('patient_id'),

            'waiting_patients' => Visit::where('doctor_id', $doctorId)
                ->where('status', 'waiting')
                ->whereDate('created_at', $date)
                ->count(),

            'in_progress_patients' => Visit::where('doctor_id', $doctorId)
                ->where('status', 'in_progress')
                ->whereDate('created_at', $date)
                ->count(),

            'completed_today' => Visit::where('doctor_id', $doctorId)
                ->where('status', 'completed')
                ->whereDate('created_at', $date)
                ->count(),

            'average_consultation_time' => $this->getAverageConsultationTime($doctorId, $date),

            'prescriptions_today' => Prescription::whereHas('diagnosis', function ($query) use ($doctorId, $date) {
                $query->where('doctor_id', $doctorId)
                    ->whereDate('created_at', $date);
            })->count(),
        ];
    }

    private function getAverageConsultationTime(int $doctorId, Carbon $date): int
    {
        $avgTime = Visit::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->whereDate('created_at', $date)
            ->whereNotNull('updated_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_time')
            ->first()
            ->avg_time;

        return (int)($avgTime ?? 0);
    }

    // Ensure you have this import at the top of your file


    public function getDoctorVisits($doctorId, $filters = [], $perPage = 10)
    {
        // 1. SELECT ONLY NECESSARY COLUMNS
        // Avoid 'select *' to reduce memory usage. Only fetch what the Blade view actually needs.
        $query = Visit::query()
            ->select('id', 'doctor_id', 'patient_id', 'visit_type', 'status', 'created_at', 'updated_at')

            // 2. REMOVE UNUSED EAGER LOADS & OPTIMIZE RELATIONSHIPS
            // Removed 'diagnoses' and 'prescriptions' as they are NOT used in the 'consultancy.blade.php' list view.
            // This significantly reduces the data payload and eliminates unnecessary queries.
            ->with(['patient' => function ($q) {
                // Select only patient fields used in the UI (name, emrn, gender, dob for age, etc.)
                $q->select('id', 'name', 'emrn', 'blood_group', 'phone', 'gender', 'dob', 'is_nhmp');
            }])->where('doctor_id', $doctorId)->whereNot('status', 'cancelled');

        // 3. STATUS FILTER
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 4. COMBINE PATIENT FILTERS (Search & NHMP)
        // Combining these into a single whereHas prevents generating multiple subqueries.
        $hasSearch = !empty($filters['search']);
        $hasNhmp = isset($filters['is_nhmp']) && $filters['is_nhmp'] !== '';

        if ($hasSearch || $hasNhmp) {
            $query->whereHas('patient', function ($q) use ($filters, $hasSearch, $hasNhmp) {
                // Apply NHMP filter strictly if present
                if ($hasNhmp) {
                    $q->where('is_nhmp', $filters['is_nhmp']);
                }

                // Apply Search filter with correct grouping
                if ($hasSearch) {
                    $searchTerm = $filters['search'];
                    $q->where(function ($subQ) use ($searchTerm) {
                        $subQ->where('name', 'like', $searchTerm . '%') // Prefer prefix search for index usage if possible
                            ->orWhere('emrn', 'like', $searchTerm . '%')
                            ->orWhere('phone', 'like', $searchTerm . '%');
                    });
                }
            });
        }

        // 5. INDEX-FRIENDLY DATE FILTERING
        // explicitly use whereBetween instead of whereDate to allow SQL to use the 'created_at' index.
        if (!empty($filters['date'])) {
            try {
                $start = Carbon::parse($filters['date'])->startOfDay();
                // If end_date is provided, use it; otherwise, default to the end of the start date
                $end = !empty($filters['end_date'])
                    ? Carbon::parse($filters['end_date'])->endOfDay()
                    : $start->copy()->endOfDay();

                $query->whereBetween('created_at', [$start, $end]);
            } catch (\Exception $e) {
                Log::error("Invalid date format in filters: " . $e->getMessage());
            }
        } elseif (!empty($filters['end_date'])) {
            // Fallback if only end_date is provided
            $query->where('created_at', '<=', Carbon::parse($filters['end_date'])->endOfDay());
        }

        // 6. ORDERING
        return $query->latest()->paginate($perPage);
    }

    public function getVisitDetails(int $visitId): ?Visit
    {
        return Visit::with([
            'patient',
            'latestVital',
            'diagnoses' => function ($query) {
                $query->with(['prescriptions.medicine']);
            },
            'patient.visits' => function ($query) {
                $query->with('diagnoses')->latest()->limit(5);
            },
            'patient.labReports' => function ($query) {
                $query->latest()->limit(5);
            }
        ])->find($visitId);
    }

    public function getDoctorAppointments(int $doctorId): array
    {
        // For future implementation - would integrate with appointment system
        return [];
    }

    public function searchPatients(string $searchTerm): \Illuminate\Database\Eloquent\Collection
    {
        return Patient::with(['latestVitals', 'lastVisit'])
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('emrn', 'like', "%{$searchTerm}%")
                    ->orWhere('cnic', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%");
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(20)
            ->get();
    }

    public function getOfficePatients(int $officeId): \Illuminate\Database\Eloquent\Collection
    {
        return Patient::with(['latestVitals', 'designation'])
            ->where('office_id', $officeId)
            ->where('is_active', true)
            ->where('is_nhmp', true)
            ->orderBy('name')
            ->get();
    }
}
