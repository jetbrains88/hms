<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\AddStockRequest;
use App\Http\Requests\Pharmacy\TransferStockRequest;
use App\Http\Requests\Pharmacy\AdjustStockRequest;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\MedicineCategory;
use App\Models\Branch;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display inventory
     */
    public function index(Request $request)
    {
        $categories = MedicineCategory::orderBy('name')->get();
        
        $initialFilters = json_encode([
            'category' => $request->get('category', 'All'),
            'stock_status' => $request->get('stock_status', 'All'),
            'sort_by' => $request->get('sort_by', 'expiry_date'),
            'sort_direction' => $request->get('sort_direction', 'asc'),
            'length' => (int) $request->get('length', 16),
            'search' => $request->get('search', ''),
            'page' => (int) $request->get('page', 1)
        ]);
        
        return view('pharmacy.inventory.index', compact('categories', 'initialFilters'));
    }

    /**
     * Show add stock form
     */
    public function create()
    {
        $medicines = Medicine::where(function ($q) {
            $q->where('branch_id', auth()->user()->current_branch_id)
              ->orWhere('is_global', true);
        })->get();
        
        return view('pharmacy.inventory.create', compact('medicines'));
    }

    /**
     * Add stock to inventory
     */
    public function store(AddStockRequest $request)
    {
        $medicine = Medicine::findOrFail($request->medicine_id);
        
        // Find or create batch
        $batch = MedicineBatch::firstOrCreate(
            [
                'branch_id' => auth()->user()->current_branch_id,
                'medicine_id' => $medicine->id,
                'batch_number' => $request->batch_number,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'expiry_date' => $request->expiry_date,
                'unit_price' => $request->unit_price,
                'sale_price' => $request->sale_price,
                'remaining_quantity' => 0,
                'is_active' => true,
            ]
        );
        
        // Add stock
        $log = $this->inventoryService->addStock(
            $batch,
            $request->quantity,
            auth()->id(),
            $request->notes,
            $request->rc_number
        );
        
        return redirect()
            ->route('pharmacy.inventory')
            ->with('success', "Added {$request->quantity} units to batch {$batch->batch_number}");
    }

    /**
     * Show batch details
     */
    public function showBatch(MedicineBatch $batch)
    {
        $batch->load(['medicine', 'inventoryLogs.user' => function ($q) {
            $q->latest();
        }]);
        
        return view('pharmacy.inventory.batch', compact('batch'));
    }

    /**
     * Transfer stock form
     */
    public function transferForm(MedicineBatch $batch)
    {
        $branches = Branch::where('id', '!=', $batch->branch_id)
            ->where('is_active', true)
            ->get();
        
        return view('pharmacy.inventory.transfer', compact('batch', 'branches'));
    }

    /**
     * Transfer stock
     */
    public function transfer(TransferStockRequest $request)
    {
        $batch = MedicineBatch::findOrFail($request->batch_id);
        
        $result = $this->inventoryService->transferStock(
            $batch,
            $request->quantity,
            $request->target_branch_id,
            auth()->id(),
            $request->notes
        );
        
        return redirect()
            ->route('pharmacy.inventory')
            ->with('success', "Transferred {$request->quantity} units to branch {$result['target_batch']->branch_id}");
    }

    /**
     * Adjust stock form
     */
    public function adjustForm(MedicineBatch $batch)
    {
        return view('pharmacy.inventory.adjust', compact('batch'));
    }

    /**
     * Adjust stock
     */
    public function adjust(AdjustStockRequest $request, MedicineBatch $batch)
    {
        $log = $this->inventoryService->adjustStock(
            $batch,
            $request->new_quantity,
            auth()->id(),
            $request->reason
        );
        
        return redirect()
            ->route('pharmacy.inventory.batch', $batch)
            ->with('success', "Stock adjusted to {$request->new_quantity} units");
    }

    /**
     * Get inventory list for AJAX
     */
    public function inventoryList(Request $request)
    {
        $branchId = auth()->user()->current_branch_id;
        $query = Medicine::where('branch_id', $branchId)
            ->orWhere('is_global', true)
            ->with(['category']);
            
        // Get total stock and reorder logic
        // This is a bit simplified for now, usually you'd join with medicine_batches
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category_id', $request->category);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        if ($sortBy === 'name') {
            $query->orderBy('name', $request->get('sort_direction', 'asc'));
        }
        
        $length = $request->get('length', 16);
        $medicines = ($length === 'All') ? $query->get() : $query->paginate($length);
        
        // Map data for Alpine.js
        $data = collect($medicines instanceof \Illuminate\Pagination\LengthAwarePaginator ? $medicines->items() : $medicines)->map(function($medicine) {
            $stock = MedicineBatch::where('medicine_id', $medicine->id)
                ->where('branch_id', auth()->user()->current_branch_id)
                ->sum('remaining_quantity');
                
            $stockPercentage = $medicine->reorder_level > 0 ? min(100, ($stock / max(1, ($medicine->reorder_level * 2))) * 100) : 100;
            
            return [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'code' => $medicine->code,
                'brand' => $medicine->brand,
                'strength' => $medicine->strength,
                'form' => $medicine->form,
                'category_name' => $medicine->category?->name ?? 'Default',
                'stock' => (int)$stock,
                'reorder_level' => (int)$medicine->reorder_level,
                'requires_prescription' => (bool)$medicine->requires_prescription,
                'stock_percentage' => $stockPercentage,
                'stock_color' => $stock <= $medicine->reorder_level ? 'bg-rose-500' : 'bg-emerald-500',
                'view_url' => route('pharmacy.inventory.batch', $medicine->id), // Placeholder
                'edit_url' => '#',
                'is_about_to_expire' => false, // Simplified
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => $medicines instanceof \Illuminate\Pagination\LengthAwarePaginator ? [
                'current_page' => $medicines->currentPage(),
                'last_page' => $medicines->lastPage(),
                'per_page' => $medicines->perPage(),
                'total' => $medicines->total(),
                'links' => (string)$medicines->links()
            ] : null,
            'stats' => [
                'total' => $medicines instanceof \Illuminate\Pagination\LengthAwarePaginator ? $medicines->total() : $medicines->count(),
                'low_stock' => 0, // Simplified
                'out_of_stock' => 0 // Simplified
            ]
        ]);
    }

    /**
     * Get medicine stock for modal
     */
    public function medicineStock($id)
    {
        $stock = MedicineBatch::where('medicine_id', $id)
            ->where('branch_id', auth()->user()->current_branch_id)
            ->sum('remaining_quantity');
            
        return response()->json(['stock' => (int)$stock]);
    }
}