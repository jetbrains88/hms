<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\DispenseRequest;
use App\Models\Prescription;
use App\Models\PrescriptionDispensation;
use App\Services\PrescriptionService;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    protected $prescriptionService;

    public function __construct(PrescriptionService $prescriptionService)
    {
        $this->prescriptionService = $prescriptionService;
    }

    /**
     * Display prescriptions
     */
    public function index(Request $request)
    {
        $branchId = auth()->user()->current_branch_id;
        
        $query = Prescription::with(['diagnosis.visit.patient', 'medicine.batches', 'prescribedBy'])
            ->where('branch_id', $branchId);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'partially_dispensed']);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('diagnosis.visit.patient', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('emrn', 'LIKE', "%{$search}%");
            });
        }
        
        $prescriptions = $query->orderBy('created_at', 'asc')->paginate(15);
        
        $stats = $this->prescriptionService->getStats($branchId);
        
        return view('pharmacy.prescriptions.index', compact('prescriptions', 'stats'));
    }

    /**
     * Show prescription details
     */
    public function show(Prescription $prescription)
    {
        $prescription->load([
            'diagnosis.visit.patient',
            'medicine',
            'prescribedBy',
            'dispensations' => function ($q) {
                $q->with(['dispensedBy', 'medicineBatch'])->latest();
            }
        ]);
        
        return view('pharmacy.prescriptions.show', compact('prescription'));
    }

    /**
     * Dispense prescription
     */
    public function dispense(DispenseRequest $request, Prescription $prescription)
    {
        try {
            $dispensation = $this->prescriptionService->dispensePrescription(
                $prescription,
                auth()->id(),
                $request->validated()
            );
            
            return redirect()
                ->route('pharmacy.prescriptions.show', $prescription)
                ->with('success', "Dispensed {$dispensation->quantity_dispensed} units successfully");
                
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display dispense history
     */
    public function history(Request $request)
    {
        $branchId = auth()->user()->current_branch_id;
        
        $stats = [
            'total_dispensed' => PrescriptionDispensation::whereHas('prescription', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->count(),
            'today_dispensed' => PrescriptionDispensation::whereHas('prescription', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->whereDate('dispensed_at', today())->count(),
            'total_quantity' => PrescriptionDispensation::whereHas('prescription', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })->sum('quantity_dispensed'),
        ];

        return view('pharmacy.prescriptions.history', compact('stats'));
    }

    /**
     * Get history data for AJAX
     */
    public function getHistoryData(Request $request)
    {
        $branchId = auth()->user()->current_branch_id;
        
        $query = PrescriptionDispensation::with([
            'prescription.diagnosis.visit.patient',
            'prescription.medicine',
            'dispensedBy',
            'medicineBatch'
        ])->whereHas('prescription', function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        });

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('prescription.diagnosis.visit.patient', function($pq) use ($search) {
                    $pq->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('emrn', 'LIKE', "%{$search}%");
                })->orWhereHas('prescription.medicine', function($mq) use ($search) {
                    $mq->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('medicineBatch', function($bq) use ($search) {
                    $bq->where('batch_number', 'LIKE', "%{$search}%");
                });
            });
        }

        // Filters
        if ($request->filled('date_from')) {
            $query->whereDate('dispensed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('dispensed_at', '<=', $request->date_to);
        }
        if ($request->filled('dispenser_id')) {
            $query->where('dispensed_by', $request->dispenser_id);
        }

        $dispensations = $query->latest('dispensed_at')->paginate($request->get('per_page', 10));

        return response()->json($dispensations);
    }

    /**
     * Print prescription label
     */
    public function printLabel(Prescription $prescription)
    {
        // This would generate a printable label
        return view('pharmacy.prescriptions.label', compact('prescription'));
    }
}