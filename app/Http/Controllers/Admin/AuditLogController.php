<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\ReportService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::with(['user', 'branch', 'details']);

        // Apply filters
        if ($request->filled('entity_type')) {
            $query->where('entity_type', 'LIKE', '%' . $request->entity_type . '%');
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderByDesc('created_at')->paginate(20);

        // Get filter options
        $entityTypes = AuditLog::select('entity_type')->distinct()->pluck('entity_type');
        $actions = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit.index', compact('logs', 'entityTypes', 'actions'));
    }

    /**
     * Show audit log details
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load(['user', 'branch', 'details']);

        return view('admin.audit.show', compact('auditLog'));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $query = AuditLog::with(['user', 'branch']);

        // Apply same filters as index
        if ($request->filled('entity_type')) {
            $query->where('entity_type', 'LIKE', '%' . $request->entity_type . '%');
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderByDesc('created_at')->get();

        $csvData = $logs->map(function ($log) {
            return [
                'Date' => $log->created_at->format('Y-m-d H:i:s'),
                'User' => $log->user?->name ?? 'System',
                'Branch' => $log->branch?->name ?? 'N/A',
                'Action' => $log->action,
                'Entity' => class_basename($log->entity_type),
                'Entity ID' => $log->entity_id,
                'IP Address' => $log->ip_address,
            ];
        })->toArray();

        $csv = app(ReportService::class)->toCsv(
            $csvData,
            ['Date', 'User', 'Branch', 'Action', 'Entity', 'Entity ID', 'IP Address']
        );

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit-log-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
