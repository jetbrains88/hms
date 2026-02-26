<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Models\AuditLogDetail;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            static::logAction($model, 'created');
        });

        static::updated(function ($model) {
            static::logAction($model, 'updated');
        });

        static::deleted(function ($model) {
            static::logAction($model, 'deleted');
        });
    }

    protected static function logAction($model, $action)
    {
        if (!config('app.audit_enabled', true)) {
            return;
        }

        $user = Auth::user();
        $oldValues = [];
        $newValues = [];

        if ($action === 'updated') {
            foreach ($model->getDirty() as $attribute => $newValue) {
                if (!in_array($attribute, ['updated_at', 'created_at'])) {
                    $oldValues[$attribute] = $model->getOriginal($attribute);
                    $newValues[$attribute] = $newValue;
                }
            }
        }

        // Determine branch_id safely
        $branchId = null;

        // Check if model has a branch_id attribute (direct column)
        if (property_exists($model, 'branch_id') || array_key_exists('branch_id', $model->getAttributes())) {
            $branchId = $model->branch_id;
        }
        // Check if model has a branch relationship
        elseif (method_exists($model, 'branch') && $model->branch) {
            $branchId = $model->branch->id;
        }
        // Fallback to user's current branch
        elseif ($user) {
            $branchId = $user->current_branch_id ?? null;
        }

        $auditLog = AuditLog::create([
            'uuid' => (string) \Str::uuid(),
            'user_id' => $user?->id,
            'branch_id' => $branchId,
            'action' => $action,
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'ip_address' => request()->ip(),
        ]);

        if ($action === 'updated' && !empty($oldValues)) {
            foreach ($oldValues as $field => $oldValue) {
                AuditLogDetail::create([
                    'audit_log_id' => $auditLog->id,
                    'field_name' => $field,
                    'old_value' => (string) $oldValue,
                    'new_value' => (string) $newValues[$field],
                ]);
            }
        }
    }
}
