@extends('layouts.app')

@section('title', 'Schedule Appointment - HMS')
@section('page-title', 'Schedule Appointment')
@section('breadcrumb', 'Appointments / Schedule')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-8 py-6">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-plus text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Schedule Appointment</h1>
                    <p class="text-teal-100 text-sm mt-1">Book a new patient appointment with a doctor</p>
                </div>
            </div>
        </div>

        <form action="{{ route('reception.appointments.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            @if (session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Patient --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                <select name="patient_id"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none @error('patient_id') border-red-400 @enderror" required>
                    <option value="">Select Patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}"
                            {{ (old('patient_id', $patientId) == $patient->id) ? 'selected' : '' }}>
                            {{ $patient->name }} — {{ $patient->emrn ?? $patient->opd_number }} ({{ $patient->phone }})
                        </option>
                    @endforeach
                </select>
                @error('patient_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Doctor --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                <select name="doctor_id"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none @error('doctor_id') border-red-400 @enderror" required>
                    <option value="">Select Doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Date & Time --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="scheduled_at"
                        value="{{ old('scheduled_at') }}"
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none @error('scheduled_at') border-red-400 @enderror" required>
                    @error('scheduled_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Type <span class="text-red-500">*</span></label>
                    <select name="type"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none @error('type') border-red-400 @enderror" required>
                        <option value="">Select Type</option>
                        <option value="physical" {{ old('type') === 'physical' ? 'selected' : '' }}>In-Person (Physical)</option>
                        <option value="online" {{ old('type') === 'online' ? 'selected' : '' }}>Online (Telemedicine)</option>
                    </select>
                    @error('type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Reason --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason / Notes</label>
                <textarea name="reason" rows="3"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                    placeholder="Reason for the appointment...">{{ old('reason') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('reception.appointments.index') }}"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                    class="px-8 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-calendar-check mr-2"></i>Schedule Appointment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
