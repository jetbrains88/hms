@extends('layouts.app')

@section('title', 'Audit Report')

@section('page-title', 'System Audit Analysis')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form action="{{ route('admin.reports.audit') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">User</label>
                <select name="user_id" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                    <option value="">All Users</option>
                    @foreach(App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 font-medium transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-soft text-center">
            <div class="text-slate-500 text-sm font-medium mb-1">Total System Actions</div>
            <div class="text-3xl font-bold text-slate-800">{{ $stats['total_actions'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-sm font-medium text-slate-500 mb-4">Top Active Users</h3>
            <div class="flex gap-2 flex-wrap">
                @foreach($stats['by_user'] as $user)
                    @php $u = App\Models\User::find($user->user_id); @endphp
                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-medium">
                        {{ $u->name ?? 'User '.$user->user_id }} ({{ $user->total }})
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- by Action -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Actions by Type</h3>
            <div class="space-y-4">
                @foreach($stats['by_action'] as $action)
                <div class="flex items-center justify-between">
                    <span class="capitalize text-slate-700 font-medium">{{ str_replace('_', ' ', $action->action) }}</span>
                    <span class="text-blue-600 font-bold">{{ $action->total }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- by Entity -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Affected Entities</h3>
            <div class="space-y-4">
                @foreach($stats['by_entity'] as $entity)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="capitalize text-slate-600">{{ class_basename($entity->entity_type) }}</span>
                        <span class="font-semibold">{{ $entity->total }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-slate-500 h-2 rounded-full" style="width: {{ $stats['total_actions'] > 0 ? ($entity->total / $stats['total_actions'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
