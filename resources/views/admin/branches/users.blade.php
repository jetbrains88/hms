@extends('layouts.app')

@section('title', 'Branch Staff - NHMP HMS')
@section('page-title', 'Branch Staff: ' . $branch->name)
@section('breadcrumb', 'Staff List')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.branches.show', $branch) }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Branch Details
        </a>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
            Total Staff: {{ $users->total() }}
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Employee Information</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Roles \ Permissions</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Office</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Type</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 border border-slate-200 flex items-center justify-center text-slate-500 font-bold uppercase shadow-sm">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500 font-medium">{{ $user->email }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">ID: {{ $user->employee_id ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-xs">
                                @foreach($user->roles as $role)
                                <span class="px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-tighter border border-indigo-100">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-600">{{ $user->office->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->pivot->is_primary)
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest">Primary</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest">Secondary</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors inline-block" title="Edit User">
                                <i class="fas fa-user-edit text-lg"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users-slash text-4xl text-slate-200 mb-4"></i>
                                <h3 class="text-lg font-bold text-slate-800">No Staff Members Found</h3>
                                <p class="text-slate-500 text-sm">There are no users assigned to this branch yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
