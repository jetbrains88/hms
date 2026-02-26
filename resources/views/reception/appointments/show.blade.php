@extends('layouts.app')

@section('title', 'Appointment Details - Reception')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('reception.appointments.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-400 hover:text-pink-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Appointment Details</h1>
                <p class="text-sm text-slate-500 mt-1">Token: <span class="font-mono font-bold text-pink-600">{{ $appointment->token ?? 'N/A' }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reception.appointments.edit', $appointment) }}" class="px-4 py-2 bg-pink-50 text-pink-600 rounded-xl hover:bg-pink-100 transition-colors text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors text-sm font-medium">
                <i class="fas fa-print mr-2"></i>Print
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="md:col-span-2 space-y-6">
            {{-- Patient Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-user-injured text-pink-500"></i>
                        Patient Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 text-2xl font-bold">
                            {{ strtoupper(substr($appointment->patient->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-slate-800">{{ $appointment->patient->name }}</h3>
                            <p class="text-sm text-slate-500">OPD: {{ $appointment->patient->opd_number }}</p>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">Phone</span>
                                    <span class="text-sm text-slate-700 font-medium">{{ $appointment->patient->phone }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold">CNIC</span>
                                    <span class="text-sm text-slate-700 font-medium">{{ $appointment->patient->cnic ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Appointment Details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-indigo-500"></i>
                        Appointment Details
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Scheduled Time</span>
                            <div class="flex items-center gap-2 text-slate-700">
                                <i class="far fa-clock text-slate-400"></i>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('F d, Y \a\t H:i') }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Doctor</span>
                            <div class="flex items-center gap-2 text-slate-700">
                                <i class="fas fa-user-md text-slate-400"></i>
                                <span class="font-semibold">Dr. {{ $appointment->doctor->name }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Type</span>
                            <span class="inline-flex px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold uppercase">
                                {{ $appointment->type ?? 'General' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Fee Collection</span>
                            <span class="text-sm font-semibold {{ $appointment->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ ucfirst($appointment->payment_status ?? 'Pending') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Reason for Visit</span>
                        <div class="p-4 bg-slate-50 rounded-xl text-sm text-slate-600 italic">
                            "{{ $appointment->reason ?? 'No reason provided' }}"
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Stats/Actions --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center">
                <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-3">Current Status</span>
                @php
                    $statusColors = [
                        'scheduled' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'confirmed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'in_progress' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'completed' => 'bg-slate-100 text-slate-600 border-slate-200',
                        'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                        'no_show' => 'bg-slate-100 text-slate-500 border-slate-200',
                    ];
                    $color = $statusColors[$appointment->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                @endphp
                <div class="inline-flex items-center px-4 py-2 rounded-2xl border {{ $color }} text-lg font-black uppercase tracking-tight">
                    {{ str_replace('_', ' ', $appointment->status) }}
                </div>
                
                <form action="{{ route('reception.appointments.update-status', $appointment) }}" method="POST" class="mt-6 space-y-2">
                    @csrf
                    <label class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-2">Update Status</label>
                    <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-500">
                        <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ $appointment->status == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                </form>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-700 text-sm mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if($appointment->status !== 'cancelled')
                    <form action="{{ route('reception.appointments.cancel', $appointment) }}" method="POST">
                        @csrf
                        <input type="hidden" name="reason" value="Cancelled by Reception">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-red-100 text-red-600 rounded-xl hover:bg-red-50 transition-colors text-xs font-bold uppercase">
                            Cancel Appointment
                        </button>
                    </form>
                    @endif
                    
                    @if($appointment->status === 'confirmed' || $appointment->status === 'scheduled')
                    <a href="{{ route('reception.visits.create', ['patient' => $appointment->patient_id]) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors text-xs font-bold uppercase shadow-lg shadow-indigo-600/20">
                        Start Visit Now
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
