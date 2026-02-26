@extends('layouts.app')

@section('title', 'Patient Details - ' . $patient->name)
@section('page-title', 'Patient Details')
@section('breadcrumb', 'Patients / ' . $patient->name)

@section('content')
<div class="space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('reception.patients.index') }}"
            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
    </div>

    {{-- Patient Header Card --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="h-20 w-20 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg
                {{ $patient->gender === 'male' ? 'bg-white/20' : ($patient->gender === 'female' ? 'bg-pink-400/40' : 'bg-purple-400/40') }}">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold">{{ $patient->name }}</h1>
                    @if($patient->is_nhmp)
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">NHMP Staff</span>
                    @else
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">General Public</span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-4 text-blue-100 text-sm">
                    <span><i class="fas fa-id-badge mr-1"></i>{{ $patient->emrn ?? $patient->opd_number ?? 'N/A' }}</span>
                    <span><i class="fas fa-phone mr-1"></i>{{ $patient->phone }}</span>
                    <span><i class="fas fa-{{ $patient->gender === 'male' ? 'mars' : 'venus' }} mr-1"></i>{{ ucfirst($patient->gender) }}</span>
                    @if($patient->blood_group)
                        <span><i class="fas fa-tint mr-1"></i>{{ $patient->blood_group }}</span>
                    @endif
                    @if($patient->dob)
                        <span><i class="fas fa-birthday-cake mr-1"></i>{{ \Carbon\Carbon::parse($patient->dob)->age }} years</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reception.patients.edit', $patient) }}"
                    class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl font-medium text-sm transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('reception.visits.create', ['patient_id' => $patient->id]) }}"
                    class="px-4 py-2 bg-green-400/30 hover:bg-green-400/50 rounded-xl font-medium text-sm transition-colors">
                    <i class="fas fa-plus mr-2"></i>New Visit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Personal Info --}}
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-500"></i> Personal Information
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">CNIC</dt>
                        <dd class="text-sm font-mono text-gray-800">{{ $patient->cnic ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Date of Birth</dt>
                        <dd class="text-sm text-gray-800">{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('d M Y') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Address</dt>
                        <dd class="text-sm text-gray-800">{{ $patient->address ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Registered On</dt>
                        <dd class="text-sm text-gray-800">{{ $patient->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            @if($patient->is_nhmp && $patient->employeeDetail)
            {{-- NHMP Details --}}
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-id-badge text-purple-500"></i> NHMP Details
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Designation</dt>
                        <dd class="text-sm text-gray-800">{{ optional($patient->employeeDetail->designation)->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Office / Unit</dt>
                        <dd class="text-sm text-gray-800">{{ optional($patient->employeeDetail->office)->name ?? '—' }}</dd>
                    </div>
                    @if($patient->rank)
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Rank / Batch</dt>
                        <dd class="text-sm text-gray-800">{{ $patient->rank }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            {{-- Medical Info --}}
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-notes-medical text-green-500"></i> Medical Info
                </h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Allergies</dt>
                        <dd class="text-sm text-gray-800">{{ $patient->allergies ?? 'None recorded' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Chronic Conditions</dt>
                        <dd class="text-sm text-gray-800">{{ $patient->chronic_conditions ?? 'None recorded' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Medical History</dt>
                        <dd class="text-sm text-gray-800">{{ $patient->medical_history ?? 'No significant history' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Recent Visits --}}
            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-green-500"></i> Recent Visits
                    </h2>
                    <a href="{{ route('reception.patients.history', $patient) }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
                </div>
                @if($patient->visits && $patient->visits->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($patient->visits as $visit)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $visit->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">Token: {{ $visit->token_number ?? 'N/A' }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $visit->status === 'completed' ? 'bg-green-100 text-green-700' :
                               ($visit->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                               'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-10 text-center text-gray-400">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>No visits recorded yet</p>
                    <a href="{{ route('reception.visits.create', ['patient_id' => $patient->id]) }}"
                        class="mt-3 inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-plus-circle"></i> Register First Visit
                    </a>
                </div>
                @endif
            </div>

            {{-- Recent Appointments --}}
            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-blue-500"></i> Recent Appointments
                    </h2>
                </div>
                @if($patient->appointments && $patient->appointments->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($patient->appointments as $appt)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $appt->appointment_time ?? '' }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ ucfirst($appt->status ?? 'Scheduled') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-10 text-center text-gray-400">
                    <i class="fas fa-calendar-plus text-4xl mb-3"></i>
                    <p>No appointments yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
