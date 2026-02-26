<?php

namespace App\Traits;

use App\Models\Branch;
use App\Models\Scopes\BranchScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToBranch
{
    /**
     * Boot the trait to automatically apply the scope and saving events.
     */
    protected static function bootBelongsToBranch(): void
    {
        // 1. Automatically filter queries by branch
        static::addGlobalScope(new BranchScope);

        // 2. Automatically set the branch_id when creating new records
        static::creating(function ($model) {
            if (Auth::check() && empty($model->branch_id)) {
                $user = Auth::user();
                // Assign to the user's active branch context
                $model->branch_id = session('current_branch_id', $user->primary_branch_id);
            }
        });
    }

    /**
     * Define the relationship to the Branch model.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
