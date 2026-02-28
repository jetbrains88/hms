@extends('layouts.app')

@section('title', 'Consultation')
@section('page-title', 'Consultation - ' . $visit->patient->name)
@section('page-description', 'Patient consultation and diagnosis')

@section('content')
    <div class="space-y-6">
        <!-- Patient Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-injured  text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $visit->patient->name }}</h2>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-id-card mr-1"></i>
                                {{ $visit->patient->emrn }}
                            </span>
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-birthday-cake mr-1"></i>
                                {{ $visit->patient->age_formatted }}
                            </span>
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-venus-mars mr-1"></i>
                                {{ $visit->patient->gender }}
                            </span>
                            @if ($visit->patient->is_nhmp)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    NHMP
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-sm text-gray-500">Visit Time</div>
                    <div class="text-lg font-bold text-gray-900">
                        {{ $visit->created_at->format('h:i A') }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $visit->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>

            <!-- Patient Quick Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="text-sm font-medium text-blue-700 mb-1">Contact</div>
                    <div class="text-gray-900">{{ $visit->patient->phone }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ $visit->patient->address }}</div>
                </div>

                <div class="p-4 bg-yellow-50 rounded-lg">
                    <div class="text-sm font-medium text-yellow-700 mb-1">Medical Info</div>
                    <div class="space-y-1">
                        <div class="text-sm">
                            <span class="text-gray-600">Blood Group:</span>
                            <span class="font-medium ml-2">{{ $visit->patient->blood_group ?? 'Not specified' }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-600">Allergies:</span>
                            <span class="font-medium ml-2">{{ $visit->patient->allergies ?? 'None' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-green-50 rounded-lg">
                    <div class="text-sm font-medium text-green-700 mb-1">Chronic Conditions</div>
                    <div class="text-gray-900">{{ $visit->patient->chronic_conditions ?? 'None' }}</div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Vitals and Medical History -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Vitals -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Vitals</h3>
                    @if ($visit->latestVital)
                        {{-- Vitals grid start --}}
                        <div class="space-y-4">
                            <div class="mt-2 grid grid-cols-5 gap-2 text-xs">
                                <!-- Temperature with Smart Coloring -->
                                <div class="relative group">
                                    <div
                                        class="bg-gray-50 p-1 rounded text-center cursor-help
                                {{ $visit->latestVital->temperature >= 100.4
                                    ? 'border border-red-200 bg-red-50'
                                    : ($visit->latestVital->temperature >= 99.5
                                        ? 'border border-yellow-200 bg-yellow-50'
                                        : '') }}">
                    <span
                        class="font-bold
                            {{ $visit->latestVital->temperature >= 100.4
                                ? 'text-red-700'
                                : ($visit->latestVital->temperature >= 99.5
                                    ? 'text-yellow-700'
                                    : 'text-gray-700') }}">
                        {{ $visit->latestVital->temperature ?? '--' }}°
                    </span>
                                            <!-- Dynamic trend indicator -->
                                            @if ($visit->latestVital->temperature)
                                                <i
                                                    class="fas fa-thermometer-half ml-1
                                            {{ $visit->latestVital->temperature >= 100.4
                                ? 'text-red-500'
                                : ($visit->latestVital->temperature >= 99.5
                                    ? 'text-yellow-500'
                                    : 'text-blue-400') }}"></i>
                                            @endif
                                            <div class="text-gray-500">Temp</div>
                                        </div>
                                        <!-- Hover tooltip -->
                                        <div
                                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                                            {{ $visit->latestVital->temperature >= 100.4 ? 'Fever' : ($visit->latestVital->temperature >= 99.5 ? 'Elevated' : 'Normal') }}
                                        </div>
                                    </div>

                                    <!-- Oxygen Saturation with Pulse Oximeter Design -->
                                    <div class="relative group">
                                        <div
                                            class="bg-gray-50 p-1 rounded text-center cursor-help
                        {{ $visit->latestVital->oxygen_saturation <= 92
                            ? 'border border-red-200 bg-red-50'
                            : ($visit->latestVital->oxygen_saturation <= 95
                                ? 'border border-yellow-200 bg-yellow-50'
                                : '') }}">
                    <span
                        class="font-bold
                        {{ $visit->latestVital->oxygen_saturation <= 92
                            ? 'text-red-700'
                            : ($visit->latestVital->oxygen_saturation <= 95
                                ? 'text-yellow-700'
                                : 'text-gray-700') }}">
                        {{ $visit->latestVital->oxygen_saturation ?? '--' }}
                    </span>
                                            <!-- Oxygen icon -->
                                            @if ($visit->latestVital->oxygen_saturation)
                                                <i
                                                    class="fas fa-lungs ml-1
                            {{ $visit->latestVital->oxygen_saturation <= 92
                                ? 'text-red-500'
                                : ($visit->latestVital->oxygen_saturation <= 95
                                    ? 'text-yellow-500'
                                    : 'text-green-400') }}"></i>
                                            @endif
                                            <div class="text-gray-500">SpO₂</div>
                                        </div>
                                    </div>

                                    <!-- Pulse with Heartbeat Animation -->
                                    <div class="relative group">
                                        <div
                                            class="bg-gray-50 p-1 rounded text-center cursor-help
                    {{ $visit->latestVital->pulse >= 120
                        ? 'border border-red-200 bg-red-50'
                        : ($visit->latestVital->pulse >= 100
                            ? 'border border-amber-200 bg-amber-50'
                            : 'border border-emerald-200 bg-emerald-50') }}">
                    <span
                        class="font-bold flex items-center justify-center
                            {{ $visit->latestVital->pulse >= 120
                                ? 'text-red-700'
                                : ($visit->latestVital->pulse >= 100
                                    ? 'text-amber-700'
                                    : 'text-emerald-700') }}">
                        {{ $visit->latestVital->pulse ?? '--' }}
                        <!-- Animated heartbeat for high pulse -->
                        @if ($visit->latestVital->pulse >= 100)
                            <i
                                class="fas fa-heartbeat ml-1
                                {{ $visit->latestVital->pulse >= 120 ? 'text-red-500 animate-pulse' : 'text-amber-500 animate-pulse' }}"></i>
                        @elseif($visit->latestVital->pulse)
                            <i class="fas fa-heart-pulse ml-1 text-emerald-400 animate-pulse"></i>
                        @endif
                    </span>
                                            <div class="text-gray-500">Pulse</div>
                                        </div>
                                    </div>

                                    <!-- Blood Pressure with Combined Display -->
                                    <div class="relative group">
                                        <div
                                            class="bg-gray-50 p-1 rounded text-center cursor-help
                    {{ $visit->latestVital->blood_pressure_systolic >= 140 || $visit->latestVital->blood_pressure_diastolic >= 90
                        ? 'border border-red-200 bg-red-50'
                        : ($visit->latestVital->blood_pressure_systolic >= 130 || $visit->latestVital->blood_pressure_diastolic >= 85
                            ? 'border border-yellow-200 bg-yellow-50'
                            : '') }}">
                    <span
                        class="font-bold
                        {{ $visit->latestVital->blood_pressure_systolic >= 140 || $visit->latestVital->blood_pressure_diastolic >= 90
                            ? 'text-red-700'
                            : ($visit->latestVital->blood_pressure_systolic >= 130 || $visit->latestVital->blood_pressure_diastolic >= 85
                                ? 'text-yellow-700'
                                : 'text-gray-700') }}">
                        {{ $visit->latestVital->blood_pressure_systolic ?? '--' }}/{{ $visit->latestVital->blood_pressure_diastolic ?? '--' }}
                    </span>
                                            <div class="text-gray-500">BP</div>
                                        </div>
                                        <!-- BP Classification Tooltip -->
                                        <div
                                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded">
                                            <div>Systolic/Diastolic</div>
                                            @if ($visit->latestVital->blood_pressure_systolic && $visit->latestVital->blood_pressure_diastolic)
                                                <div
                                                    class="{{ $visit->latestVital->blood_pressure_systolic >= 140 || $visit->latestVital->blood_pressure_diastolic >= 90
                                ? 'text-red-300'
                                : ($visit->latestVital->blood_pressure_systolic >= 130 || $visit->latestVital->blood_pressure_diastolic >= 85
                                    ? 'text-yellow-300'
                                    : 'text-green-300') }}">
                                                    {{ $visit->latestVital->blood_pressure_systolic >= 140 || $visit->latestVital->blood_pressure_diastolic >= 90
                                                        ? 'Hypertensive'
                                                        : ($visit->latestVital->blood_pressure_systolic >= 130 || $visit->latestVital->blood_pressure_diastolic >= 85
                                                            ? 'Elevated'
                                                            : 'Normal') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Respiratory Rate -->
                                    <div class="relative group">
                                        <div
                                            class="bg-gray-50 p-1 rounded text-center cursor-help
                        {{ $visit->latestVital->respiratory_rate >= 24 || $visit->latestVital->respiratory_rate <= 12
                            ? 'border border-red-200 bg-red-50'
                            : ($visit->latestVital->respiratory_rate >= 20 || $visit->latestVital->respiratory_rate <= 14
                                ? 'border border-yellow-200 bg-yellow-50'
                                : '') }}">
                    <span
                        class="font-bold
                            {{ $visit->latestVital->respiratory_rate >= 24 || $visit->latestVital->respiratory_rate <= 12
                                ? 'text-red-700'
                                : ($visit->latestVital->respiratory_rate >= 20 || $visit->latestVital->respiratory_rate <= 14
                                    ? 'text-yellow-700'
                                    : 'text-gray-700') }}">
                        {{ $visit->latestVital->respiratory_rate ?? '--' }}
                    </span>
                                            <!-- Respiratory icon -->
                                            @if ($visit->latestVital->respiratory_rate)
                                                <i
                                                    class="fas fa-wind ml-1
                                {{ $visit->latestVital->respiratory_rate >= 24 || $visit->latestVital->respiratory_rate <= 12
                                    ? 'text-red-500'
                                    : ($visit->latestVital->respiratory_rate >= 20 || $visit->latestVital->respiratory_rate <= 14
                                        ? 'text-yellow-500'
                                        : 'text-blue-400') }}"></i>
                                            @endif
                                            <div class="text-gray-500">Resp</div>
                                        </div>
                                        <!-- Tooltip -->
                                        <div
                                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                                            {{ $visit->latestVital->respiratory_rate >= 24 ? 'Tachypnea' : ($visit->latestVital->respiratory_rate <= 12 ? 'Bradypnea' : ($visit->latestVital->respiratory_rate >= 20 || $visit->latestVital->respiratory_rate <= 14 ? 'Abnormal' : 'Normal')) }}
                                        </div>
                                    </div>

                                    <!-- Pain Scale -->
                                    <div class="relative group">
                                        <div
                                            class="bg-gray-50 p-1 rounded text-center cursor-help
                        {{ $visit->latestVital->pain_scale >= 7
                            ? 'border border-red-200 bg-red-50'
                            : ($visit->latestVital->pain_scale >= 4
                                ? 'border border-yellow-200 bg-yellow-50'
                                : '') }}">
                    <span
                        class="font-bold
                            {{ $visit->latestVital->pain_scale >= 7
                                ? 'text-red-700'
                                : ($visit->latestVital->pain_scale >= 4
                                    ? 'text-yellow-700'
                                    : 'text-gray-700') }}">
                        {{ $visit->latestVital->pain_scale ?? '--' }}
                    </span>
                                            <!-- Pain icon -->
                                            @if ($visit->latestVital->pain_scale)
                                                <i
                                                    class="fas fa-head-side-virus ml-1
                                {{ $visit->latestVital->pain_scale >= 7
                                    ? 'text-red-500'
                                    : ($visit->latestVital->pain_scale >= 4
                                        ? 'text-yellow-500'
                                        : 'text-green-400') }}"></i>
                                            @endif
                                            <div class="text-gray-500">Pain</div>
                                        </div>
                                        <!-- Tooltip -->
                                        <div
                                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                                            {{ $visit->latestVital->pain_scale >= 7 ? 'Severe' : ($visit->latestVital->pain_scale >= 4 ? 'Moderate' : ($visit->latestVital->pain_scale >= 1 ? 'Mild' : 'No pain')) }}
                                        </div>
                                    </div>

                                    <!-- Height -->
                                    <div class="relative group">
                                        <div class="bg-gray-50 p-1 rounded text-center cursor-help">
                    <span class="font-bold text-gray-700">
                        {{ $visit->latestVital->height ? round($visit->latestVital->height) : '--' }}
                    </span>
                                            <!-- Height icon -->
                                            @if ($visit->latestVital->height)
                                                <i class="fas fa-ruler-vertical ml-1 text-gray-500"></i>
                                            @endif
                                            <div class="text-gray-500">Height</div>
                                        </div>
                                        <!-- Tooltip -->
                                        <div
                                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                                            {{ $visit->latestVital->height ? $visit->latestVital->height . ' cm' : 'Not recorded' }}
                                        </div>
                                    </div>

                                    <!-- Weight -->
                                    <div class="relative group">
                                        <div class="bg-gray-50 p-1 rounded text-center cursor-help">
                    <span class="font-bold text-gray-700">
                        {{ $visit->latestVital->weight ? round($visit->latestVital->weight) : '--' }}
                    </span>
                                            <!-- Weight icon -->
                                            @if ($visit->latestVital->weight)
                                                <i class="fas fa-weight ml-1 text-gray-500"></i>
                                            @endif
                                            <div class="text-gray-500">Weight</div>
                                        </div>
                                        <!-- Tooltip -->
                                        <div
                                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                                            {{ $visit->latestVital->weight ? $visit->latestVital->weight . ' kg' : 'Not recorded' }}
                                        </div>
                                    </div>

                                    <!-- BMI with Categories -->
                                    <div class="relative group">
                                        <div
                                            class="bg-gray-50 p-1 rounded text-center cursor-help
                        {{ $visit->latestVital->bmi >= 30
                            ? 'border border-red-200 bg-red-50'
                            : ($visit->latestVital->bmi >= 25
                                ? 'border border-yellow-200 bg-yellow-50'
                                : ($visit->latestVital->bmi < 18.5
                                    ? 'border border-yellow-200 bg-yellow-50'
                                    : '')) }}">
                    <span
                        class="font-bold
                            {{ $visit->latestVital->bmi >= 30
                                ? 'text-red-700'
                                : ($visit->latestVital->bmi >= 25
                                    ? 'text-yellow-700'
                                    : ($visit->latestVital->bmi < 18.5
                                        ? 'text-yellow-700'
                                        : 'text-gray-700')) }}">
                        {{ $visit->latestVital->bmi ?? '--' }}
                    </span>
                                            <!-- BMI icon -->
                                            @if ($visit->latestVital->bmi)
                                                <i
                                                    class="fas fa-balance-scale ml-1
                                {{ $visit->latestVital->bmi >= 30
                                    ? 'text-red-500'
                                    : ($visit->latestVital->bmi >= 25
                                        ? 'text-yellow-500'
                                        : ($visit->latestVital->bmi < 18.5
                                            ? 'text-yellow-500'
                                            : 'text-green-400')) }}"></i>
                                            @endif
                                            <div class="text-gray-500">BMI</div>
                                        </div>
                                        <!-- Tooltip with BMI classification -->
                                        <div
                                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">
                                            @if ($visit->latestVital->bmi)
                                                {{ $visit->latestVital->bmi >= 30 ? 'Obese' : ($visit->latestVital->bmi >= 25 ? 'Overweight' : ($visit->latestVital->bmi >= 18.5 ? 'Normal' : 'Underweight')) }}
                                            @else
                                                Not calculated
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Blood Glucose with Contextual Display -->
                                    <div class="relative group">
                                        <div
                                            class="bg-gray-50 p-1 rounded text-center cursor-help
                        {{ $visit->latestVital->blood_glucose >= 200
                            ? 'border border-red-200 bg-red-50'
                            : ($visit->latestVital->blood_glucose >= 140
                                ? 'border border-yellow-200 bg-yellow-50'
                                : '') }}">
                    <span
                        class="font-bold
                            {{ $visit->latestVital->blood_glucose >= 200
                                ? 'text-red-700'
                                : ($visit->latestVital->blood_glucose >= 140
                                    ? 'text-yellow-700'
                                    : 'text-gray-700') }}">
                        {{ $visit->latestVital->blood_glucose ?? '--' }}
                    </span>
                                            <!-- Glucose-specific icon -->
                                            @if ($visit->latestVital->blood_glucose)
                                                <i
                                                    class="fas fa-tint ml-1
                                {{ $visit->latestVital->blood_glucose >= 200
                                    ? 'text-red-500'
                                    : ($visit->latestVital->blood_glucose >= 140
                                        ? 'text-yellow-500'
                                        : 'text-green-400') }}"></i>
                                            @endif
                                            <div class="text-gray-500">BGL</div>
                                        </div>
                                        <!-- Enhanced tooltip -->
                                        <div
                                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded min-w-max">
                                            <div>Blood Glucose</div>
                                            @if ($visit->latestVital->blood_glucose)
                                                <div
                                                    class="{{ $visit->latestVital->blood_glucose >= 200
                                ? 'text-red-300'
                                : ($visit->latestVital->blood_glucose >= 140
                                    ? 'text-yellow-300'
                                    : 'text-green-300') }}">
                                                    {{ $visit->latestVital->blood_glucose >= 200
                                                        ? 'Hyperglycemic'
                                                        : ($visit->latestVital->blood_glucose >= 140
                                                            ? 'Elevated'
                                                            : 'Normal') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                            </div>
                        </div>
                        {{-- Vitals grid end --}}
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <i class="fas fa-heartbeat text-3xl mb-3"></i>
                            <p>No vitals recorded</p>
                            <button onclick="showVitalsModal()"
                                    class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>
                                Record Vitals
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Medical History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Medical History</h3>
                        <button onclick="loadMedicalHistory({{ $visit->patient->id }})"
                                class="text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-sync-alt mr-1"></i>
                            Refresh
                        </button>
                    </div>
                    <div id="medicalHistory" class="space-y-3">
                        <!-- History will be loaded via AJAX -->
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p class="mt-2">Loading history...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Diagnosis and Prescriptions -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Diagnosis Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add Diagnosis</h3>
                    <form id="diagnosisForm">
                        @csrf
                        <input type="hidden" name="visit_id" value="{{ $visit->id }}">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis *</label>
                                <textarea name="diagnosis" rows="3" required placeholder="Enter primary diagnosis..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Severity *</label>
                                    <select name="severity" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Severity</option>
                                        <option value="mild">Mild</option>
                                        <option value="moderate">Moderate</option>
                                        <option value="severe">Severe</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Date</label>
                                    <input type="date" name="follow_up_date"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Doctor's Notes</label>
                                <textarea name="doctor_notes" rows="2"
                                          placeholder="Additional notes, observations, or recommendations..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_chronic" value="1"
                                           class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Chronic Condition</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_urgent" value="1"
                                           class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Urgent Case</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="has_prescription" value="1"
                                           class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Requires Prescription</span>
                                </label>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4 border-t">
                                <button type="button" onclick="resetDiagnosisForm()"
                                        class="px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-500 text-white font-bold rounded-lg hover:from-gray-950 hover:to-gray-600 hover:text-white transition-all duration-300 flex items-center gap-2 group">
                                    <i class="fas fa-eraser group-hover:rotate-12 transition-transform duration-300"></i>
                                    Clear
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-bold  rounded-lg hover:from-blue-950 hover:to-blue-600 hover:text-white transition-colors">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Diagnosis
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Diagnoses List -->
                <div id="diagnosesSection" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Diagnoses</h3>
                        <span class="text-sm text-gray-500" id="diagnosisCount">
                            {{ $visit->diagnoses->count() }} diagnosis
                        </span>
                    </div>

                    <div id="diagnosesList" class="space-y-4">
                        @if ($visit->diagnoses->count() > 0)
                            @foreach ($visit->diagnoses as $diagnosis)
                                @include('doctor.partials.diagnosis-item', ['diagnosis' => $diagnosis])
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-notes-medical text-3xl mb-3"></i>
                                <p>No diagnoses added yet</p>
                                <p class="text-sm mt-1">Add your first diagnosis above</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Prescription Form (Initially Hidden) -->
                <div id="prescriptionForm" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Add Prescription</h3>
                        <button onclick="closePrescriptionForm()"
                                class="px-4 py-2 bg-gradient-to-r from-teal-600 to-green-500 text-white font-bold  rounded-lg hover:from-teal-950 hover:to-green-600 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="addPrescriptionForm">
                        @csrf
                        <input type="hidden" id="prescriptionDiagnosisId" name="diagnosis_id">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medicine *</label>
                                <select name="medicine_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Medicine</option>
                                    @foreach ($medicines as $medicine)
                                        <option value="{{ $medicine->id }}" data-stock="{{ $medicine->stock }}">
                                            {{ $medicine->name }} ({{ $medicine->strength }} {{ $medicine->unit }})
                                            - Stock: {{ $medicine->stock }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="medicineStock" class="text-xs mt-1 text-gray-500"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosage *</label>
                                    <input type="text" name="dosage" required placeholder="e.g., 1 tablet"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Frequency *</label>
                                    <input type="text" name="frequency" required placeholder="e.g., Twice daily"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration *</label>
                                    <input type="text" name="duration" required placeholder="e.g., 7 days"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                    <input type="number" name="quantity" required min="1"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Refills Allowed</label>
                                    <input type="number" name="refills_allowed" min="0" max="10"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                                <textarea name="instructions" rows="2"
                                          placeholder="Special instructions for the patient..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4 border-t">
                                <button type="button" onclick="closePrescriptionForm()"
                                        class="px-4 py-2 bg-gradient-to-r from-rose-600 to-rose-500 text-white font-bold  rounded-lg hover:from-rose-950 hover:to-rose-600 hover:text-white transition-colors">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-r from-teal-600 to-green-500 text-white font-bold  rounded-lg hover:from-teal-950 hover:to-green-600 hover:text-white transition-colors">
                                    <i class="fas fa-prescription mr-2"></i>
                                    Add Prescription
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Prescriptions List -->
                <div id="prescriptionsSection" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Prescriptions</h3>
                        <span class="text-sm text-gray-500" id="prescriptionCount">
                            {{ $visit->prescriptions->count() }} prescriptions
                        </span>
                    </div>

                    <div id="prescriptionsList" class="space-y-4">
                        @if ($visit->prescriptions->count() > 0)
                            @foreach ($visit->prescriptions as $prescription)
                                @include('doctor.partials.prescription-item', [
                                    'prescription' => $prescription,
                                ])
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-prescription-bottle-alt text-3xl mb-3"></i>
                                <p>No prescriptions added yet</p>
                                <p class="text-sm mt-1">Add prescriptions from diagnoses</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <a href="{{ route('doctor.consultancy') }}"
                       class="px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-500 text-white font-bold  rounded-lg hover:from-gray-950 hover:to-gray-600 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Consultations
                    </a>
                </div>

                <div class="flex space-x-3">
                    @if ($visit->prescriptions->count() > 0)
                        <button onclick="printPrescription('{{$visit->prescriptions->first()->id}}')"
                                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white font-bold  rounded-lg hover:from-indigo-950 hover:to-indigo-600 hover:text-white transition-colors"
                                title="Print">
                            <i class="fas fa-print"></i>
                            Print
                        </button>
                    @endif
                    @if ($visit->status === 'in_progress')
                        <button onclick="showCompleteModal()"
                                class="px-4 py-2 bg-gradient-to-r from-teal-600 to-green-500 text-white font-bold  rounded-lg hover:from-teal-950 hover:to-green-600 hover:text-white transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>
                            Complete Consultation
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Consultation Modal -->
    <div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="p-6 text-center">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Complete Consultation</h3>
                    <p class="text-gray-600 mb-6">
                        Are you sure you want to complete this consultation for
                        <span class="font-semibold text-green-700">{{ $visit->patient->name }}</span>?
                        This will finalize the visit and update patient records.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <button onclick="closeCompleteModal()"
                                class="px-6 py-2.5 border-2 bg-gradient-to-r from-maroon-600 to-maroon-500 text-white font-bold  rounded-lg hover:from-maroon-950 hover:to-maroon-600 hover:text-white transition-colors">
                            Cancel
                        </button>
                        <button onclick="confirmCompleteConsultation()" id="completeBtn"
                                class="px-6 py-2.5 bg-gradient-to-r from-teal-600 to-md-green text-white font-bold  rounded-lg hover:from-teal-950 hover:to-green-600 hover:text-white transition-colors">
                            Complete Consultation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vitals Modal -->
    <div id="vitalsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Record Vitals</h3>
                        <button onclick="closeVitalsModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="vitalsForm">
                        @csrf
                        <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                        <input type="hidden" name="patient_id" value="{{ $visit->patient->id }}">

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Temperature (°F)</label>
                                    <input type="number" step="0.1" name="temperature"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pulse (BPM)</label>
                                    <input type="number" name="pulse"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">BP Systolic (mmHg)</label>
                                    <input type="number" name="blood_pressure_systolic" placeholder="120"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">BP Diastolic (mmHg)</label>
                                    <input type="number" name="blood_pressure_diastolic" placeholder="80"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Respiratory Rate</label>
                                    <input type="number" name="respiratory_rate"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">O2 Saturation
                                        (%)</label>
                                    <input type="number" step="0.1" name="oxygen_saturation"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea name="notes" rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4 border-t">
                                <button type="button" onclick="closeVitalsModal()"
                                        class="px-4 py-2 text-gray-700 hover:text-gray-900">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Vitals
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load medical history on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadMedicalHistory({{ $visit->patient->id }});
        });

        // Medicine stock check
        document.querySelector('select[name="medicine_id"]')?.addEventListener('change', function (e) {
            const selectedOption = this.options[this.selectedIndex];
            const stock = selectedOption.dataset.stock;
            const stockElement = document.getElementById('medicineStock');

            if (stock) {
                if (stock <= 0) {
                    stockElement.innerHTML = '<span class="text-red-600">Out of stock!</span>';
                } else if (stock <= 10) {
                    stockElement.innerHTML = `<span class="text-yellow-600">Low stock: ${stock} remaining</span>`;
                } else {
                    stockElement.innerHTML = `<span class="text-green-600">In stock: ${stock} available</span>`;
                }
            } else {
                stockElement.innerHTML = '';
            }
        });

        function loadMedicalHistory(patientId) {
            fetch(`/doctor/patient/${patientId}/history`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const history = data.data;
                        let html = `
                <div class="space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="text-center p-2 bg-blue-50 rounded">
                            <div class="font-bold text-blue-700">${history.statistics.total_visits}</div>
                            <div class="text-xs text-blue-600">Total Visits</div>
                        </div>
                        <div class="text-center p-2 bg-green-50 rounded">
                            <div class="font-bold text-green-700">${history.statistics.total_prescriptions}</div>
                            <div class="text-xs text-green-600">Prescriptions</div>
                        </div>
                    </div>
            `;

                        if (history.patient.chronic_conditions) {
                            html += `
                    <div class="mt-3">
                        <div class="text-sm font-medium text-gray-700 mb-1">Chronic Conditions:</div>
                        <div class="text-sm text-gray-600">${history.patient.chronic_conditions}</div>
                    </div>
                `;
                        }

                        if (history.patient.allergies) {
                            html += `
                    <div class="mt-3">
                        <div class="text-sm font-medium text-gray-700 mb-1">Allergies:</div>
                        <div class="text-sm text-gray-600">${history.patient.allergies}</div>
                    </div>
                `;
                        }

                        html += `</div>`;

                        document.getElementById('medicalHistory').innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error loading medical history:', error);
                    document.getElementById('medicalHistory').innerHTML = `
            <div class="text-center text-gray-500">
                <i class="fas fa-exclamation-circle"></i>
                <p class="mt-2">Failed to load history</p>
            </div>
        `;
                });
        }

        // Diagnosis Form Submission
        document.getElementById('diagnosisForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/doctor/diagnosis/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Diagnosis saved successfully', 'success');

                        // Add new diagnosis to list
                        document.getElementById('diagnosesList').insertAdjacentHTML('afterbegin', data.html);

                        // Update count
                        const countElement = document.getElementById('diagnosisCount');
                        const currentCount = parseInt(countElement.textContent);
                        countElement.textContent = (currentCount + 1) + ' diagnoses';

                        // Check if prescription needed
                        if (formData.get('has_prescription') === '1') {
                            openPrescriptionForm(data.diagnosis.id);
                        }

                        // Reset form
                        resetDiagnosisForm();
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to save diagnosis', 'error');
                });
        });

        function resetDiagnosisForm() {
            document.getElementById('diagnosisForm').reset();
        }

        function openPrescriptionForm(diagnosisId) {
            document.getElementById('prescriptionDiagnosisId').value = diagnosisId;
            document.getElementById('prescriptionForm').classList.remove('hidden');

            // Scroll to prescription form
            document.getElementById('prescriptionForm').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function closePrescriptionForm() {
            document.getElementById('prescriptionForm').classList.add('hidden');
            document.getElementById('addPrescriptionForm').reset();
            document.getElementById('medicineStock').innerHTML = '';
        }

        // Prescription Form Submission
        document.getElementById('addPrescriptionForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/doctor/prescription/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Prescription added successfully', 'success');

                        // Add new prescription to list
                        document.getElementById('prescriptionsList').insertAdjacentHTML('afterbegin', data
                            .html);

                        // Update count
                        const countElement = document.getElementById('prescriptionCount');
                        const currentCount = parseInt(countElement.textContent);
                        countElement.textContent = (currentCount + 1) + ' prescriptions';

                        // Close form
                        closePrescriptionForm();
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to add prescription', 'error');
                });
        });

        // Complete Consultation Modal Functions
        function showCompleteModal() {
            document.getElementById('completeModal').classList.remove('hidden');
        }

        function closeCompleteModal() {
            document.getElementById('completeModal').classList.add('hidden');
        }

        function confirmCompleteConsultation() {
            const btn = document.getElementById('completeBtn');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Completing...';
            btn.disabled = true;

            fetch(`/doctor/consultancy/{{ $visit->id }}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Consultation completed successfully', 'success');
                        closeCompleteModal();

                        setTimeout(() => {
                            window.location.href = data.redirect || '/doctor/consultancy';
                        }, 1500);
                    } else {
                        showNotification(data.message, 'error');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to complete consultation', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }

        function printConsultation() {
            window.print();
        }

        // Fixed Vitals Modal Functions
        function showVitalsModal() {
            document.getElementById('vitalsModal').classList.remove('hidden');
        }

        function closeVitalsModal() {
            document.getElementById('vitalsModal').classList.add('hidden');
            document.getElementById('vitalsForm').reset();
        }

        function printPrescription(id) {
            window.open(`/print/prescription/${id}`, '_blank');
        }

        // Vitals Form Submission
        document.getElementById('vitalsForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/doctor/vitals/record', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Vitals recorded successfully', 'success');
                        closeVitalsModal();

                        // Reload the page to show updated vitals
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to record vitals', 'error');
                });
        });
    </script>
@endsection
