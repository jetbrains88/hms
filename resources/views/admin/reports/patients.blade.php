@extends('layouts.app')

@section('title', 'Patient Report')

@section('page-title', 'Patient Demographics & Trends')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft p-6">
        <form action="{{ route('admin.reports.patients') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 font-medium transition-colors">
                    Filter
                </button>
                <button type="submit" name="export" value="1" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-soft border-l-4 border-blue-500">
            <div class="text-slate-500 text-sm font-medium mb-1">Total Patients</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['total_patients'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft border-l-4 border-green-500">
            <div class="text-slate-500 text-sm font-medium mb-1">New Today</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['new_patients'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft border-l-4 border-purple-500">
            <div class="text-slate-500 text-sm font-medium mb-1">NHMP Staff/Family</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['nhmp_patients'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-soft border-l-4 border-amber-500">
            <div class="text-slate-500 text-sm font-medium mb-1">Dependents</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['dependents'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gender Distribution -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">By Gender</h3>
            <div class="space-y-4">
                @foreach($stats['by_gender'] as $gender)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="capitalize">{{ $gender->gender }}</span>
                        <span class="font-semibold">{{ $gender->total }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $stats['total_patients'] > 0 ? ($gender->total / $stats['total_patients'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Blood Group Distribution -->
        <div class="bg-white p-6 rounded-2xl shadow-soft">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">By Blood Group</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($stats['by_blood_group'] as $bg)
                <div class="p-4 bg-slate-50 rounded-xl text-center">
                    <div class="text-rose-600 font-bold text-lg">{{ $bg->blood_group }}</div>
                    <div class="text-slate-500 text-xs">{{ $bg->total }} Patients</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
