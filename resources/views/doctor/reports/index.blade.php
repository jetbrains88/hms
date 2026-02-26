@extends('layouts.app')

@section('title', 'Doctor Reports')
@section('page-title', 'Clinical Reports')
@section('breadcrumb', 'Doctor / Reports')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-user-md text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Total Consultations</span>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalConsultations ?? 0 }}</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                    <i class="fas fa-flask text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Lab Orders</span>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalLabOrders ?? 0 }}</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="fas fa-pills text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-500">Prescriptions</span>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalPrescriptions ?? 0 }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Clinical Reports</h3>
        </div>
        <div class="p-6 text-center text-gray-500">
            No clinical reports available.
        </div>
    </div>
</div>
@endsection
