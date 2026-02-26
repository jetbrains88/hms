<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DesignationController extends Controller
{
    /**
     * Display a listing of designations.
     */
    public function index()
    {
        $designations = Designation::orderBy('bps')->orderBy('title')->paginate(20);
        return view('admin.designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new designation.
     */
    public function create()
    {
        return view('admin.designations.create');
    }

    /**
     * Store a newly created designation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'short_form' => 'nullable|max:50',
            'bps' => 'nullable|integer',
            'cadre_type' => 'nullable|max:50',
            'rank_group' => 'nullable|max:100',
        ]);

        $validated['uuid'] = (string) Str::uuid();

        Designation::create($validated);

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation created successfully.');
    }

    /**
     * Display the specified designation.
     */
    public function show(Designation $designation)
    {
        return view('admin.designations.show', compact('designation'));
    }

    /**
     * Show the form for editing the specified designation.
     */
    public function edit(Designation $designation)
    {
        return view('admin.designations.edit', compact('designation'));
    }

    /**
     * Update the specified designation.
     */
    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'short_form' => 'nullable|max:50',
            'bps' => 'nullable|integer',
            'cadre_type' => 'nullable|max:50',
            'rank_group' => 'nullable|max:100',
        ]);

        $designation->update($validated);

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    /**
     * Remove the specified designation.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
