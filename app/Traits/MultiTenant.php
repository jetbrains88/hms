<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait MultiTenant
{
    /**
     * Scope query to a specific branch
     */
    public function scopeForBranch(Builder $query, $branchId = null): Builder
    {
        $branchId = $branchId ?? session('current_branch_id') ?? auth()->user()?->current_branch_id;
        
        if ($branchId && $this->isBranchScoped()) {
            return $query->where($this->getTable() . '.branch_id', $branchId);
        }
        
        return $query;
    }
    
    /**
     * Scope query to all branches user has access to
     */
    public function scopeForUserBranches(Builder $query): Builder
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query;
        }
        
        // Super admins can see all branches
        if ($user->hasRole('super_admin')) {
            return $query;
        }
        
        $branchIds = $user->branches()->pluck('branches.id')->toArray();
        
        if ($this->isBranchScoped()) {
            return $query->whereIn($this->getTable() . '.branch_id', $branchIds);
        }
        
        return $query;
    }
    
    /**
     * Check if model has branch_id column
     */
    protected function isBranchScoped(): bool
    {
        return in_array('branch_id', $this->getFillable()) || 
               array_key_exists('branch_id', $this->getAttributes());
    }
    
    /**
     * Boot the multi-tenant trait
     */
    protected static function bootMultiTenant()
    {
        static::addGlobalScope('branch', function (Builder $builder) {
            $user = auth()->user();
            if ($user && !$user->hasRole('super_admin')) {
                $model = $builder->getModel();
                
                $builder->where(function (Builder $query) use ($user, $model) {
                    // Branch-specific results
                    $branchIds = $user->branches()->pluck('branches.id')->toArray();
                    $query->whereIn($model->getTable() . '.branch_id', $branchIds);
                    
                    // Include global items if supported
                    if (in_array('is_global', $model->getFillable())) {
                        $query->orWhere($model->getTable() . '.is_global', true);
                    }
                });
            }
        });
    }
}