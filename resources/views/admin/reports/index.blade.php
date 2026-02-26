@extends('layouts.app')

@section('title', 'Reports')

@section('page-title', 'Reports Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Patient Reports -->
        <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-lg transition-shadow">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-users text-blue-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Patient Reports</h3>
            <p class="text-sm text-slate-500 mb-4">View patient demographics, registration trends, and statistics</p>
            <a href="{{ route('admin.reports.patients') }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                View Report <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Visit Reports -->
        <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-lg transition-shadow">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-clipboard-list text-green-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Visit Reports</h3>
            <p class="text-sm text-slate-500 mb-4">Analyze patient visits, wait times, and doctor performance</p>
            <a href="{{ route('admin.reports.visits') }}" class="text-green-500 hover:text-green-700 text-sm font-medium">
                View Report <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Pharmacy Reports -->
        <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-lg transition-shadow">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-pills text-amber-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Pharmacy Reports</h3>
            <p class="text-sm text-slate-500 mb-4">Track prescriptions, inventory value, and medicine usage</p>
            <a href="{{ route('admin.reports.pharmacy') }}" class="text-amber-500 hover:text-amber-700 text-sm font-medium">
                View Report <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Laboratory Reports -->
        <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-lg transition-shadow">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-flask text-purple-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Laboratory Reports</h3>
            <p class="text-sm text-slate-500 mb-4">Monitor lab orders, test volumes, and abnormal results</p>
            <a href="{{ route('admin.reports.laboratory') }}"
                class="text-purple-500 hover:text-purple-700 text-sm font-medium">
                View Report <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Appointment Reports -->
        <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-lg transition-shadow">
            <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-calendar-check text-pink-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Appointment Reports</h3>
            <p class="text-sm text-slate-500 mb-4">Analyze appointment trends, no-shows, and doctor schedules</p>
            <a href="{{ route('admin.reports.appointments') }}"
                class="text-pink-500 hover:text-pink-700 text-sm font-medium">
                View Report <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Audit Reports -->
        <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-lg transition-shadow">
            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-history text-slate-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Audit Reports</h3>
            <p class="text-sm text-slate-500 mb-4">Review system activity, user actions, and data changes</p>
            <a href="{{ route('admin.reports.audit') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium">
                View Report <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
@endsection
