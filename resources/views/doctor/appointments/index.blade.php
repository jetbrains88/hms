@extends('layouts.app')

@section('title', 'Appointments')
@section('page-title', 'My Appointments')
@section('breadcrumb', 'Doctor / Appointments')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-lg font-bold text-gray-900">Schedule for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h3>
            <div class="flex items-center gap-3">
                <form action="{{ route('doctor.appointments.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date }}" class="form-input rounded-lg border-gray-300 text-sm">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition-colors">Go</button>
                </form>
                <a href="{{ route('doctor.appointments.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> New
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments ?? [] as $appointment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $appointment->scheduled_at->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $appointment->patient->name }}</div>
                            <div class="text-sm text-gray-500">{{ $appointment->patient->emrn }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->type == 'online' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                <i class="fas {{ $appointment->type == 'online' ? 'fa-video' : 'fa-user-friends' }} mr-1"></i>
                                {{ ucfirst($appointment->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $appointment->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $appointment->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $appointment->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $appointment->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('doctor.appointments.show', $appointment) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <i class="fas fa-calendar-check text-3xl mb-3 text-gray-300"></i>
                            <p>No appointments for this date.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
