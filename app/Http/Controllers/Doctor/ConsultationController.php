<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\LabTestType;
use App\Models\Visit;
use App\Services\VisitService;
use App\Services\VitalService;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    protected $visitService;
    protected $vitalService;

    public function __construct(VisitService $visitService, VitalService $vitalService)
    {
        $this->visitService = $visitService;
        $this->vitalService = $vitalService;
    }

    /**
     * Display doctor's dashboard with queue
     */
    public function index(Request $request)
    {
        $totalWaiting = $this->visitService->getWaitingCountForDoctor(auth()->id());
        
        $myQueue = Visit::with(['patient', 'latestVital'])
            ->where('doctor_id', auth()->id())
            ->whereIn('status', ['waiting', 'in_progress'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        $recentCompleted = Visit::with(['patient', 'diagnoses'])
            ->where('doctor_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->limit(10)
            ->get();
        
        $filters = $request->only(['status', 'search', 'is_nhmp', 'date']);
        
        return view('doctor.consultations.index', compact('totalWaiting', 'myQueue', 'recentCompleted', 'filters'));
    }

    /**
     * Get consultations data for AJAX
     */
    public function data(Request $request)
    {
        $query = Visit::with(['patient', 'latestVital'])
            ->where('doctor_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('emrn', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['waiting', 'in_progress', 'completed']);
        }

        if ($request->filled('is_nhmp')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('is_nhmp', $request->is_nhmp === '1');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $perPage = $request->input('per_page', 10);
        $consultations = $query->latest()->paginate($perPage);

        return response()->json($consultations);
    }

    /**
     * Get statistics for doctor
     */
    public function stats()
    {
        $doctorId = auth()->id();
        
        $stats = [
            'total' => Visit::where('doctor_id', $doctorId)->count(),
            'waiting' => Visit::where('doctor_id', $doctorId)->where('status', 'waiting')->count(),
            'in_progress' => Visit::where('doctor_id', $doctorId)->where('status', 'in_progress')->count(),
            'completed' => Visit::where('doctor_id', $doctorId)->where('status', 'completed')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Start a consultation
     */
    public function start(Visit $visit)
    {
        if ($visit->doctor_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($visit->status !== 'waiting') {
            return response()->json(['success' => false, 'message' => 'Visit is not in waiting status'], 400);
        }

        $this->visitService->updateStatus($visit, 'in_progress');

        return response()->json(['success' => true]);
    }

    /**
     * Cancel a consultation
     */
    public function cancel(Request $request, Visit $visit)
    {
        if ($visit->doctor_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->visitService->updateStatus($visit, 'cancelled');

        if ($request->filled('reason')) {
            $visit->update(['notes' => $visit->notes . "\nCancellation Reason: " . $request->reason]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Show consultation form for a visit
     */
    public function show(Visit $visit)
    {
        // Use Policy or manual check
        if ($visit->doctor_id !== auth()->id()) {
            abort(403);
        }
        
        $visit->load([
            'patient',
            'latestVital',
            'diagnoses' => function ($query) {
                $query->with('prescriptions')->latest();
            }
        ]);
        
        // Mark visit as in_progress if it's waiting
        if ($visit->status === 'waiting') {
            $this->visitService->updateStatus($visit, 'in_progress');
        }

        $medicines = Medicine::active()->get();
        $labTestTypes = LabTestType::orderBy('name')->get();
        
        return view('doctor.consultations.show', compact('visit', 'medicines', 'labTestTypes'));
    }

    /**
     * Complete consultation
     */
    public function complete(Visit $visit)
    {
        if ($visit->doctor_id !== auth()->id()) {
            abort(403);
        }
        
        $this->visitService->updateStatus($visit, 'completed');
        
        return redirect()
            ->route('doctor.consultancy')
            ->with('success', 'Consultation completed successfully');
    }

    /**
     * Get patient medical history as JSON
     */
    public function patientHistoryJson(int $patientId)
    {
        $patient = \App\Models\Patient::findOrFail($patientId);
        
        // Fetch some basic stats
        $totalVisits = $patient->visits()->count();
        $totalPrescriptions = \App\Models\Prescription::whereHas('diagnosis', function($q) use ($patientId) {
            $q->whereHas('visit', function($v) use ($patientId) {
                $v->where('patient_id', $patientId);
            });
        })->count();

        return response()->json([
            'success' => true,
            'data' => [
                'patient' => [
                    'chronic_conditions' => $patient->chronic_conditions,
                    'allergies' => $patient->allergies,
                ],
                'statistics' => [
                    'total_visits' => $totalVisits,
                    'total_prescriptions' => $totalPrescriptions,
                ]
            ]
        ]);
    }

    /**
     * Record vitals for a patient from consultation view
     */
    public function recordVitals(Request $request)
    {
        $validated = $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'patient_id' => 'required|exists:patients,id',
            'temperature' => 'nullable|numeric',
            'pulse' => 'nullable|integer',
            'blood_pressure_systolic' => 'nullable|integer',
            'blood_pressure_diastolic' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'oxygen_saturation' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $vital = \App\Models\Vital::create($validated + [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
        ]);

        return response()->json([
            'success' => true,
            'vital' => $vital
        ]);
    }

    /**
     * Start teleconsultation
     */
    public function eConsultancy()
    {
        // This will be implemented with video conferencing integration
        return view('doctor.consultations.e-consultancy');
    }
}