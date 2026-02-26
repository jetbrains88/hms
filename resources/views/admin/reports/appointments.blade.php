@extends('layouts.app')

@section('title', 'Appointment Report')

@section('page-title', 'Appointment Analysis')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form action="{{ route('admin.reports.appointments') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
            <div class="text-slate-500 text-sm font-medium mb-1">Total Appointments</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['total_appointments'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <div class="text-slate-500 text-sm font-medium mb-1">Completion Rate</div>
            <div class="text-2xl font-bold text-green-600">{{ round($stats['completion_rate'], 1) }}%</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <div class="text-slate-500 text-sm font-medium mb-1">No-Show Rate</div>
            <div class="text-2xl font-bold text-rose-600">{{ round($stats['no_show_rate'], 1) }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- by Status -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">By Status</h3>
            <div class="space-y-4">
                @foreach($stats['by_status'] as $status)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="capitalize">{{ str_replace('_', ' ', $status->status) }}</span>
                        <span class="font-semibold">{{ $status->total }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        @php
                            $colors = ['completed' => 'bg-green-500', 'pending' => 'bg-blue-500', 'cancelled' => 'bg-slate-400', 'no_show' => 'bg-rose-500'];
                            $color = $colors[$status->status] ?? 'bg-blue-500';
                        @endphp
                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $stats['total_appointments'] > 0 ? ($status->total / $stats['total_appointments'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Popular Doctors -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Most Booked Doctors</h3>
            <div class="space-y-4">
                @foreach($stats['popular_doctors'] as $doc)
                <div class="flex items-center justify-between">
                    <div class="text-slate-700 font-medium">{{ $doc->doctor->name ?? 'N/A' }}</div>
                    <span class="bg-pink-50 text-pink-700 px-3 py-1 rounded-full text-xs font-bold">{{ $doc->total }} bookings</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
