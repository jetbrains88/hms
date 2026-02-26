@extends('layouts.app')

@section('title', 'User Activity History')

@section('page-title', 'Activity History: ' . $user->name)

@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900 font-bold transition-colors">Users</a>
    <span class="mx-2 text-slate-300">
        <i class="fas fa-chevron-right text-[10px]"></i>
    </span>
    <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 font-bold transition-colors">{{ $user->name }}</a>
    <span class="mx-2 text-slate-300">
        <i class="fas fa-chevron-right text-[10px]"></i>
    </span>
    <span class="text-slate-500 font-medium">Activity Logs</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- User Quick Info -->
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-100">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-slate-500 font-medium">{{ $user->email }}</p>
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-500">USER ID: #{{ $user->id }}</span>
                </div>
            </div>
        </div>
        <div class="hidden md:flex flex-col items-end">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Activities</span>
            <span class="text-2xl font-black text-slate-800">{{ $logs->total() }}</span>
        </div>
    </div>

    <!-- Activity Table -->
    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-lg font-bold text-slate-800">Complete Audit Trail</h3>
            <div class="flex gap-2">
                <button class="p-2 bg-white border border-slate-200 text-slate-400 rounded-xl hover:text-indigo-600 transition-all shadow-sm">
                    <i class="fas fa-download text-xs"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Timestamp</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Action</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Entity & Target</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Branch Context</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Description</th>
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
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm border
                                    @if($log->action == 'created') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @elseif($log->action == 'updated') bg-sky-50 text-sky-600 border-sky-100
                                    @elseif($log->action == 'deleted') bg-rose-50 text-rose-600 border-rose-100
                                    @else bg-slate-50 text-slate-600 border-slate-200 @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                        <i class="fas fa-database text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700">{{ class_basename($log->entity_type) }}</div>
                                        <div class="text-[10px] font-bold text-slate-400">ID: #{{ $log->entity_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fas fa-building text-slate-300 text-xs"></i>
                                    <span class="text-xs font-bold">{{ $log->branch->name ?? 'System Core' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-500 font-medium truncate max-w-xs">
                                    {{ $log->description ?: 'System logged activity' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.audit.show', $log) }}" 
                                   class="inline-flex items-center justify-center h-9 w-9 bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 rounded-xl transition-all">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center mb-4">
                                        <i class="fas fa-history text-3xl text-slate-200"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">Clear Trails</h3>
                                    <p class="text-slate-500 max-w-xs mx-auto text-sm">No activity logs found for this user.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
