@extends('layouts.app')

@section('title', 'Reception Dashboard')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Reception Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Overview of patient flow and daily operations</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reception.patients.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white rounded-xl hover:bg-pink-700 transition-colors shadow-sm">
                <i class="fas fa-user-plus w-4"></i>
                <span>Register Patient</span>
            </a>
            <a href="{{ route('reception.visits.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors shadow-sm">
                <i class="fas fa-notes-medical w-4"></i>
                <span>New Visit</span>
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Patients Added</p>
                <div class="mt-1 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['patients_today'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Visits</p>
                <div class="mt-1 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['visits_today'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fas fa-walking"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Waiting Room</p>
                <div class="mt-1 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['waiting_count'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">In Progress</p>
                <div class="mt-1 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['in_progress_count'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fas fa-user-md"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Appointments</p>
                <div class="mt-1 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['appointments_today'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Visits --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col h-full">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-history text-indigo-500"></i>
                    Recent Visits (Today)
                </h3>
                <a href="{{ route('reception.visits.create') }}" class="text-sm text-indigo-600 font-medium hover:text-indigo-700">View All</a>
            </div>
            <div class="p-0 overflow-y-auto" style="max-height: 400px;">
                @forelse($recentVisits ?? [] as $visit)
                    <div class="px-5 py-3 border-b border-slate-50 hover:bg-slate-50 transition-colors flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold shrink-0">
                                {{ substr(optional($visit->patient)->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ optional($visit->patient)->name ?? 'Unknown' }}</h4>
                                <div class="text-xs text-slate-500 flex flex-wrap items-center gap-2 mt-0.5">
                                    <span>Token: <strong class="text-slate-700">{{ $visit->queue_token }}</strong></span>
                                    <span>&bull;</span>
                                    <span>{{ $visit->created_at->format('h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            @php
                                $statusColors = [
                                    'waiting' => 'bg-amber-100 text-amber-700',
                                    'in_progress' => 'bg-emerald-100 text-emerald-700',
                                    'completed' => 'bg-slate-100 text-slate-600',
                                ];
                                $color = $statusColors[$visit->status] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 content-center flex-1">
                        <i class="fas fa-folder-open text-3xl mb-3 text-slate-300"></i>
                        <p>No recent visits today.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Appointments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col h-full">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-pink-500"></i>
                    Upcoming Appointments
                </h3>
                <a href="{{ route('reception.appointments.index') }}" class="text-sm text-pink-600 font-medium hover:text-pink-700">View All</a>
            </div>
            <div class="p-0 overflow-y-auto" style="max-height: 400px;">
                @forelse($upcomingAppointments ?? [] as $appointment)
                    <div class="px-5 py-3 border-b border-slate-50 hover:bg-slate-50 transition-colors flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ optional($appointment->patient)->name ?? 'Unknown' }}</h4>
                                <div class="text-xs text-slate-500 flex flex-wrap items-center gap-2 mt-0.5">
                                    <span><i class="fas fa-user-md mr-1 opacity-70"></i> {{ optional($appointment->doctor)->name ?? 'Any Doctor' }}</span>
                                    <span>&bull;</span>
                                    <span class="font-medium text-pink-600">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 content-center flex-1">
                        <i class="fas fa-calendar-check text-3xl mb-3 text-slate-300"></i>
                        <p>No upcoming appointments.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
