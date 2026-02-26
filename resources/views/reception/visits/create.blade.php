@extends('layouts.app')

@section('title', 'New Visit')
@section('page-title', 'Create Patient Visit')
@section('breadcrumb', 'Reception / Visits / Create')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Patient Registration & Visit</h3>
            <p class="text-sm text-gray-600">Enter appointment details for the patient</p>
        </div>

        <form action="{{ route('reception.visits.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Select Patient -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Patient</label>
                    <select name="patient_id" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Search or select patient...</option>
                        @foreach($patients ?? [] as $patient)
                            <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} ({{ $patient->emrn }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Visit Details -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department_id" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select Department</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                    <select name="doctor_id" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Select Doctor</option>
                        @foreach($doctors ?? [] as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Visit Type</label>
                    <select name="visit_type" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="consultation">Consultation</option>
                        <option value="follow_up">Follow Up</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urgency</label>
                    <select name="urgency" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="normal">Normal</option>
                        <option value="urgent">Urgent</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="history.back()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Register Visit</button>
            </div>
        </form>
    </div>
</div>
@endsection
