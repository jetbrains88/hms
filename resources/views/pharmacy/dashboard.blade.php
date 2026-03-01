{{-- resources/views/pharmacy/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Pharmacy Dashboard')
@section('page-title', 'Pharmacy Dashboard')
@section('page-description', 'Manage prescriptions, inventory, and dispensing')

@section('content')
    <div class="space-y-6">
        <!-- Stats Overview -->
        {{-- Update the stats section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Prescriptions -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700">Pending</p>
                        <p class="text-3xl font-bold text-orange-900 mt-2">{{ $stats['pending_prescriptions'] }}</p>
                        <p class="text-xs text-orange-600 mt-1">Prescriptions to dispense</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-prescription text-white text-xl"></i>
                    </div>
                </div>
                <a href="{{ route('pharmacy.prescriptions.index') }}"
                    class="block mt-4 text-orange-600 hover:text-orange-800 text-sm font-medium">
                    View All →
                </a>
            </div>

            <!-- Dispensed Today -->
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700">Dispensed Today</p>
                        <p class="text-3xl font-bold text-emerald-900 mt-2">{{ $stats['dispensed_today'] }}</p>
                        <p class="text-xs text-emerald-600 mt-1">Prescriptions fulfilled</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-pills text-white text-xl"></i>
                    </div>
                </div>
                <a href="{{ route('pharmacy.dispense.history') }}"
                    class="block mt-4 text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                    View History →
                </a>
            </div>

            <!-- Total Inventory Value -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700">Inventory Value</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">Rs.
                            {{ number_format($stats['total_stock_value'], 2) }}</p>
                        <p class="text-xs text-blue-600 mt-1">Total stock worth</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-warehouse text-white text-xl"></i>
                    </div>
                </div>
                <a href="{{ route('pharmacy.reports') }}"
                    class="block mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View Reports →
                </a>
            </div>

            <!-- Low Stock Items -->
            <div class="bg-gradient-to-br from-rose-50 to-rose-100 border border-rose-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-rose-700">Low Stock</p>
                        <p class="text-3xl font-bold text-rose-900 mt-2">{{ $stats['low_stock_items'] }}</p>
                        <p class="text-xs text-rose-600 mt-1">Need reordering</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-r from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
                <a href="{{ route('pharmacy.alerts.index') }}"
                    class="block mt-4 text-rose-600 hover:text-rose-800 text-sm font-medium">
                    View Alerts →
                </a>
            </div>
        </div>
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Prescriptions -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700">Pending</p>
                        <p class="text-3xl font-bold text-orange-900 mt-2">{{ $stats['pending_prescriptions'] }}</p>
                        <p class="text-xs text-orange-600 mt-1">Prescriptions to dispense</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-prescription text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Dispensed Today -->
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700">Dispensed Today</p>
                        <p class="text-3xl font-bold text-emerald-900 mt-2">{{ $stats['dispensed_today'] }}</p>
                        <p class="text-xs text-emerald-600 mt-1">Prescriptions fulfilled</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-pills text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Inventory Value -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700">Inventory Value</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">
                            {{ number_format($stats['total_stock_value'], 2) }}</p>
                        <p class="text-xs text-blue-600 mt-1">Total stock worth</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-warehouse text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Low Stock Items -->
            <div class="bg-gradient-to-br from-rose-50 to-rose-100 border border-rose-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-rose-700">Low Stock</p>
                        <p class="text-3xl font-bold text-rose-900 mt-2">{{ $stats['low_stock_items'] }}</p>
                        <p class="text-xs text-rose-600 mt-1">Need reordering</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-r from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Alerts Section -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Low Stock Alerts -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="font-bold text-gray-800 text-xl flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-rose-500"></i>
                            Low Stock Alerts
                            <span class="bg-rose-100 text-rose-800 text-xs font-bold px-2 py-1 rounded-full ml-2">
                                {{ count($alerts['low_stock']) }}
                            </span>
                        </h2>
                    </div>
                    <div class="overflow-y-auto max-h-[400px]">
                        @forelse($alerts['low_stock'] as $medicine)
                            <div class="p-4 border-b border-gray-100 hover:bg-rose-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-bold text-gray-800">{{ $medicine->name }}</h4>
                                        {{-- <p class="text-sm text-gray-600">{{ $medicine->code }}</p> --}}
                                    </div>
                                    <div class="text-right">
                                        <div
                                            class="text-lg font-bold {{ $medicine->stock == 0 ? 'text-rose-600' : 'text-orange-600' }}">
                                            {{ $medicine->stock }}
                                        </div>
                                        <div class="text-xs text-gray-500">of {{ $medicine->reorder_level }}</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $medicine->stock == 0 ? 'bg-rose-500' : 'bg-orange-500' }}"
                                            style="width: {{ min(100, ($medicine->stock / $medicine->reorder_level) * 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-check-circle text-3xl text-green-500 mb-4"></i>
                                <p>All medicines are well stocked</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Expiring Soon -->
                <!-- Expiring Soon -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="font-bold text-gray-800 text-xl flex items-center gap-2">
                            <i class="fas fa-clock text-amber-500"></i>
                            Expiring Soon
                        </h2>
                    </div>
                    <div class="overflow-y-auto max-h-[300px]">
                        @forelse($alerts['expiring_soon'] as $medicine)
                            <div class="p-4 border-b border-gray-100 hover:bg-amber-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-bold text-gray-800">{{ $medicine->name }}</h4>
                                        <!-- REMOVE THIS LINE -->
                                        {{-- <p class="text-xs text-gray-600">{{ $medicine->code }}</p> --}}
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-amber-600">
                                            {{ \Carbon\Carbon::parse($medicine->expiry_date)->diffForHumans() }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center text-xs text-gray-500">
                                    <i class="fas fa-box mr-1"></i>
                                    Stock: {{ $medicine->stock }}
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-check-circle text-3xl text-green-500 mb-4"></i>
                                <p>No medicines expiring soon</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Dispenses & Quick Actions -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Dispenses -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="font-bold text-gray-800 text-xl flex items-center gap-2">
                            <i class="fas fa-history text-blue-500"></i>
                            Recent Dispenses
                        </h2>
                    </div>
                    <div class="overflow-y-auto max-h-[500px]">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Medicine
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentDispenses as $prescription)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">
                                                {{ $prescription->diagnosis->visit->patient->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $prescription->diagnosis->visit->patient->emrn }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $prescription->medicine->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $prescription->dosage }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">
                                                {{ $prescription->dispensed_quantity }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $prescription->dispensed_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-history text-3xl text-gray-300 mb-4"></i>
                                            <p>No dispenses today</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-2 gap-6">
                    <a href="{{ route('pharmacy.prescriptions.index') }}"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold">Dispense Medicines</h3>
                                <p class="text-blue-100 text-sm mt-2">Process pending prescriptions</p>
                            </div>
                            <i class="fas fa-arrow-right text-2xl"></i>
                        </div>
                    </a>

                    <a href="{{ route('pharmacy.inventory') }}"
                        class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold">Manage Inventory</h3>
                                <p class="text-emerald-100 text-sm mt-2">View and update stock</p>
                            </div>
                            <i class="fas fa-arrow-right text-2xl"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
