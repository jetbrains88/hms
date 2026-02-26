<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBranchRequest;
use App\Models\Branch;
use App\Models\Office;
use App\Services\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * Display branches list
     */
    public function index()
    {
        $branches = Branch::with(['office', 'users'])
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show create branch form
     */
    public function create()
    {
        $offices = Office::where('is_active', true)->get();

        return view('admin.branches.create', compact('offices'));
    }

    /**
     * Store new branch
     */
    public function store(StoreBranchRequest $request)
    {
        $branch = $this->branchService->createBranch($request->validated());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Branch created successfully',
                'branch' => $branch
            ]);
        }

        return redirect()
            ->route('admin.branches.show', $branch)
            ->with('success', 'Branch created successfully');
    }

    /**
     * Show branch details
     */
    public function show(Branch $branch)
    {
        $branch->load(['office', 'users' => function ($q) {
            $q->with('roles')->limit(10);
        }]);

        $stats = $this->branchService->getBranchStats($branch->id);

        return view('admin.branches.show', compact('branch', 'stats'));
    }

    /**
     * Show edit branch form
     */
    public function edit(Branch $branch)
    {
        $offices = Office::where('is_active', true)->get();

        return view('admin.branches.edit', compact('branch', 'offices'));
    }

    /**
     * Update branch
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->branchService->updateBranch($branch, $request->validated());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Branch updated successfully',
                'branch' => $branch
            ]);
        }

        return redirect()
            ->route('admin.branches.show', $branch)
            ->with('success', 'Branch updated successfully');
    }

    /**
     * Toggle branch status
     */
    public function toggleStatus(Branch $branch)
    {
        $branch->update(['is_active' => !$branch->is_active]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Branch status updated successfully',
                'is_active' => $branch->is_active
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Branch status updated');
    }

    /**
     * Show branch users
     */
    public function users(Branch $branch)
    {
        $users = $branch->users()->with('roles')->paginate(20);

        return view('admin.branches.users', compact('branch', 'users'));
    }
}
