@extends('layouts.app')

@section('title', 'System Audit Trail')

@section('page-title', 'Security & Audit Logs')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">System Audit Trail</h2>
            <p class="text-sm text-slate-500 font-medium">Monitor and track all critical actions performed within the system</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.audit.export', request()->all()) }}"
                class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 font-bold text-sm">
                <i class="fas fa-file-csv mr-2"></i>Export History
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Entity Type</label>
                <div class="relative">
                    <select name="entity_type" class="w-full pl-3 pr-10 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none font-medium text-slate-700">
                        <option value="">All Entities</option>
                        @foreach ($entityTypes as $type)
                            <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>
                                {{ class_basename($type) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-slate-300 text-xs"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Action Type</label>
                <div class="relative">
                    <select name="action" class="w-full pl-3 pr-10 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none font-medium text-slate-700">
                        <option value="">All Actions</option>
                        @foreach ($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-slate-300 text-xs"></i>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Date Range</label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-700">
                    </div>
                    <div class="flex items-center text-slate-300">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                    <div class="relative flex-1">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-700">
                    </div>
                </div>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition-all font-bold shadow-lg shadow-indigo-100 flex items-center justify-center gap-2">
                    <i class="fas fa-filter text-xs"></i>Apply
                </button>
                <a href="{{ route('admin.audit.index') }}"
                    class="p-2.5 bg-slate-100 text-slate-400 rounded-xl hover:bg-slate-200 hover:text-slate-600 transition-all flex items-center justify-center">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Timestamp</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Performed By</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target Entity</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Action</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Branch</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-700">{{ $log->created_at->format('d M, Y') }}</div>
                                <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-tighter">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs shadow-sm">
                                        {{ strtoupper(substr($log->user?->name ?? 'SY', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">{{ $log->user?->name ?? 'System Process' }}</div>
                                        <div class="text-[10px] font-medium text-slate-500">
                                            @if($log->user)
                                                ID: {{ $log->user->id }} · {{ $log->user->email }}
                                            @else
                                                Automated Task
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="p-1.5 bg-slate-100 rounded-lg text-slate-400">
                                        <i class="fas fa-cube text-xs"></i>
                                    </span>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700">{{ class_basename($log->entity_type) }}</div>
                                        <div class="text-[10px] font-bold text-slate-400 tracking-wider">REF ID: #{{ $log->entity_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm border
                                    @if ($log->action == 'created') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @elseif($log->action == 'updated') bg-sky-50 text-sky-600 border-sky-100
                                    @elseif($log->action == 'deleted') bg-rose-50 text-rose-600 border-rose-100
                                    @else bg-slate-50 text-slate-600 border-slate-200 @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-600 italic">
                                    {{ $log->branch?->name ?? 'System Core' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.audit.show', $log) }}" 
                                   class="inline-flex items-center justify-center h-10 w-10 bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 rounded-xl transition-all hover:shadow-md">
                                    <i class="fas fa-search-plus"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-4">
                                        <i class="fas fa-fingerprint text-3xl text-slate-200"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">Clear Trails</h3>
                                    <p class="text-slate-500 max-w-xs mx-auto text-sm">No activity logs found matching your current filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-6 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $logs->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
