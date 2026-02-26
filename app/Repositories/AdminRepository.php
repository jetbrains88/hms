<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Models\Designation;
use App\Models\Medicine;
use App\Models\Office;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Prescription;
use App\Models\Role;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminRepository implements AdminRepositoryInterface
{
    public function getDashboardStats()
    {
        // Try to get low stock medicines count with error handling
        try {
            $lowStockMedicines = DB::table('medicine_stock_value')
                ->where('stock', '>', 0)
                ->where('stock_status', 'low_stock')
                ->count();
        } catch (\Exception $e) {
            // Fallback query if view doesn't exist
            $lowStockMedicines = DB::table('medicines')
                ->join('medicine_batches', 'medicines.id', '=', 'medicine_batches.medicine_id')
                ->where('medicine_batches.remaining_quantity', '>', 0)
                ->where('medicine_batches.remaining_quantity', '<=', DB::raw('medicines.reorder_level'))
                ->count(DB::raw('DISTINCT medicines.id'));

            Log::warning('medicine_stock_value view not found, using fallback query', ['error' => $e->getMessage()]);
        }

        return [
            'totalPatients' => Patient::count(),
            'todayVisits' => Visit::whereDate('visits.created_at', today())->count(),
            'pendingPrescriptions' => Prescription::where('status', 'pending')->count(),
            'lowStockMedicines' => $lowStockMedicines,
            'totalUsers' => User::count(),
            'totalRoles' => Role::count(),
            'activeUsers' => User::where('is_active', true)->count(),
            'todayRevenue' => 1500, // You might want to calculate this properly
        ];
    }

    public function getRecentPatients($limit = 10)
    {
        return Patient::with('office', 'designation')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentVisits($limit = 10)
    {
        return Visit::with(['patient', 'doctor'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getAllUsers($paginate = 20)
    {
        return User::with(['roles']) // Removed 'designation' since users don't have it
            ->latest()
            ->paginate($paginate);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        // Sync multiple roles
        if (!empty($data['role_ids'])) {
            $user->roles()->sync($data['role_ids']);
        }

        return $user;
    }

    public function updateUser($id, array $data): User
    {
        try {
            Log::info('Update User ID: ' . $id);
            $user = User::findOrFail($id);

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            // Sync multiple roles
            if (isset($data['role_ids'])) {
                $user->roles()->sync($data['role_ids']);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }


        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function getAllRoles()
    {
        return Role::with('permissions')->latest()->get();
    }

    public function createRole(array $data)
    {
        Log::info('Create Role:|: ', $data);
        $role = Role::create([
            'name' => $data['name'],
            'display_name' => $data['display_name']
        ]);

        if (!empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        return $role;
    }

    public function updateRole($id, array $data)
    {
        Log::info('Role_UPDATE_Req: ', $data);
        $role = Role::findOrFail($id);
        $role->update([
            'name' => $data['name'],
            'display_name' => $data['display_name']
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);
        return $role;
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        return $role->delete();
    }

    public function getAllPermissions()
    {
        return Permission::all()->groupBy('group');
    }

    public function getVisitTrendData()
    {
        $today = Visit::whereDate('visits.created_at', today())->count();
        $yesterday = Visit::whereDate('visits.created_at', Carbon::yesterday())->count();

        return [
            'today' => $today,
            'yesterday' => $yesterday,
            'visitTrend' => $this->calculateTrend($today, $yesterday)
        ];
    }

    private function calculateTrend($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        $change = (($current - $previous) / $previous) * 100;
        return round($change, 1);
    }

    public function getSystemMetrics()
    {
        return [
            'totalDepartments' => Office::where('type', 'department')->count(),
            'totalDesignations' => Designation::count(),
            'totalMedicines' => Medicine::count(),
            'expiredMedicines' => DB::table('medicine_batches')
                ->where('expiry_date', '<', today())
                ->count(),
        ];
    }

    private function calculateTodayRevenue()
    {
        try {
            return DB::table('prescriptions')
                ->join('medicines', 'prescriptions.medicine_id', '=', 'medicines.id')
                ->whereDate('prescriptions.created_at', today())
                ->where('prescriptions.status', 'dispensed')
                ->sum(DB::raw('prescriptions.quantity * medicines.selling_price'));
        } catch (\Exception $e) {
            Log::error('Revenue calculation error: ' . $e->getMessage());
            return 0;
        }
    }
}
