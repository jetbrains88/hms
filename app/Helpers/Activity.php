<?php

namespace App\Helpers;

use App\Models\AuditLog;
use App\Models\AuditLogDetail;
use Illuminate\Support\Facades\Request;

class Activity
{
    /**
     * Log an activity
     */
    public static function log($description, $properties = [])
    {
        // This is a simplified version - in production, use spatie/laravel-activitylog
        $user = auth()->user();

        $log = AuditLog::create([
            'uuid' => \Str::uuid(),
            'user_id' => $user?->id,
            'branch_id' => session('current_branch_id'),
            'action' => $description,
            'entity_type' => $properties['entity_type'] ?? null,
            'entity_id' => $properties['entity_id'] ?? null,
            'ip_address' => Request::ip(),
        ]);

        if (isset($properties['details']) && is_array($properties['details'])) {
            foreach ($properties['details'] as $field => $values) {
                AuditLogDetail::create([
                    'audit_log_id' => $log->id,
                    'field_name' => $field,
                    'old_value' => $values['old'] ?? null,
                    'new_value' => $values['new'] ?? null,
                ]);
            }
        }

        return $log;
    }
}

// Global helper function
if (!function_exists('activity')) {
    function activity($description = null, $properties = [])
    {
        if ($description) {
            return Activity::log($description, $properties);
        }

        return new Activity();
    }
}
