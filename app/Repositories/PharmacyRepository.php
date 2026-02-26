<?php

namespace App\Repositories;

use App\Models\InventoryLog;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionDispensation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PharmacyRepository
{
    public function getDashboardStats(): array
    {
        // FIXED: Use prescription_dispensations table for dispensed counts
        $dispensedToday = PrescriptionDispensation::whereDate('dispensed_at', today())
            ->count();

        // Get total stock value - handle potential missing view
        try {
            $totalStockValue = DB::table('medicine_stock_value')
                ->sum(DB::raw('stock * unit_price')) ?? 0;
        } catch (\Exception $e) {
            // Fallback if view doesn't exist
            $totalStockValue = DB::table('medicine_batches')
                ->where('remaining_quantity', '>', 0)
                ->sum(DB::raw('remaining_quantity * unit_price')) ?? 0;
            Log::warning('medicine_stock_value view not found, using fallback', ['error' => $e->getMessage()]);
        }

        // Get low stock items with error handling
        try {
            $lowStockItems = DB::table('medicine_stock_value')
                ->where('stock_status', 'low_stock')
                ->count();
        } catch (\Exception $e) {
            // Fallback query if view doesn't exist
            $lowStockItems = Medicine::whereHas('batches', function ($query) {
                $query->select(DB::raw('SUM(remaining_quantity) as total_stock'))
                    ->having('total_stock', '>', 0)
                    ->having('total_stock', '<=', DB::raw('medicines.reorder_level'));
            })->count();
            Log::warning('medicine_stock_value view not found for low stock, using fallback');
        }

        // FIXED: Get recent dispensations from prescription_dispensations table
        $recentDispenses = PrescriptionDispensation::with([
            'prescription.medicine',
            'prescription.diagnosis.visit.patient',
            'dispensedBy'
        ])
            ->latest('dispensed_at')
            ->limit(10)
            ->get();

        return [
            'pending_prescriptions' => Prescription::where('status', 'pending')->count(),
            'dispensed_today' => $dispensedToday,
            'total_stock_value' => $totalStockValue,
            'low_stock_items' => $lowStockItems,
            'recent_dispenses' => $recentDispenses,
            'inventory_alerts' => $this->getInventoryAlerts(),
        ];
    }

    public function getInventoryAlerts(): array
    {
        // FIXED: Handle potential missing view and columns
        try {
            $lowStock = DB::table('medicine_stock_value')
                ->where('stock_status', 'low_stock')
                ->where('stock', '>', 0)
                ->select('id', 'name', 'stock', 'reorder_level')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Fallback if view doesn't exist or missing columns
            $lowStock = DB::table('medicines')
                ->join('medicine_batches', 'medicines.id', '=', 'medicine_batches.medicine_id')
                ->select(
                    'medicines.id',
                    'medicines.name',
                    DB::raw('SUM(medicine_batches.remaining_quantity) as stock'),
                    'medicines.reorder_level'
                )
                ->where('medicine_batches.remaining_quantity', '>', 0)
                ->groupBy('medicines.id', 'medicines.name', 'medicines.reorder_level')
                ->having('stock', '<=', DB::raw('medicines.reorder_level'))
                ->limit(5)
                ->get();
            Log::warning('medicine_stock_value view not found for low stock alerts');
        }

        // Expiring soon - this query is correct as it uses medicine_batches
        $expiringSoon = DB::table('medicine_batches')
            ->join('medicines', 'medicine_batches.medicine_id', '=', 'medicines.id')
            ->whereDate('medicine_batches.expiry_date', '<=', now()->addMonths(3))
            ->whereDate('medicine_batches.expiry_date', '>', now())
            ->where('medicine_batches.remaining_quantity', '>', 0)
            ->select(
                'medicines.id',
                'medicines.name',
                'medicine_batches.expiry_date',
                'medicine_batches.remaining_quantity as stock'
            )
            ->orderBy('medicine_batches.expiry_date')
            ->limit(5)
            ->get();

        // Out of stock with error handling
        try {
            $outOfStock = DB::table('medicine_stock_value')
                ->where('stock', 0)
                ->select('id', 'name')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Fallback if view doesn't exist
            $outOfStock = DB::table('medicines')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('medicine_batches')
                        ->whereColumn('medicine_batches.medicine_id', 'medicines.id')
                        ->where('medicine_batches.remaining_quantity', '>', 0);
                })
                ->select('id', 'name')
                ->limit(5)
                ->get();
            Log::warning('medicine_stock_value view not found for out of stock');
        }

        return [
            'low_stock' => $lowStock,
            'expiring_soon' => $expiringSoon,
            'out_of_stock' => $outOfStock,
        ];
    }

    public function getMedicines(array $filters = []): LengthAwarePaginator
    {
        $query = Medicine::with('category');
        $count = $query->count();

        if (!empty($filters['category']) && $filters['category'] != 'All') {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['search']) && $filters['search'] != 'null') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('medicines.name', 'like', "%{$search}%")
                    ->orWhere('medicines.generic_name', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['stock_status']) && $filters['stock_status'] != 'All') {
            try {
                // Try using the view first
                $query->join('medicine_stock_value', 'medicines.id', '=', 'medicine_stock_value.id');

                switch ($filters['stock_status']) {
                    case 'low':
                        $query->where('medicine_stock_value.stock_status', 'low_stock');
                        break;
                    case 'out':
                        $query->where('medicine_stock_value.stock', 0);
                        break;
                    case 'normal':
                        $query->where('medicine_stock_value.stock_status', 'in_stock');
                        break;
                }
            } catch (\Exception $e) {
                // Fallback if view doesn't exist
                Log::warning('medicine_stock_value view not found for filtering');
            }
        }

        if (!empty($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';

            // Define which columns belong to which table
            $medicineColumns = ['name', 'generic_name', 'category_id'];
            $viewColumns = ['stock', 'reorder_level', 'stock_status', 'unit_price'];

            $sortColumn = 'medicines.' . $filters['sort_by']; // Default to medicines table

            // Check if we should use the view for sorting
            if (!empty($filters['stock_status']) && $filters['stock_status'] != 'All' && in_array($filters['sort_by'], $viewColumns)) {
                // We joined medicine_stock_value, so use it for these columns
                $sortColumn = 'medicine_stock_value.' . $filters['sort_by'];
            }

            $query->orderBy($sortColumn, $direction);
        } else {
            $query->orderBy('medicines.name');
        }

        $perPage = ($filters['length'] ?? '16') === 'All' ? $count : ($filters['length'] ?? 16);

        return $query->paginate($perPage);
    }

    public function getInventoryHistory(Medicine $medicine): LengthAwarePaginator
    {
        return InventoryLog::where('medicine_id', $medicine->id)
            ->with('user')
            ->latest()
            ->paginate(20);
    }

    public function getDispenseHistory(array $filters = []): LengthAwarePaginator
    {
        // FIXED: Use prescription_dispensations table for dispense history
        $query = PrescriptionDispensation::with([
            'prescription.medicine',
            'prescription.prescriber',
            'prescription.diagnosis.visit.patient',
            'dispensedBy'
        ]);

        if (!empty($filters['date_from'])) {
            $query->whereDate('dispensed_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('dispensed_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['medicine_id'])) {
            $query->whereHas('prescription', function ($q) use ($filters) {
                $q->where('medicine_id', $filters['medicine_id']);
            });
        }

        return $query->latest('dispensed_at')->paginate(25);
    }
}
