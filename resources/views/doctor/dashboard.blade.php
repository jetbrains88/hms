@extends('layouts.app')

@section('title', 'Doctor Dashboard')
@section('page-title', 'Doctor Dashboard')
@section('page-description', 'Overview of your medical practice')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Today's Patients</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['total_patients_today'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-blue-500">
                    <i class="fas fa-arrow-up mr-1"></i>
                    {{ $stats['waiting_patients'] ?? 0 }} waiting
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">In Progress</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">{{ $stats['in_progress_patients'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-clock text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-green-500">
                    <i class="fas fa-clock mr-1"></i>
                    Active consultations
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Avg. Time</p>
                        <p class="text-3xl font-bold text-purple-900 mt-2">{{ $stats['average_consultation_time'] ?? 0 }}
                            m</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-stopwatch text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-purple-500">
                    <i class="fas fa-chart-line mr-1"></i>
                    Per consultation
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-600">Prescriptions</p>
                        <p class="text-3xl font-bold text-orange-900 mt-2">{{ $stats['prescriptions_today'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-prescription text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-orange-500">
                    <i class="fas fa-pills mr-1"></i>
                    Today's count
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                <div class="flex space-x-3">
                    <a href="{{ route('doctor.consultancy') }}"
                       class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white font-bold  rounded-lg hover:from-indigo-950 hover:to-indigo-600 hover:text-white transition-colors">
                        <i class="fas fa-stethoscope mr-2"></i>
                        Start Consultation
                    </a>
                    <a href="{{ route('doctor.e-consultancy') }}"
                       class="px-4 py-2 bg-gradient-to-r from-teal-600 to-green-500 text-white font-bold  rounded-lg hover:from-teal-950 hover:to-green-600 hover:text-white transition-colors">
                        <i class="fas fa-video mr-2"></i>
                        E-Consultation
                    </a>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="space-y-4">
                <h4 class="font-medium text-gray-700 border-b pb-2">Today's Consultations</h4>
                @if($today_visits->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @foreach($today_visits as $visit)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $visit->patient->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $visit->patient->emrn }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $visit->created_at->format('h:i A') }}
                                    </td>
                                    <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $visit->status == 'waiting' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $visit->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $visit->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                    </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($visit->status == 'waiting')
                                            <button onclick="startConsultation({{ $visit->id }})"
                                                    class="text-blue-600 hover:text-blue-900 text-sm font-bold">
                                                <i class="fas fa-play-circle mr-1"></i>
                                                Start
                                            </button>
                                        @elseif($visit->status == 'in_progress')
                                            <a href="{{ route('doctor.consultation.view', $visit->id) }}"
                                               class="text-green-600 hover:text-green-900 text-sm font-bold">
                                                <i class="fas fa-notes-medical mr-1"></i>
                                                Continue
                                            </a>
                                        @else
                                            <a href="{{ route('doctor.consultation.view', $visit->id) }}"
                                               class="text-gray-600 hover:text-gray-900 text-sm font-bold">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-times text-3xl mb-3"></i>
                        <p>No consultations scheduled for today</p>
                    </div>
                @endif
            </div>

            <!-- Recent Patients -->
            <div class="mt-8">
                <h4 class="font-medium text-gray-700 border-b pb-2 mb-4">Recent Patients</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recent_patients as $patient)
                        <div
                            class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $patient->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $patient->age_formatted }} • {{ $patient->gender }}
                                        @if($patient->is_nhmp)
                                            <span
                                                class="ml-2 px-1.5 py-0.5 bg-green-100 text-green-800 text-xs rounded">NHMP</span>
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('doctor.consultancy') }}?search={{ $patient->emrn }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            @if($patient->lastVisit)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-600">
                                        Last visit: {{ $patient->lastVisit->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function startConsultation(visitId) {
            fetch(`/doctor/consultation/${visitId}/start`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Consultation started successfully', 'success');
                        setTimeout(() => {
                            window.location.href = `/doctor/consultation/${visitId}`;
                        }, 1000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to start consultation', 'error');
                });
        }
    </script>
@endsection
