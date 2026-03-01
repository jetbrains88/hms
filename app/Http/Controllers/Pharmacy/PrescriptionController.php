<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\DispenseRequest;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineForm;
use App\Models\User;
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
    
    // Get stats
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
        'unique_patients' => PrescriptionDispensation::whereHas('prescription', function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->distinct('prescription_id')->count('prescription_id'),
    ];

    // Get filter data
    $medicineCategories = MedicineCategory::where('is_active', true)->orderBy('name')->get();
    $medicines = Medicine::where(function($q) use ($branchId) {
            $q->where('branch_id', $branchId)->orWhere('is_global', true);
        })->where('is_active', true)->orderBy('name')->get();
    $medicineForms = MedicineForm::orderBy('name')->get();
    $pharmacists = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['pharmacy', 'pharmacist', 'admin']);
        })->where('is_active', true)->orderBy('name')->get(['id', 'name']);

    return view('pharmacy.prescriptions.history', compact(
        'stats', 
        'medicineCategories', 
        'medicines', 
        'medicineForms', 
        'pharmacists'
    ));
}

// Add this method to handle filtered history data
public function getHistoryData(Request $request)
{
    $branchId = auth()->user()->current_branch_id;
    
    $query = PrescriptionDispensation::with([
        'prescription.diagnosis.visit.patient',
        'prescription.medicine.category',
        'prescription.medicine.form',
        'dispensedBy',
        'medicineBatch'
    ])->whereHas('prescription', function($q) use ($branchId) {
        $q->where('branch_id', $branchId);
    });

    // Search filter
    if ($search = $request->get('search')) {
        $query->where(function($q) use ($search) {
            $q->whereHas('prescription.diagnosis.visit.patient', function($pq) use ($search) {
                $pq->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('emrn', 'LIKE', "%{$search}%");
            })->orWhereHas('prescription.medicine', function($mq) use ($search) {
                $mq->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('generic_name', 'LIKE', "%{$search}%");
            })->orWhereHas('medicineBatch', function($bq) use ($search) {
                $bq->where('batch_number', 'LIKE', "%{$search}%");
            });
        });
    }

    // Date range filters
    if ($request->filled('date_from')) {
        $query->whereDate('dispensed_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('dispensed_at', '<=', $request->date_to);
    }

    // Medicine Category filter
    if ($request->filled('medicine_category_id')) {
        $query->whereHas('prescription.medicine', function($q) use ($request) {
            $q->where('category_id', $request->medicine_category_id);
        });
    }

    // Medicine filter
    if ($request->filled('medicine_id')) {
        $query->whereHas('prescription', function($q) use ($request) {
            $q->where('medicine_id', $request->medicine_id);
        });
    }

    // Medicine Form filter
    if ($request->filled('medicine_form_id')) {
        $query->whereHas('prescription.medicine', function($q) use ($request) {
            $q->where('form_id', $request->medicine_form_id);
        });
    }

    // Batch number filter
    if ($request->filled('batch_number')) {
        $query->whereHas('medicineBatch', function($q) use ($request) {
            $q->where('batch_number', 'LIKE', "%{$request->batch_number}%");
        });
    }

    // Manufacturer filter
    if ($request->filled('manufacturer')) {
        $query->whereHas('prescription.medicine', function($q) use ($request) {
            $q->where('manufacturer', 'LIKE', "%{$request->manufacturer}%");
        });
    }

    // Prescription status filter
    if ($request->filled('prescription_status')) {
        $query->whereHas('prescription', function($q) use ($request) {
            $q->where('status', $request->prescription_status);
        });
    }

    // Dispensed by (pharmacist) filter
    if ($request->filled('dispensed_by')) {
        $query->where('dispensed_by', $request->dispensed_by);
    }

    // Quantity range filter
    if ($request->filled('min_quantity')) {
        $query->where('quantity_dispensed', '>=', $request->min_quantity);
    }
    if ($request->filled('max_quantity')) {
        $query->where('quantity_dispensed', '<=', $request->max_quantity);
    }

    // Expiry status filter
    if ($request->filled('expiry_status')) {
        $today = now();
        switch ($request->expiry_status) {
            case 'expired':
                $query->whereHas('medicineBatch', function($q) use ($today) {
                    $q->where('expiry_date', '<', $today);
                });
                break;
            case 'expiring_soon':
                $query->whereHas('medicineBatch', function($q) use ($today) {
                    $q->where('expiry_date', '>=', $today)
                      ->where('expiry_date', '<=', $today->copy()->addDays(30));
                });
                break;
            case 'valid':
                $query->whereHas('medicineBatch', function($q) use ($today) {
                    $q->where('expiry_date', '>', $today->copy()->addDays(30));
                })->orWhereDoesntHave('medicineBatch');
                break;
        }
    }

    // Stock status filter
    if ($request->filled('stock_status')) {
        switch ($request->stock_status) {
            case 'in_stock':
                $query->whereHas('medicineBatch', function($q) {
                    $q->where('remaining_quantity', '>', 10);
                });
                break;
            case 'low_stock':
                $query->whereHas('medicineBatch', function($q) {
                    $q->where('remaining_quantity', '>', 0)
                      ->where('remaining_quantity', '<=', 10);
                });
                break;
            case 'out_of_stock':
                $query->whereHas('medicineBatch', function($q) {
                    $q->where('remaining_quantity', '<=', 0);
                })->orWhereDoesntHave('medicineBatch');
                break;
        }
    }

    // Price range filter
    if ($request->filled('min_price') || $request->filled('max_price')) {
        $query->whereHas('medicineBatch', function($q) use ($request) {
            if ($request->filled('min_price')) {
                $q->where('sale_price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('sale_price', '<=', $request->max_price);
            }
        });
    }

    // Sorting
    $sortField = $request->get('sort', 'dispensed_at');
    $sortDirection = $request->get('direction', 'desc');

    switch ($sortField) {
        case 'medicine_name':
            $query->join('prescriptions', 'prescription_dispensations.prescription_id', '=', 'prescriptions.id')
                  ->join('medicines', 'prescriptions.medicine_id', '=', 'medicines.id')
                  ->orderBy('medicines.name', $sortDirection)
                  ->select('prescription_dispensations.*');
            break;
        case 'patient_name':
            $query->join('prescriptions', 'prescription_dispensations.prescription_id', '=', 'prescriptions.id')
                  ->join('diagnoses', 'prescriptions.diagnosis_id', '=', 'diagnoses.id')
                  ->join('visits', 'diagnoses.visit_id', '=', 'visits.id')
                  ->join('patients', 'visits.patient_id', '=', 'patients.id')
                  ->orderBy('patients.name', $sortDirection)
                  ->select('prescription_dispensations.*');
            break;
        case 'batch_number':
            $query->leftJoin('medicine_batches', 'prescription_dispensations.medicine_batch_id', '=', 'medicine_batches.id')
                  ->orderBy('medicine_batches.batch_number', $sortDirection)
                  ->select('prescription_dispensations.*');
            break;
        case 'expiry_date':
            $query->leftJoin('medicine_batches', 'prescription_dispensations.medicine_batch_id', '=', 'medicine_batches.id')
                  ->orderBy('medicine_batches.expiry_date', $sortDirection)
                  ->select('prescription_dispensations.*');
            break;
        case 'quantity_dispensed':
            $query->orderBy('quantity_dispensed', $sortDirection);
            break;
        default:
            $query->orderBy($sortField, $sortDirection);
    }

    $perPage = $request->get('per_page', 10);
    $dispensations = $query->paginate($perPage);

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