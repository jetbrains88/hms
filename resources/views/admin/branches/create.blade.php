@extends('layouts.app')

@section('title', 'Add Branch - NHMP HMS')
@section('page-title', 'Add New Branch')
@section('breadcrumb', 'Add Branch')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800">Branch Information</h3>
            <a href="{{ route('admin.branches.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
        </div>
        
        <form action="{{ route('admin.branches.store') }}" method="POST" class="p-8 space-y-6" x-data="{ active: {{ old('is_active', true) ? 'true' : 'false' }} }">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Branch Name -->
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-slate-700 tracking-wide">Branch Name <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200"
                            placeholder="e.g. NHMP Hospital Islamabad">
                    </div>
                    @error('name')<p class="text-xs text-rose-500 font-medium pl-1">{{ $message }}</p>@enderror
                </div>

                <!-- Branch Type -->
                <div class="space-y-2">
                    <label for="type" class="text-sm font-bold text-slate-700 tracking-wide">Branch Type <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600">
                            <i class="fas fa-tag"></i>
                        </div>
                        <select name="type" id="type" required
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200">
                            <option value="CMO" {{ old('type') == 'CMO' ? 'selected' : '' }}>CMO (Central Medical Office)</option>
                            <option value="RMO" {{ old('type') == 'RMO' ? 'selected' : '' }}>RMO (Regional Medical Office)</option>
                        </select>
                    </div>
                    @error('type')<p class="text-xs text-rose-500 font-medium pl-1">{{ $message }}</p>@enderror
                </div>

                <!-- Office (Hierarchy) -->
                <div class="space-y-2">
                    <label for="office_id" class="text-sm font-bold text-slate-700 tracking-wide">Parent Office (Hierarchy)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <select name="office_id" id="office_id"
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200">
                            <option value="">Select Office</option>

                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                    {{ $office->name }} ({{ $office->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('office_id')<p class="text-xs text-rose-500 font-medium pl-1">{{ $message }}</p>@enderror
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label for="is_active" class="text-sm font-bold text-slate-700 tracking-wide">Status</label>
                    <div class="flex items-center space-x-4 h-[50px] px-4 bg-slate-50 border border-slate-200 rounded-xl">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" x-model="active" class="sr-only">
                                <div class="w-10 h-6 bg-slate-300 rounded-full shadow-inner transition-colors duration-200" :class="active ? 'bg-indigo-500' : ''"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full shadow transition-transform duration-200 select-none" :class="active ? 'translate-x-4' : ''"></div>
                            </div>
                            <div class="ml-3 text-slate-700 font-medium text-sm">Active</div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="space-y-2">
                <label for="location" class="text-sm font-bold text-slate-700 tracking-wide">Physical Location</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <textarea name="location" id="location" rows="3"
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200"
                        placeholder="Complete address of the branch...">{{ old('location') }}</textarea>
                </div>
                @error('location')<p class="text-xs text-rose-500 font-medium pl-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transform active:scale-95 transition-all duration-200">
                    Create Branch
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
