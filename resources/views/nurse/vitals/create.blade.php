@extends('layouts.app')

@section('title', 'Patient Vitals')
@section('page-title', 'Record Patient Vitals')
@section('breadcrumb', 'Nurse / Vitals / Create')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Vital Signs Recording</h3>
            <p class="text-sm text-gray-600">Enter patient's physical measurements and vital signs</p>
        </div>

        <form action="{{ route('nurse.vitals.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            @if(request('visit_id'))
                <input type="hidden" name="visit_id" value="{{ request('visit_id') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Vitals -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-700 pb-2 border-b">Circulatory & Respiratory</h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure (mmHg)</label>
                        <div class="flex gap-2">
                            <input type="text" name="systolic" placeholder="Sys" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <span class="flex items-center text-gray-500">/</span>
                            <input type="text" name="diastolic" placeholder="Dia" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pulse Rate (bpm)</label>
                            <input type="number" name="pulse_rate" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Resp. Rate (bpm)</label>
                            <input type="number" name="respiratory_rate" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Body Measurements -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-700 pb-2 border-b">Physical & Temperature</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Temperature (°C)</label>
                            <input type="number" step="0.1" name="temperature" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Oxygen Saturation (%)</label>
                            <input type="number" name="spo2" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                            <input type="number" step="0.1" name="height" class="block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="history.back()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Save Vitals</button>
            </div>
        </form>
    </div>
</div>
@endsection
