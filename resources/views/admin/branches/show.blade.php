@extends('layouts.app')

@section('title', 'Branch Details - NHMP HMS')
@section('page-title', 'Branch Details')
@section('breadcrumb', 'Branch Info')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100 shadow-sm">
                <i class="fas fa-hospital text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $branch->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $branch->type == 'CMO' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">
                        {{ $branch->type }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $branch->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.branches.edit', $branch) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors font-bold text-sm shadow-sm">
                <i class="fas fa-edit mr-2 text-blue-500"></i> Edit Branch
            </a>
            <form action="{{ route('admin.branches.toggle-status', $branch) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 {{ $branch->is_active ? 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' }} border rounded-xl transition-colors font-bold text-sm shadow-sm">
                    <i class="fas {{ $branch->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-2"></i> 
                    {{ $branch->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stats Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-6">Branch Statistics</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-blue-500 text-white flex items-center justify-center">
                                <i class="fas fa-user-injured text-xs"></i>
                            </div>
                            <span class="text-sm font-bold text-blue-800">Total Patients</span>
                        </div>
                        <span class="text-lg font-black text-blue-900">{{ $stats['total_patients'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                                <i class="fas fa-users-cog text-xs"></i>
                            </div>
                            <span class="text-sm font-bold text-emerald-800">Total Staff</span>
                        </div>
                        <span class="text-lg font-black text-emerald-900">{{ $stats['total_staff'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-amber-500 text-white flex items-center justify-center">
                                <i class="fas fa-calendar-check text-xs"></i>
                            </div>
                            <span class="text-sm font-bold text-amber-800">Visits Today</span>
                        </div>
                        <span class="text-lg font-black text-amber-900">{{ $stats['visits_today'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl border border-purple-100">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-purple-500 text-white flex items-center justify-center">
                                <i class="fas fa-flask text-xs"></i>
                            </div>
                            <span class="text-sm font-bold text-purple-800">Pending Labs</span>
                        </div>
                        <span class="text-lg font-black text-purple-900">{{ $stats['pending_lab_orders'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-4">Core Details</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mb-1">Office Hierarchy</div>
                        <div class="text-sm font-bold text-slate-700">{{ $branch->office->name ?? 'Unassigned' }}</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $branch->office->type ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mb-1">Physical Location</div>
                        <div class="text-sm text-slate-600 leading-relaxed">{{ $branch->location ?? 'No location provided' }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mb-1">System UUID</div>
                        <div class="text-[10px] font-mono text-slate-400">{{ $branch->uuid }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content (Staff List) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Assigned Staff</h3>
                        <p class="text-xs text-slate-500 font-medium">Healthcare professionals assigned to this branch</p>
                    </div>
                    <a href="{{ route('admin.branches.users', $branch) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest">
                        View All <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Staff Member</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Role</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Primary?</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($branch->users as $user)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-500 uppercase">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">{{ $user->name }}</div>
                                            <div class="text-[11px] text-slate-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                        <span class="px-1.5 py-0.5 rounded-md bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-tighter">
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->pivot->is_primary)
                                    <span class="text-emerald-500" title="Primary Branch">
                                        <i class="fas fa-check-circle"></i> Yes
                                    </span>
                                    @else
                                    <span class="text-slate-300">
                                        No
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic text-sm">
                                    No staff members assigned to this branch yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-200 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <h4 class="font-bold text-lg mb-1">Add Staff Member</h4>
                        <p class="text-indigo-100 text-xs mb-4">Assign a new doctor or nurse to this branch.</p>
                        <a href="{{ route('admin.users.create') }}?branch_id={{ $branch->id }}" class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 rounded-xl font-bold text-xs hover:bg-indigo-50 transition-colors">
                            Assign Now
                        </a>
                    </div>
                    <i class="fas fa-user-plus absolute -right-4 -bottom-4 text-8xl text-indigo-500/30 group-hover:scale-110 transition-transform duration-500 rotate-12"></i>
                </div>
                
                <div class="bg-slate-800 rounded-2xl p-6 shadow-lg shadow-slate-200 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <h4 class="font-bold text-lg mb-1">Branch Inventory</h4>
                        <p class="text-slate-400 text-xs mb-4">Check stock levels for this specific location.</p>
                        <a href="{{ route('pharmacy.inventory') }}?branch_id={{ $branch->id }}" class="inline-flex items-center px-4 py-2 bg-slate-700 text-white rounded-xl font-bold text-xs hover:bg-slate-600 transition-colors">
                            Check Stock
                        </a>
                    </div>
                    <i class="fas fa-boxes absolute -right-4 -bottom-4 text-8xl text-slate-700/50 group-hover:scale-110 transition-transform duration-500 rotate-12"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
