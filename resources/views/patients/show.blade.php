@extends('layouts.app')

@section('title', 'Patient Details - ' . $patient->name)
@section('page-title', 'Patient Details')
@section('breadcrumb', 'Patients / ' . $patient->name)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 p-4 md:p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Patient Details</h1>
                        <p class="text-gray-600">Viewing patient information and medical history</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('reception.dashboard') }}"
                           class="bg-blue-red text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Reception
                        </a>

                        <!-- Quick Actions Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                                Actions
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit
                                        Patient Info</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New
                                        Visit</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Medical
                                        History</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Emergency
                                        Alert</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient Summary -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Visits</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $patient->visits->count() }}</p>
                            </div>
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Active Visits</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ $patient->visits->whereIn('status', ['waiting', 'in_progress'])->count() }}
                                </p>
                            </div>
                            <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Last Visit</p>
                                <p class="text-lg font-bold text-gray-800">
                                    @if($patient->visits->count() > 0)
                                        {{ $patient->visits->last()->created_at->diffForHumans() }}
                                    @else
                                        Never
                                    @endif
                                </p>
                            </div>
                            <div class="p-2 bg-green-100 text-green-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Patient Age</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : 'N/A' }}
                                </p>
                            </div>
                            <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient Info Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <span class="text-2xl font-bold text-blue-600">
                            {{ substr($patient->name, 0, 1) }}
                        </span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ $patient->name }}</h2>
                            <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm font-mono bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                {{ $patient->emrn }}
                            </span>
                                @if($patient->is_nhmp)
                                    <span
                                        class="text-sm bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 px-2 py-1 rounded">
                                NHMP Staff
                            </span>
                                @endif
                                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                {{ ucfirst($patient->gender) }}
                            </span>
                                @if($patient->blood_group)
                                    <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded">
                                Blood: {{ $patient->blood_group }}
                            </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Registration Date</p>
                        <p class="font-bold">{{ $patient->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Patient Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-700 border-b pb-2">Personal Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Phone</p>
                                <p class="font-medium">{{ formatPhone($patient->phone) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date of Birth</p>
                                <p class="font-medium">{{ $patient->dob ? $patient->dob->format('d M Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">CNIC</p>
                                <p class="font-medium">{{ $patient->cnic ? formatCNIC($patient->cnic) : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Emergency Contact</p>
                                <p class="font-medium">{{ $patient->emergency_contact ? formatPhone($patient->emergency_contact) : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-700 border-b pb-2">Contact Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Address</p>
                                <p class="font-medium">{{ $patient->address ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Allergies</p>
                                <p class="font-medium">{{ $patient->allergies ?? 'None known' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Chronic Conditions</p>
                                <p class="font-medium">{{ $patient->chronic_conditions ?? 'None' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Medical History</p>
                                <p class="font-medium">{{ $patient->medical_history ?? 'None' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- NHMP Details -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-700 border-b pb-2">NHMP Details</h3>
                        @if($patient->is_nhmp)
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Designation</p>
                                    <p class="font-medium">{{ $patient->designation->title ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Office</p>
                                    <p class="font-medium">{{ $patient->office->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Rank/Grade</p>
                                    <p class="font-medium">{{ $patient->rank ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Cadre Type</p>
                                    <p class="font-medium">{{ $patient->designation->cadre_type ?? 'N/A' }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-gray-500">General Patient</p>
                                <p class="text-sm text-gray-400">Not NHMP staff</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Visits -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-gray-700">Recent Visits</h3>
                    <button onclick="location.href='{{ route('reception.dashboard') }}'"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Visit
                    </button>
                </div>

                @if($patient->visits && $patient->visits->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">Visit Date</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">Queue Token</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">Type</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @foreach($patient->visits->take(10) as $visit)
                                <tr class="hover:bg-blue-50 transition-colors" x-data="{ showActions: false }"
                                    @mouseenter="showActions = true" @mouseleave="showActions = false">
                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ $visit->created_at->format('d M Y') }}</div>
                                        <div
                                            class="text-xs text-gray-500">{{ $visit->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                <span class="font-mono bg-gray-100 px-2 py-1 rounded text-sm">
                                    {{ $visit->queue_token }}
                                </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColors = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                                'waiting' => 'bg-blue-100 text-blue-800',
                                                'cancelled' => 'bg-red-100 text-red-800'
                                            ];
                                            $statusColor = $statusColors[$visit->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                                </span>
                                    </td>
                                    <td class="px-4 py-3">{{ ucfirst($visit->visit_type) }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2"
                                             :class="showActions ? 'opacity-100' : 'opacity-70'">
                                            <!-- View Details -->
                                            <button onclick="viewVisitDetails({{ $visit->id }})"
                                                    class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            <!-- Edit Visit -->
                                            <button onclick="editVisit({{ $visit->id }})"
                                                    class="p-1.5 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Edit Visit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <!-- Print Prescription -->
                                            <button onclick="printPrescription({{ $visit->id }})"
                                                    class="p-1.5 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-colors"
                                                    title="Print Prescription">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                            </button>

                                            <!-- Complete Visit -->
                                            @if(in_array($visit->status, ['waiting', 'in_progress']))
                                                <button onclick="completeVisit({{ $visit->id }})"
                                                        class="p-1.5 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors"
                                                        title="Mark as Completed">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Cancel Visit -->
                                            @if(in_array($visit->status, ['waiting', 'in_progress']))
                                                <button onclick="cancelVisit({{ $visit->id }})"
                                                        class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Cancel Visit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- More Actions Dropdown -->
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open"
                                                        class="p-1.5 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-lg transition-colors"
                                                        title="More Actions">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                    </svg>
                                                </button>

                                                <div x-show="open" @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
                                                    <div class="py-1">
                                                        <a href="#"
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                 viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                            Generate Report
                                                        </a>
                                                        <a href="#"
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                 viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                            </svg>
                                                            Download Details
                                                        </a>
                                                        <div class="border-t border-gray-100 my-1"></div>
                                                        <a href="#"
                                                           class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                 viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Delete Visit
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($patient->visits->count() > 10)
                        <div class="mt-6 flex items-center justify-between">
                            <p class="text-sm text-gray-600">
                                Showing 1 to 10 of {{ $patient->visits->count() }} visits
                            </p>
                            <div class="flex items-center gap-2">
                                <button
                                    class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Previous
                                </button>
                                <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    1
                                </button>
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                    2
                                </button>
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                    Next
                                </button>
                            </div>
                        </div>
                    @endif

                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 text-gray-300 mx-auto mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">No Visits Recorded</h4>
                        <p class="text-gray-500 mb-6">This patient hasn't had any visits yet.</p>
                        <button onclick="location.href='{{ route('reception.dashboard') }}?patient_id={{ $patient->id }}'"
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                            Create First Visit
                        </button>
                    </div>
                @endif
            </div>

            <!-- Medical History Timeline -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-700 mb-6">Medical History Timeline</h3>
                <div class="space-y-6">
                    @if($patient->visits && $patient->visits->count() > 0)
                        @foreach($patient->visits->sortByDesc('created_at')->take(5) as $visit)
                            <div class="flex">
                                <div class="flex flex-col items-center mr-4">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <div class="w-px h-full bg-gray-300 mt-2"></div>
                                </div>
                                <div class="flex-1 pb-6">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-800">
                                                {{ ucfirst($visit->visit_type) }} Consultation
                                            </h4>
                                            <p class="text-sm text-gray-500">{{ $visit->created_at->format('d M Y, h:i A') }}</p>
                                        </div>
                                        <span class="text-sm px-2 py-1 rounded-full {{
                                    $visit->status == 'completed' ? 'bg-green-100 text-green-800' :
                                    ($visit->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                    'bg-blue-100 text-blue-800')
                                }}">
                                    {{ ucfirst($visit->status) }}
                                </span>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">Doctor:</span>
                                                <span
                                                    class="font-medium ml-2">{{ $visit->doctor->name ?? 'N/A' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Token:</span>
                                                <span
                                                    class="font-medium ml-2 font-mono">{{ $visit->queue_token }}</span>
                                            </div>
                                            @if($visit->latestVital)
                                                <div class="col-span-2">
                                                    <span class="text-gray-500">Vitals:</span>
                                                    <span class="font-medium ml-2">
                                            BP: {{ $visit->latestVital->blood_pressure_systolic }}/{{ $visit->latestVital->blood_pressure_diastolic }},
                                            Temp: {{ $visit->latestVital->temperature }}°C,
                                            Pulse: {{ $visit->latestVital->pulse }}
                                        </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>No medical history available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Include Alpine.js for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // Action Functions
        function viewVisitDetails(visitId) {
            // AJAX call to get visit details
            fetch(`/visits/${visitId}`)
                .then(response => response.json())
                .then(data => {
                    // Show modal with visit details
                    alert(`Viewing Visit #${visitId}\nToken: ${data.queue_token}\nStatus: ${data.status}`);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading visit details');
                });
        }

        function editVisit(visitId) {
            window.location.href = `/visits/${visitId}/edit`;
        }

        function printPrescription(visitId) {
            window.open(`/prescriptions/${visitId}/print`, '_blank');
        }

        function completeVisit(visitId) {
            if (confirm('Mark this visit as completed?')) {
                fetch(`/visits/${visitId}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Visit marked as completed!');
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error completing visit');
                    });
            }
        }

        function cancelVisit(visitId) {
            if (confirm('Are you sure you want to cancel this visit?')) {
                fetch(`/visits/${visitId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Visit cancelled successfully!');
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error cancelling visit');
                    });
            }
        }
    </script>
@endsection
