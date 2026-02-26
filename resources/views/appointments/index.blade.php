@extends('layouts.app')

@section('title', 'Appointments')

@section('page-title', 'Appointment Calendar')

@section('content')
<div class="bg-white rounded-2xl shadow-soft p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-slate-800">Appointments</h3>
        <a href="{{ route('appointments.create') }}" 
           class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600">
            <i class="fas fa-plus mr-2"></i>New Appointment
        </a>
    </div>
    
    <!-- Date Selector -->
    <div class="flex gap-4 mb-6">
        <form method="GET" class="flex gap-2">
            <input type="date" 
                   name="date" 
                   value="{{ $date }}"
                   class="px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200">
                <i class="fas fa-calendar-alt mr-2"></i>View
            </button>
        </form>
        
        <select class="px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                onchange="window.location.href = '?date={{ $date }}&doctor=' + this.value">
            <option value="">All Doctors</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}" {{ request('doctor') == $doctor->id ? 'selected' : '' }}>
                    Dr. {{ $doctor->name }}
                </option>
            @endforeach
        </select>
    </div>
    
    <!-- Calendar View -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">Time</th>
                    @foreach($doctors as $doctor)
                        <th class="px-4 py-3 text-left text-sm font-semibold text-slate-600">
                            Dr. {{ $doctor->name }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php
                    $startTime = \Carbon\Carbon::parse($date . ' 09:00');
                    $endTime = \Carbon\Carbon::parse($date . ' 17:00');
                @endphp
                
                @while($startTime < $endTime)
                <tr>
                    <td class="px-4 py-3 text-sm text-slate-500 font-medium">
                        {{ $startTime->format('H:i') }}
                    </td>
                    
                    @foreach($doctors as $doctor)
                        @php
                            $appointment = $appointments
                                ->where('doctor_id', $doctor->id)
                                ->where('scheduled_at', '>=', $startTime)
                                ->where('scheduled_at', '<', $startTime->copy()->addMinutes(30))
                                ->first();
                        @endphp
                        
                        <td class="px-4 py-2">
                            @if($appointment)
                                <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-2">
                                    <div class="font-medium text-sm">{{ $appointment->patient->name }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs px-2 py-1 bg-white rounded-full
                                            @if($appointment->type == 'online') text-purple-600 @else text-green-600 @endif">
                                            <i class="fas fa-{{ $appointment->type == 'online' ? 'video' : 'hospital' }} mr-1"></i>
                                            {{ ucfirst($appointment->type) }}
                                        </span>
                                        <span class="text-xs px-2 py-1 bg-white rounded-full
                                            @if($appointment->status == 'scheduled') text-amber-600
                                            @elseif($appointment->status == 'confirmed') text-green-600
                                            @elseif($appointment->status == 'cancelled') text-red-600
                                            @endif">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('appointments.show', $appointment) }}" 
                                       class="text-xs text-blue-600 hover:underline mt-1 inline-block">
                                        View Details
                                    </a>
                                </div>
                            @else
                                <div class="h-16"></div>
                            @endif
                        </td>
                    @endforeach
                </tr>
                @php $startTime->addMinutes(30); @endphp
                @endwhile
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh calendar every 5 minutes
    setTimeout(function() {
        window.location.reload();
    }, 300000);
</script>
@endpush