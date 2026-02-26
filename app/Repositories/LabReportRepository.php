<?php

namespace App\Repositories;

use App\Interfaces\LabReportRepositoryInterface;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\User;
use App\Models\LabSampleInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class LabReportRepository implements LabReportRepositoryInterface
{
    public function getAll(array $filters = [], $perPage = 10)
    {
        $query = LabOrder::with([
            'patient',
            'doctor',
            'testType',  // This is the correct relationship name (not testType)
            'results',
            'sampleInfo'
        ]);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['patient_id'])) {
            $query->where('patient_id', $filters['patient_id']);
        }

        if (!empty($filters['doctor_id'])) {
            $query->where('doctor_id', $filters['doctor_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('lab_number', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('patient', function ($patientQuery) use ($filters) {
                        $patientQuery->where('name', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('cnic', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('emrn', 'like', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('doctor', function ($doctorQuery) use ($filters) {
                        $doctorQuery->where('name', 'like', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('labTestType', function ($testQuery) use ($filters) {
                        $testQuery->where('name', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Apply sorting
        if (!empty($filters['sort_by']) && !empty($filters['sort_direction'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_direction']);
        } else {
            $query->latest(); // Default sorting
        }

        return $query->paginate($perPage);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(): array
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();

        return [
            'total' => LabOrder::count(),
            'pending' => LabOrder::where('status', 'pending')->count(),
            'processing' => LabOrder::where('status', 'processing')->count(),
            'completed' => LabOrder::where('status', 'completed')->count(),
            'cancelled' => LabOrder::where('status', 'cancelled')->count(),
            'urgent' => LabOrder::whereIn('priority', ['urgent', 'emergency'])
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'today' => LabOrder::whereDate('created_at', $today)->count(),
            'this_week' => LabOrder::where('created_at', '>=', $thisWeek)->count(),
            'overdue' => LabOrder::whereNotIn('status', ['completed', 'cancelled'])
                ->where('created_at', '<', now()->subHours(24))
                ->count(),
            'completed_today' => LabOrder::where('status', 'completed')
                ->whereDate('reporting_date', $today)
                ->count(),
        ];
    }

    /**
     * Get lab orders for a specific patient
     */
    public function getByPatient(int $patientId, array $filters = [], $perPage = 10)
    {
        $query = LabOrder::with(['doctor', 'labTestType', 'results'])
            ->where('patient_id', $patientId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get lab orders by date range with optional filters
     */
    public function getByDateRange(string $startDate, string $endDate, array $filters = [])
    {
        $query = LabOrder::with(['patient', 'doctor', 'labTestType'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        return $query->latest()->get();
    }

    public function findWithRelations(int $id): LabOrder
    {
        return LabOrder::with([
            'patient',
            'doctor',
            'technician',
            'testType',
            'visit.patient',
            'results.parameter',
            'testType.parameters' => function ($query) {
                $query->orderBy('order');
            },
            'items.sampleInfo', // Add this - load sample info through items
            'verifiedBy'
        ])->findOrFail($id);
    }

    public function create(array $data): LabOrder
    {
        return DB::transaction(function () use ($data) {
            // Extract sample info before creating LabOrder
            $sampleInfo = [];
            $sampleFields = [
                'sample_collected_at',
                'sample_id',
                'sample_container',
                'sample_quantity',
                'sample_quantity_unit',
                'sample_condition',
                'special_instructions'
            ];

            foreach ($sampleFields as $field) {
                if (isset($data[$field])) {
                    $sampleInfo[$field] = $data[$field];
                    unset($data[$field]);
                }
            }

            // Generate lab number if not provided
            if (empty($data['lab_number'])) {
                $data['lab_number'] = $this->generateUniqueLabNumber();
            }

            // Set default technician if not provided
            if (empty($data['technician_id'])) {
                $data['technician_id'] = auth()->id();
            }

            // Set default priority if not provided
            if (empty($data['priority'])) {
                $data['priority'] = 'normal';
            }

            // Create lab order
            $labOrder = LabOrder::create($data);

            // Create sample info if any data exists
            if (!empty($sampleInfo)) {
                $sampleInfo['lab_order_id'] = $labOrder->id;

                // Generate sample ID if not provided
                if (empty($sampleInfo['sample_id'])) {
                    $sampleInfo['sample_id'] = $this->generateSampleId();
                }

                // Set sample collection time if not provided
                if (empty($sampleInfo['sample_collected_at'])) {
                    $sampleInfo['sample_collected_at'] = now();
                }

                LabSampleInfo::create($sampleInfo);
            }

            return $labOrder->load('sampleInfo');
        });
    }

    public function update(int $id, array $data): LabOrder
    {
        return DB::transaction(function () use ($id, $data) {
            $labOrder = $this->find($id);

            // Remove virtual fields that don't exist in database
            $virtualFields = ['test_name', 'test_type', 'sample_type'];
            foreach ($virtualFields as $field) {
                if (isset($data[$field])) {
                    unset($data[$field]);
                }
            }

            // Extract sample info
            $sampleInfo = [];
            $sampleFields = [
                'sample_collected_at',
                'sample_id',
                'sample_container',
                'sample_quantity',
                'sample_quantity_unit',
                'sample_condition',
                'special_instructions'
            ];

            foreach ($sampleFields as $field) {
                if (array_key_exists($field, $data)) {
                    $sampleInfo[$field] = $data[$field];
                    unset($data[$field]);
                }
            }

            // Check lab_number uniqueness if being updated
            if (isset($data['lab_number']) && $data['lab_number'] !== $labOrder->lab_number) {
                $existing = LabOrder::where('lab_number', $data['lab_number'])
                    ->where('id', '!=', $id)
                    ->first();

                if ($existing) {
                    $data['lab_number'] = $this->generateUniqueLabNumber();
                }
            }

            // Update lab order
            $labOrder->update($data);

            // Update or create sample info
            if (!empty($sampleInfo)) {
                $labOrder->sampleInfo()->updateOrCreate(
                    ['lab_order_id' => $labOrder->id],
                    $sampleInfo
                );
            }

            return $labOrder->fresh(['sampleInfo', 'patient', 'doctor', 'technician', 'testType']);
        });
    }

    public function find(int $id): LabOrder
    {
        return LabOrder::findOrFail($id);
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $labOrder = $this->find($id);

            // Delete related sample info first
            $labOrder->sampleInfo()->delete();

            return $labOrder->delete();
        });
    }

    public function getEligiblePatients()
    {
        return Patient::where('is_active', true)
            ->select('id', 'name', 'cnic', 'emrn', 'dob', 'gender')
            ->orderBy('name')
            ->get();
    }

    public function getAllDoctors()
    {
        return User::whereHas('roles', function ($q) {
            $q->where('name', 'doctor');
        })
            ->where('is_active', true)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
    }

    public function getAllTechnicians()
    {
        return User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['lab', 'lab_technician']);
        })
            ->where('is_active', true)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
    }

    public function getPendingReports()
    {
        return LabOrder::with(['patient', 'doctor', 'testType', 'sampleInfo'])
            ->whereIn('status', ['pending', 'processing'])
            ->orderBy('priority', 'desc') // Emergency first, then urgent, then normal
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get();
    }



    public function getReportsByDateRange(string $startDate, string $endDate)
    {
        return LabOrder::whereBetween('created_at', [$startDate, $endDate])
            ->with(['patient', 'doctor', 'testType'])
            ->latest()
            ->get();
    }

    public function getUrgentReports()
    {
        return LabOrder::whereIn('priority', ['urgent', 'emergency'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['patient', 'doctor', 'testType'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get();
    }

    public function getOverdueReports()
    {
        return LabOrder::whereNotIn('status', ['completed', 'cancelled'])
            ->where('created_at', '<', now()->subHours(24))
            ->with(['patient', 'doctor', 'testType'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getPendingVerificationReports()
    {
        return LabOrder::where('status', 'completed')
            ->where('is_verified', false)
            ->with(['patient', 'doctor', 'technician', 'testType'])
            ->orderBy('reporting_date', 'desc')
            ->limit(30)
            ->get();
    }

    protected function generateUniqueLabNumber(): string
    {
        do {
            $date = now()->format('Ymd');
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $labNumber = 'LAB-' . $date . '-' . $random;
        } while (LabOrder::where('lab_number', $labNumber)->exists());

        return $labNumber;
    }

    protected function generateSampleId(): string
    {
        $date = now();
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');
        $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

        return "SMP-{$year}{$month}{$day}-{$random}";
    }
}
