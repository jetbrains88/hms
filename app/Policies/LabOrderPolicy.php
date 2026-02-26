<?php

namespace App\Policies;

use App\Models\LabOrder;
use App\Models\User;

class LabOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_lab_reports');
    }

    public function view(User $user, LabOrder $labOrder): bool
    {
        if (!$user->hasPermission('view_lab_reports')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->branches()->where('branch_id', $labOrder->branch_id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create_lab_orders');
    }

    public function process(User $user, LabOrder $labOrder): bool
    {
        if (!$user->hasRole('lab')) {
            return false;
        }

        return $user->branches()->where('branch_id', $labOrder->branch_id)->exists();
    }

    public function verify(User $user, LabOrder $labOrder): bool
    {
        if (!$user->hasPermission('verify_lab_reports')) {
            return false;
        }

        return $user->branches()->where('branch_id', $labOrder->branch_id)->exists();
    }
}