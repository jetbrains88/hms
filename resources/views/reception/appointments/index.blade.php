@extends('layouts.app')

@section('title', 'Appointments - Reception')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Appointments</h1>
            <p class="text-sm text-slate-500 mt-1">Manage scheduled appointments for {{ now()->format('F d, Y') }}</p>
        </div>
        <a href="{{ route('reception.appointments.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white rounded-xl hover:bg-pink-700 transition-colors shadow-sm">
            <i class="fas fa-plus w-4"></i>
            <span>New Appointment</span>
        </a>
    </div>

    {{-- Date Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <form method="GET" action="{{ route('reception.appointments.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Date</label>
                <input type="date" name="date" value="{{ $date }}"
                       class="px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            </div>
            <button type="submit"
                    class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors text-sm font-medium">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>

    {{-- Appointments Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-700">
                Appointments for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
            </h2>
            <span class="text-sm text-slate-500">{{ $appointments->count() }} total</span>
        </div>

        @if($appointments->isEmpty())
            <div class="text-center py-16">
                <i class="fas fa-calendar-times text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-medium">No appointments scheduled for this date</p>
                <a href="{{ route('reception.appointments.create') }}"
                   class="inline-block mt-4 px-4 py-2 bg-pink-50 text-pink-600 rounded-xl hover:bg-pink-100 transition-colors text-sm">
                    Schedule an appointment
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Time</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Patient</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Doctor</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Notes</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($appointments as $appointment)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($appointment->patient)
                                    <div class="font-semibold text-slate-800">{{ $appointment->patient->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $appointment->patient->opd_number }}</div>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $appointment->doctor ? 'Dr. ' . $appointment->doctor->name : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-blue-100 text-blue-700',
                                        'confirmed' => 'bg-emerald-100 text-emerald-700',
                                        'completed' => 'bg-slate-100 text-slate-600',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'no_show'   => 'bg-amber-100 text-amber-700',
                                    ];
                                    $color = $statusColors[$appointment->status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm max-w-xs truncate">
                                {{ $appointment->notes ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('reception.appointments.show', $appointment) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('reception.appointments.edit', $appointment) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-pink-600 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
