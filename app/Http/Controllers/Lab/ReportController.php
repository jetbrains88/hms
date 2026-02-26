<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use App\Models\LabOrderItem;
use App\Models\LabTestType;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display reports index.
     */
    public function index()
    {
        $branchId = session('current_branch_id');

        $stats = [
            'total_orders' => LabOrder::where('branch_id', $branchId)->count(),
            'completed_orders' => LabOrder::where('branch_id', $branchId)
                ->where('status', 'completed')
                ->count(),
            'verified_orders' => LabOrder::where('branch_id', $branchId)
                ->where('is_verified', true)
                ->count(),
            'avg_processing_time' => LabOrder::where('branch_id', $branchId)
                ->where('status', 'completed')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, reporting_date)) as avg_hours'))
                ->value('avg_hours'),
        ];

        // Monthly data
        $monthlyData = LabOrder::where('branch_id', $branchId)
            ->whereYear('created_at', now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top tests
        $topTests = LabOrderItem::select(
            'lab_test_type_id',
            DB::raw('COUNT(*) as total')
        )
            ->whereHas('labOrder', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->groupBy('lab_test_type_id')
            ->with('labTestType')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('lab.reports.index', compact('stats', 'monthlyData', 'topTests'));
    }

    /**
     * Show lab report.
     */
    public function show(LabOrder $labOrder)
    {
        $labOrder->load([
            'patient',
            'doctor',
            'verifiedBy',
            'items' => function ($q) {
                $q->with(['labTestType', 'labResults.labTestParameter']);
            }
        ]);

        return view('lab.reports.show', compact('labOrder'));
    }

    /**
     * Generate PDF report.
     */
    public function pdf(LabOrder $labOrder)
    {
        $labOrder->load([
            'patient',
            'doctor',
            'verifiedBy',
            'items' => function ($q) {
                $q->with(['labTestType', 'labResults.labTestParameter']);
            }
        ]);

        $pdf = Pdf::loadView('lab.reports.pdf', compact('labOrder'));

        return $pdf->download('lab-report-' . $labOrder->lab_number . '.pdf');
    }

    /**
     * Export report to CSV.
     */
    public function export(Request $request, $type)
    {
        $branchId = session('current_branch_id');
        $data = [];
        $headers = [];

        switch ($type) {
            case 'orders':
                $data = $this->getOrdersExportData($branchId, $request);
                $headers = ['Lab #', 'Date', 'Patient', 'Doctor', 'Tests', 'Priority', 'Status', 'Verified'];
                break;

            case 'tests':
                $data = $this->getTestsExportData($branchId, $request);
                $headers = ['Test', 'Department', 'Total Orders', 'Completed', 'Avg. Time (hours)'];
                break;

            case 'results':
                $data = $this->getResultsExportData($branchId, $request);
                $headers = ['Date', 'Patient', 'Test', 'Parameter', 'Result', 'Reference', 'Abnormal'];
                break;

            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }

        $csv = $this->reportService->toCsv($data, $headers);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $type . '-report-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Get orders export data.
     */
    protected function getOrdersExportData($branchId, $request)
    {
        $query = LabOrder::with(['patient', 'doctor', 'items.labTestType'])
            ->where('branch_id', $branchId);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return $orders->map(function ($order) {
            return [
                $order->lab_number,
                $order->created_at->format('Y-m-d H:i'),
                $order->patient->name,
                $order->doctor->name,
                $order->items->pluck('labTestType.name')->implode(', '),
                $order->priority,
                $order->status,
                $order->is_verified ? 'Yes' : 'No',
            ];
        })->toArray();
    }

    /**
     * Get tests export data.
     */
    protected function getTestsExportData($branchId, $request)
    {
        $testTypes = LabTestType::withCount(['labOrderItems' => function ($q) use ($branchId) {
            $q->whereHas('labOrder', function ($oq) use ($branchId) {
                $oq->where('branch_id', $branchId);
            });
        }])->get();

        return $testTypes->map(function ($test) use ($branchId) {
            $completed = LabOrderItem::where('lab_test_type_id', $test->id)
                ->whereHas('labOrder', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->where('status', 'completed')
                ->count();

            $avgTime = LabOrderItem::where('lab_test_type_id', $test->id)
                ->whereHas('labOrder', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId)
                        ->whereNotNull('reporting_date');
                })
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, lab_orders.created_at, lab_orders.reporting_date)) as avg_hours'))
                ->join('lab_orders', 'lab_orders.id', '=', 'lab_order_items.lab_order_id')
                ->value('avg_hours');

            return [
                $test->name,
                $test->department,
                $test->lab_order_items_count,
                $completed,
                round($avgTime ?? 0, 1),
            ];
        })->toArray();
    }

    /**
     * Get results export data.
     */
    protected function getResultsExportData($branchId, $request)
    {
        $items = LabOrderItem::with([
            'labOrder.patient',
            'labTestType',
            'labResults.labTestParameter'
        ])
            ->whereHas('labOrder', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->where('status', 'completed')
            ->limit(500)
            ->get();

        $data = [];

        foreach ($items as $item) {
            foreach ($item->labResults as $result) {
                $data[] = [
                    $item->labOrder->created_at->format('Y-m-d'),
                    $item->labOrder->patient->name,
                    $item->labTestType->name,
                    $result->labTestParameter->name,
                    $result->display_value,
                    $result->labTestParameter->reference_range ?? 'N/A',
                    $result->is_abnormal ? 'Yes' : 'No',
                ];
            }
        }

        return $data;
    }
}
