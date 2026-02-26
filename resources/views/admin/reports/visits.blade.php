@extends('layouts.app')

@section('title', 'Visit Report')

@section('page-title', 'Visit Analysis')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form action="{{ route('admin.reports.visits') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Branch</label>
                <select name="branch_id" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                    <option value="">All Branches</option>
                    @foreach(App\Models\Branch::all() as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <div class="text-slate-500 text-sm font-medium mb-1">Total Visits</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['total_visits'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft text-center">
            <div class="text-slate-500 text-sm font-medium mb-1">Avg Wait Time</div>
            <div class="text-2xl font-bold text-blue-600">{{ round($stats['average_wait_time'], 1) }} mins</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Visits by Type -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">By Visit Type</h3>
            <div class="space-y-4">
                @foreach($stats['by_type'] as $type)
                <div class="flex items-center justify-between">
                    <span class="capitalize text-slate-700">{{ $type->visit_type }}</span>
                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">{{ $type->total }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Doctors -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Top Doctors</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100">
                            <th class="pb-3 font-medium">Doctor Name</th>
                            <th class="pb-3 font-medium text-right">Visits</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($stats['top_doctors'] as $doc)
                        <tr>
                            <td class="py-3 font-medium text-slate-700">{{ $doc->doctor->name ?? 'N/A' }}</td>
                            <td class="py-3 text-right text-blue-600 font-bold">{{ $doc->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
