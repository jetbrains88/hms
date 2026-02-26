@extends('layouts.app')

@section('title', 'Edit Appointment - HMS')
@section('page-title', 'Edit Appointment')
@section('breadcrumb', 'Appointments / Edit')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-8 py-6">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-edit text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Appointment</h1>
                    <p class="text-pink-100 text-sm mt-1">Update appointment details for {{ $appointment->patient->name }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('reception.appointments.update', $appointment) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

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

            {{-- Patient (Read Only) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
                <div class="w-full border border-gray-200 bg-slate-50 rounded-xl px-4 py-2.5 text-slate-600">
                    {{ $appointment->patient->name }} — {{ $appointment->patient->opd_number }}
                </div>
                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
            </div>

            {{-- Doctor --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                <select name="doctor_id"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 outline-none @error('doctor_id') border-red-400 @enderror" required>
                    <option value="">Select Doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
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
                        value="{{ old('scheduled_at', \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d\TH:i')) }}"
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 outline-none @error('scheduled_at') border-red-400 @enderror" required>
                    @error('scheduled_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Type <span class="text-red-500">*</span></label>
                    <select name="type"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 outline-none @error('type') border-red-400 @enderror" required>
                        <option value="">Select Type</option>
                        <option value="physical" {{ old('type', $appointment->type) === 'physical' ? 'selected' : '' }}>In-Person (Physical)</option>
                        <option value="online" {{ old('type', $appointment->type) === 'online' ? 'selected' : '' }}>Online (Telemedicine)</option>
                    </select>
                    @error('type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 outline-none @error('status') border-red-400 @enderror" required>
                    <option value="scheduled" {{ old('status', $appointment->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="confirmed" {{ old('status', $appointment->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ old('status', $appointment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="no_show" {{ old('status', $appointment->status) === 'no_show' ? 'selected' : '' }}>No Show</option>
                    <option value="completed" {{ old('status', $appointment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Reason --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason / Notes</label>
                <textarea name="reason" rows="3"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 outline-none"
                    placeholder="Reason for the appointment...">{{ old('reason', $appointment->reason) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('reception.appointments.show', $appointment) }}"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                    class="px-8 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>Update Appointment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
