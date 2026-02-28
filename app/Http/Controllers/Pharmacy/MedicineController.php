<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\StoreMedicineRequest;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    /**
     * Display medicine catalog
     */
    public function index(Request $request)
    {
        $query = Medicine::with(['category', 'form'])
            ->where(function ($q) {
                // Show branch-specific and global medicines
                $q->where('branch_id', auth()->user()->current_branch_id)
                  ->orWhere('is_global', true);
            });
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('generic_name', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('prescription')) {
            $query->where('requires_prescription', $request->prescription === 'required');
        }
        
        $medicines = $query->paginate(15);
        $categories = MedicineCategory::where('is_active', true)->get();
        
        return view('pharmacy.medicines.index', compact('medicines', 'categories'));
    }

    /**
     * Show medicine creation form
     */
    public function create()
    {
        $categories = MedicineCategory::where('is_active', true)->get();
        $forms = MedicineForm::all();
        
        return view('pharmacy.medicines.create', compact('categories', 'forms'));
    }

    /**
     * Store new medicine
     */
    public function store(StoreMedicineRequest $request)
    {
        $data = $request->validated();
        $data['uuid'] = (string) Str::uuid();
        
        // If not global, assign to current branch
        if (!($data['is_global'] ?? false)) {
            $data['branch_id'] = auth()->user()->current_branch_id;
        }
        
        $medicine = Medicine::create($data);
        
        return redirect()
            ->route('pharmacy.medicines.show', $medicine)
            ->with('success', 'Medicine added successfully');
    }

    /**
     * Show medicine details
     */
    public function show(Medicine $medicine)
    {
        $medicine->load(['category', 'form', 'batches' => function ($q) {
            $q->where('branch_id', auth()->user()->current_branch_id)
              ->orderBy('expiry_date');
        }]);
        
        $totalStock = $medicine->batches->sum('remaining_quantity');
        
        return view('pharmacy.medicines.show', compact('medicine', 'totalStock'));
    }

    /**
     * Show medicine edit form
     */
    public function edit(Medicine $medicine)
    {
        $categories = MedicineCategory::where('is_active', true)->get();
        $forms = MedicineForm::all();
        
        return view('pharmacy.medicines.edit', compact('medicine', 'categories', 'forms'));
    }

    /**
     * Update medicine
     */
    public function update(StoreMedicineRequest $request, Medicine $medicine)
    {
        $medicine->update($request->validated());
        
        return redirect()
            ->route('pharmacy.medicines.show', $medicine)
            ->with('success', 'Medicine updated successfully');
    }

    /**
     * AJAX: Search medicines for typeahead/dropdowns
     */
    public function apiSearch(Request $request)
    {
        $search = $request->get('q') ?: $request->get('search');
        
        $query = Medicine::with(['category', 'form'])
            ->where(function ($q) {
                $q->where('branch_id', auth()->user()->current_branch_id)
                  ->orWhere('is_global', true);
            })
            ->where('is_active', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('generic_name', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        $medicines = $query->limit(20)->get();

        return response()->json($medicines);
    }
}