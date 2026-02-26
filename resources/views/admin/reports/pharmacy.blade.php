@extends('layouts.app')

@section('title', 'Pharmacy Report')

@section('page-title', 'Pharmacy & Inventory Analysis')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form action="{{ route('admin.reports.pharmacy') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
        <div class="bg-white p-6 rounded-2xl shadow-soft card-hover">
            <div class="text-slate-500 text-sm font-medium mb-1">Total Prescriptions</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['total_prescriptions'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft card-hover">
            <div class="text-slate-500 text-sm font-medium mb-1">Total Dispensed</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['total_dispensed'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft card-hover">
            <div class="text-slate-500 text-sm font-medium mb-1">Inventory Value</div>
            <div class="text-2xl font-bold text-amber-600">PKR {{ number_format($stats['stock_value'], 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Medicines -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Top Prescribed Medicines</h3>
            <div class="space-y-4">
                @foreach($stats['top_medicines'] as $med)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="font-medium text-slate-700">{{ $med->medicine->name ?? 'Unknown' }}</div>
                    <div class="text-sm font-bold text-blue-600">{{ $med->total_quantity }} Units</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Inventory Movements -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Inventory Movements</h3>
            <div class="space-y-4">
                @foreach($stats['inventory_movements'] as $move)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="capitalize">{{ str_replace('_', ' ', $move->type) }}</span>
                        <span class="font-semibold">{{ $move->total_quantity }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        @php $color = $move->type == 'stock_in' ? 'bg-green-500' : 'bg-rose-500'; @endphp
                        <div class="{{ $color }} h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
