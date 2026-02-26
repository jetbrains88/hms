<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\StockAlert;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{
    /**
     * Display stock alerts
     */
    public function index(Request $request)
    {
        $branchId = auth()->user()->current_branch_id;
        
        $query = StockAlert::with(['medicine', 'resolvedBy'])
            ->where('branch_id', $branchId);
        
        if ($request->has('show_resolved')) {
            // Show all
        } else {
            $query->where('is_resolved', false);
        }
        
        $alerts = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('pharmacy.alerts.index', compact('alerts'));
    }

    /**
     * Resolve alert
     */
    public function resolve(Request $request, StockAlert $alert)
    {
        $request->validate([
            'resolution_notes' => 'nullable|string|max:500',
        ]);
        
        $alert->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
            'resolution_notes' => $request->resolution_notes,
        ]);
        
        return redirect()
            ->back()
            ->with('success', 'Alert resolved');
    }

    /**
     * Resolve all alerts for a medicine
     */
    public function resolveAll(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'resolution_notes' => 'nullable|string|max:500',
        ]);
        
        StockAlert::where('medicine_id', $request->medicine_id)
            ->where('branch_id', auth()->user()->current_branch_id)
            ->where('is_resolved', false)
            ->update([
                'is_resolved' => true,
                'resolved_at' => now(),
                'resolved_by' => auth()->id(),
                'resolution_notes' => $request->resolution_notes,
            ]);
        
        return redirect()
            ->back()
            ->with('success', 'All alerts resolved');
    }
}