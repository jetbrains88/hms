@extends('layouts.app')

@section('title', 'Laboratory Report')

@section('page-title', 'Laboratory Analysis')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form action="{{ route('admin.reports.laboratory') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <div class="text-slate-500 text-sm font-medium mb-1">Total Lab Orders</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <div class="text-slate-500 text-sm font-medium mb-1">Avg Processing Time</div>
            <div class="text-2xl font-bold text-purple-600">{{ round($stats['avg_processing_time'], 1) }} hours</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <div class="text-slate-500 text-sm font-medium mb-1">Abnormal Rate</div>
            <div class="text-2xl font-bold text-rose-600">{{ round($stats['abnormal_percentage'], 1) }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Tests -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Most Popular Tests</h3>
            <div class="space-y-4">
                @foreach($stats['top_tests'] as $test)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="font-medium text-slate-700">{{ $test->name }}</div>
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">{{ $test->total }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Orders by Status -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Orders by Status</h3>
            <div class="space-y-4">
                @foreach($stats['by_status'] as $status)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="capitalize">{{ $status->status }}</span>
                        <span class="font-semibold">{{ $status->total }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $stats['total_orders'] > 0 ? ($status->total / $stats['total_orders'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
