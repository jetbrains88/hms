@extends('layouts.app')

@section('title', 'Pharmacy Reports')
@section('page-title', 'Pharmacy Reports')
@section('breadcrumb', 'Analytics & Reports')

@section('content')
    <div class="space-y-6">
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $metrics['total_medicines'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Total Medicines</div>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-pills text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-6 border border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $metrics['total_categories'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Categories</div>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-400 to-teal-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tags text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-6 border border-yellow-100">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $metrics['low_stock_count'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Low Stock Items</div>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl p-6 border border-red-100">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $metrics['out_of_stock_count'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Out of Stock</div>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-red-400 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-times-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Low Stock Medicines -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Low Stock Medicines</h3>
                    <p class="text-gray-600 mt-1">Medicines running below minimum stock level</p>
                </div>
                <div class="p-6">
                    @if ($lowStock->isEmpty())
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">All Stock Levels Good</h3>
                            <p class="text-gray-500">No medicines are currently low in stock.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($lowStock as $medicine)
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg border border-yellow-100">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-pills text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $medicine->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $medicine->category ?? 'Uncategorized' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-amber-700">{{ $medicine->stock }}
                                            {{ $medicine->unit }}</div>
                                        <div class="text-xs text-amber-600">Min: {{ $medicine->min_stock_level }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Expiring Soon -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Expiring Soon</h3>
                    <p class="text-gray-600 mt-1">Medicines expiring within 30 days</p>
                </div>
                <div class="p-6">
                    @if ($expiringSoon->isEmpty())
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">No Expiring Medicines</h3>
                            <p class="text-gray-500">No medicines are expiring within the next 30 days.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($expiringSoon as $medicine)
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-lg border border-orange-100">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-calendar-times text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $medicine->medicine->name }}</div>
                                            <div class="text-sm text-gray-600">Batch: {{ $medicine->batch_number }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-red-700">
                                            {{ \Carbon\Carbon::parse($medicine->expiry_date)->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-red-600">
                                            {{ \Carbon\Carbon::parse($medicine->expiry_date)->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detailed Metrics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detailed Inventory Metrics</h3>
                <p class="text-gray-600 mt-1">Comprehensive analysis of pharmacy inventory</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $metrics['total_value'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Total Inventory Value</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ $metrics['avg_stock_turnover'] ?? 0 }} days
                        </div>
                        <div class="text-sm text-gray-600">Avg. Stock Turnover</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ $metrics['total_dispenses'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Monthly Dispenses</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600 mb-2">
                            ${{ number_format($metrics['monthly_revenue'] ?? 0, 2) }}</div>
                        <div class="text-sm text-gray-600">Monthly Revenue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Reports</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button
                    class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100 hover:shadow transition-all text-left">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-file-pdf text-white"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Stock Report</div>
                            <div class="text-sm text-gray-600">PDF Format</div>
                        </div>
                    </div>
                </button>

                <button
                    class="p-4 bg-gradient-to-r from-green-50 to-teal-50 rounded-lg border border-green-100 hover:shadow transition-all text-left">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-green-400 to-teal-500 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-file-excel text-white"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Sales Report</div>
                            <div class="text-sm text-gray-600">Excel Format</div>
                        </div>
                    </div>
                </button>

                <button
                    class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100 hover:shadow transition-all text-left">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-500 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Analytics Report</div>
                            <div class="text-sm text-gray-600">Detailed Analysis</div>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
@endsection
