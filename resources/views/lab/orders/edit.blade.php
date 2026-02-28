@extends('layouts.app')

@section('title', 'Edit Lab Order')

@section('content')
    <div x-data="labReportForm" x-init="init()" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- Header -->
        <div class="bg-white shadow-xl rounded-2xl mx-4 mt-6 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Lab Order #{{ $labOrder->lab_number }}</h1>
                    <p class="text-gray-600 mt-2">Update laboratory order details</p>
                </div>
                <div>
                    <a href="{{ route('lab.orders.show', $labOrder) }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Details
                    </a>
                </div>
            </div>
        </div>

        <div class="mx-4 mt-6">
            <form action="{{ route('lab.orders.update', $labOrder) }}" method="POST" class="bg-white shadow-xl rounded-2xl p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                        <input type="text" value="{{ $labOrder->patient?->name ?? 'N/A' }}" disabled class="w-full px-4 py-3 border rounded-xl bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500">
                            <option value="normal" {{ $labOrder->priority == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="urgent" {{ $labOrder->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="emergency" {{ $labOrder->priority == 'emergency' ? 'selected' : '' }}>Emergency</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Device Name</label>
                        <input type="text" name="device_name" value="{{ $labOrder->device_name }}" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reporting Date</label>
                        <input type="datetime-local" name="reporting_date" value="{{ $labOrder->reporting_date ? $labOrder->reporting_date->format('Y-m-d\TH:i') : '' }}" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                        <textarea name="comments" rows="3" class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500">{{ $labOrder->comments }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 shadow-lg transition-all">
                        Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
