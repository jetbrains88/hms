<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Branch;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display roles list
     */
    public function index()
    {
        $roles = Role::with(['permissions', 'branch'])->orderBy('name')->paginate(15);
        $permissions = Permission::all()->groupBy('group'); // Add this line

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show create role form
     */
    public function create()
    {
        $permissions = $this->roleService->getPermissionsGrouped();
        $branches = Branch::where('is_active', true)->get();

        return view('admin.roles.create', compact('permissions', 'branches'));
    }

    /**
     * Store new role
     */
    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->createRole($request->validated());

        return redirect()
            ->route('admin.roles.show', $role)
            ->with('success', 'Role created successfully');
    }

    /**
     * Show role details
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'branch', 'users']);

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show edit role form
     */
    public function edit(Role $role)
    {
        $permissions = $this->roleService->getPermissionsGrouped();
        $branches = Branch::where('is_active', true)->get();

        return view('admin.roles.edit', compact('role', 'permissions', 'branches'));
    }

    /**
     * Update role
     */
    public function update(StoreRoleRequest $request, Role $role)
    {
        $role = $this->roleService->updateRole($role, $request->validated());

        return redirect()
            ->route('admin.roles.show', $role)
            ->with('success', 'Role updated successfully');
    }

    /**
     * Clone role to branch
     */
    public function cloneToBranch(Request $request, Role $role)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $newRole = $this->roleService->cloneRoleToBranch($role, $request->branch_id);

        return redirect()
            ->route('admin.roles.show', $newRole)
            ->with('success', 'Role cloned successfully');
    }
}
