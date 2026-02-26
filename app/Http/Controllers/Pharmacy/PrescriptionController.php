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
        
        $query = Prescription::with(['patient', 'medicine', 'prescribedBy'])
            ->where('branch_id', $branchId);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'partially_dispensed']);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
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
            'patient',
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

        $query = Prescription::with(['patient', 'medicine', 'prescribedBy'])
            ->where('branch_id', $branchId)
            ->where('status', 'completed');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('emrn', 'LIKE', "%{$search}%");
            });
        }

        $prescriptions = $query->latest('updated_at')->paginate(15);

        return view('pharmacy.prescriptions.index', [
            'prescriptions' => $prescriptions,
            'title' => 'Dispense History',
            'isHistory' => true
        ]);
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