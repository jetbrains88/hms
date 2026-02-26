<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfficeController extends Controller
{
    /**
     * Display a listing of offices.
     */
    public function index()
    {
        $offices = Office::with('parent')->orderBy('type')->orderBy('name')->paginate(20);
        return view('admin.offices.index', compact('offices'));
    }

    /**
     * Show the form for creating a new office.
     */
    public function create()
    {
        $offices = Office::where('is_active', true)->get();
        return view('admin.offices.create', compact('offices'));
    }

    /**
     * Store a newly created office.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'parent_id' => 'nullable|exists:offices,id',
            'is_active' => 'boolean',
        ]);

        $validated['uuid'] = (string) Str::uuid();
        $validated['is_active'] = $request->has('is_active');

        Office::create($validated);

        return redirect()->route('admin.offices.index')
            ->with('success', 'Office created successfully.');
    }

    /**
     * Display the specified office.
     */
    public function show(Office $office)
    {
        $office->load('parent', 'children');
        return view('admin.offices.show', compact('office'));
    }

    /**
     * Show the form for editing the specified office.
     */
    public function edit(Office $office)
    {
        $offices = Office::where('is_active', true)->where('id', '!=', $office->id)->get();
        return view('admin.offices.edit', compact('office', 'offices'));
    }

    /**
     * Update the specified office.
     */
    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'parent_id' => 'nullable|exists:offices,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $office->update($validated);

        return redirect()->route('admin.offices.index')
            ->with('success', 'Office updated successfully.');
    }

    /**
     * Remove the specified office.
     */
    public function destroy(Office $office)
    {
        if ($office->children()->count() > 0) {
            return redirect()->route('admin.offices.index')
                ->with('error', 'Cannot delete office with child offices.');
        }

        $office->delete();

        return redirect()->route('admin.offices.index')
            ->with('success', 'Office deleted successfully.');
    }
}
