<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\StoreLabOrderRequest;
use App\Http\Requests\Lab\VerifyOrderRequest;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Visit;
use App\Services\LabService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $labService;

    public function __construct(LabService $labService)
    {
        $this->labService = $labService;
    }

    /**
     * Display lab orders
     */
    public function index(Request $request)
    {
        $branchId = auth()->user()->current_branch_id;
        
        $query = LabOrder::with(['patient', 'doctor', 'items.labTestType'])
            ->where('branch_id', $branchId);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('lab_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('patient', function ($patientQuery) use ($search) {
                      $patientQuery->where('name', 'LIKE', "%{$search}%")
                                 ->orWhere('emrn', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        $stats = $this->labService->getStats($branchId);
        
        return view('lab.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show create order form
     */
    public function create(Request $request)
    {
        $patientId = $request->get('patient_id');
        $visitId = $request->get('visit_id');
        
        $patient = null;
        $visit = null;
        
        if ($patientId) {
            $patient = Patient::findOrFail($patientId);
        }
        
        if ($visitId) {
            $visit = Visit::with('patient')->findOrFail($visitId);
            $patient = $visit->patient;
        }
        
        $testTypes = \App\Models\LabTestType::with('parameters')
            ->orderBy('name')
            ->get();
        
        return view('lab.orders.create', compact('patient', 'visit', 'testTypes'));
    }

    /**
     * Store new lab order
     */
    public function store(StoreLabOrderRequest $request)
    {
        $labOrder = $this->labService->createLabOrder(
            $request->validated(),
            auth()->id(),
            auth()->user()->current_branch_id
        );
        
        return redirect()
            ->route('lab.orders.show', $labOrder)
            ->with('success', 'Lab order created successfully. Order #: ' . $labOrder->lab_number);
    }

    /**
     * Show lab order details
     */
    public function show(LabOrder $labOrder)
    {
        $labOrder->load([
            'patient',
            'doctor',
            'items' => function ($q) {
                $q->with(['labTestType.parameters', 'labResults', 'technician']);
            }
        ]);
        
        return view('lab.orders.show', compact('labOrder'));
    }

    /**
     * Start processing an order item
     */
    public function startItem(LabOrderItem $item)
    {
        $item->update([
            'status' => 'processing',
            'technician_id' => auth()->id(),
        ]);
        
        return redirect()
            ->back()
            ->with('success', 'Started processing test: ' . $item->labTestType->name);
    }

    /**
     * Verify lab order
     */
    public function verify(VerifyOrderRequest $request, LabOrder $labOrder)
    {
        try {
            $labOrder = $this->labService->verifyOrder($labOrder, auth()->id());
            
            return redirect()
                ->route('lab.orders.show', $labOrder)
                ->with('success', 'Lab order verified successfully');
                
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Print lab report
     */
    public function print(LabOrder $labOrder)
    {
        $labOrder->load([
            'patient',
            'doctor',
            'verifiedBy',
            'items' => function ($q) {
                $q->with(['labTestType', 'labResults.labTestParameter']);
            }
        ]);
        
        return view('lab.orders.print', compact('labOrder'));
    }
}