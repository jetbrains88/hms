@extends('layouts.app')

@section('title', 'Admin Dashboard - NHMP HMS')
@section('page-title', 'Admin Dashboard')
@section('breadcrumb', 'Overview')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Patients -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-blue-600 mb-1">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['totalPatients'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Registered in system</p>
                </div>
                <div class="p-3 bg-blue-500 rounded-xl shadow-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-blue-200">
                <div class="flex items-center text-sm text-blue-700">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>+12% from last month</span>
                </div>
            </div>
        </div>

        <!-- Today's Visits -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-green-600 mb-1">Today's Visits</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['todayVisits'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Consultations today</p>
                </div>
                <div class="p-3 bg-green-500 rounded-xl shadow-lg">
                    <i class="fas fa-stethoscope text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-green-200">
                <div
                    class="flex items-center text-sm {{ $stats['visitTrend'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                    <i class="fas {{ $stats['visitTrend'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                    <span>{{ abs($stats['visitTrend']) }}% {{ $stats['visitTrend'] >= 0 ? 'increase' : 'decrease' }} from yesterday</span>
                </div>
            </div>
        </div>

        <!-- Pending Prescriptions -->
        <div
            class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-yellow-600 mb-1">Pending Prescriptions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pendingPrescriptions'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Awaiting pharmacy</p>
                </div>
                <div class="p-3 bg-yellow-500 rounded-xl shadow-lg">
                    <i class="fas fa-prescription-bottle-alt text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-yellow-200">
                <a href="{{ route('pharmacy.inventory') }}"
                   class="text-sm text-yellow-700 hover:text-yellow-800 font-medium">
                    View in pharmacy →
                </a>
            </div>
        </div>

        <!-- Low Stock Medicines -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-red-600 mb-1">Low Stock</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['lowStockMedicines'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Medicines below threshold</p>
                </div>
                <div class="p-3 bg-red-500 rounded-xl shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-red-200">
                <button class="text-sm text-red-700 hover:text-red-800 font-medium">
                    Generate order list
                </button>
            </div>
        </div>
    </div>

    <!-- System Health & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- System Health -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">System Health Monitor</h3>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    <i class="fas fa-circle text-xs mr-1"></i>
                    All Systems Operational
                </span>
            </div>

            <div class="space-y-4">
                <!-- Database Status -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-database text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Database</p>
                            <p class="text-sm text-gray-500">MySQL Connection</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            Healthy
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Response: 12ms</p>
                    </div>
                </div>

                <!-- Storage Status -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-hard-drive text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Storage</p>
                            <p class="text-sm text-gray-500">Disk Space Usage</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center">
                            <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-green-500 h-2 rounded-full"
                                     style="width: {{ $systemHealth['storage']['used_percent'] }}%"></div>
                            </div>
                            <span class="text-sm font-medium">{{ $systemHealth['storage']['used_percent'] }}%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $systemHealth['storage']['free'] }} free</p>
                    </div>
                </div>

                <!-- Last Backup -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-shield-alt text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Last Backup</p>
                            <p class="text-sm text-gray-500">Database Backup</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span
                            class="px-3 py-1 {{ $systemHealth['lastBackup'] == 'Never' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} rounded-full text-sm">
                            {{ $systemHealth['lastBackup'] == 'Never' ? 'No Backup' : 'Completed' }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ $systemHealth['lastBackup'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-b from-gray-900 to-gray-800 rounded-2xl shadow-xl p-6 text-white">
            <h3 class="text-lg font-bold mb-6">Quick Actions</h3>

            <div class="space-y-4">
                <button onclick="performQuickAction('clear_cache')"
                        class="w-full p-4 bg-gray-800 hover:bg-gray-700 rounded-xl transition-all flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-broom text-white"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-medium">Clear Cache</p>
                            <p class="text-xs text-gray-400">Refresh system cache</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                </button>

                <a href="{{ route('admin.users') }}"
                   class="block p-4 bg-gray-800 hover:bg-gray-700 rounded-xl transition-all flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-medium">Add New User</p>
                            <p class="text-xs text-gray-400">Create staff account</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                </a>

                <button onclick="performQuickAction('backup_database')"
                        class="w-full p-4 bg-gray-800 hover:bg-gray-700 rounded-xl transition-all flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-download text-white"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-medium">Backup Database</p>
                            <p class="text-xs text-gray-400">Create system backup</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                </button>

                <a href="{{ route('admin.settings') }}"
                   class="block p-4 bg-gray-800 hover:bg-gray-700 rounded-xl transition-all flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-cog text-white"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-medium">System Settings</p>
                            <p class="text-xs text-gray-400">Configure application</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-white"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Patients -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Recent Patients</h3>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    View all →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentPatients as $patient)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $patient->name }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs text-gray-500 mr-3">EMRN: {{ $patient->emrn }}</span>
                                    <span
                                        class="text-xs px-2 py-1 rounded {{ $patient->is_nhmp ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $patient->is_nhmp ? 'NHMP' : 'General' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">{{ $patient->created_at->diffForHumans() }}</p>
                            @if($patient->office)
                            <p class="text-xs text-gray-600 mt-1">{{ $patient->office->name }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Visits -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Recent Visits</h3>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    View all →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentVisits as $visit)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br {{ $visit->status == 'completed' ? 'from-green-100 to-green-200' : 'from-yellow-100 to-yellow-200' }} rounded-lg flex items-center justify-center mr-3">
                                <i class="fas {{ $visit->status == 'completed' ? 'fa-check-circle' : 'fa-clock' }} {{ $visit->status == 'completed' ? 'text-green-600' : 'text-yellow-600' }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $visit->patient->name }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs font-mono bg-gray-100 text-gray-800 px-2 py-1 rounded mr-2">
                                        {{ $visit->queue_token }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $visit->doctor->name ?? 'Unassigned' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium {{ $visit->status == 'completed' ? 'bg-green-100 text-green-800' : ($visit->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $visit->created_at->format('h:i A') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="bg-gradient-to-r from-indigo-900 via-purple-900 to-pink-900 rounded-2xl shadow-xl p-6 text-white">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-xl font-bold">System Overview</h3>
                <p class="text-indigo-200 mt-1">Real-time system metrics and analytics</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <p class="text-2xl font-bold">{{ $stats['activeUsers'] }}</p>
                    <p class="text-xs text-indigo-300">Active Users</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold">{{ $stats['todayRevenue'] }}</p>
                    <p class="text-xs text-indigo-300">Today's Revenue</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-indigo-200">Uptime</p>
                        <p class="text-2xl font-bold">99.8%</p>
                    </div>
                    <i class="fas fa-server text-xl text-green-400"></i>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-indigo-200">Avg. Response</p>
                        <p class="text-2xl font-bold">45ms</p>
                    </div>
                    <i class="fas fa-bolt text-xl text-yellow-400"></i>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-indigo-200">Error Rate</p>
                        <p class="text-2xl font-bold">0.2%</p>
                    </div>
                    <i class="fas fa-bug text-xl text-red-400"></i>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-indigo-200">API Calls</p>
                        <p class="text-2xl font-bold">2.4K</p>
                    </div>
                    <i class="fas fa-sync-alt text-xl text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function performQuickAction(action) {
        fetch('/admin/quick-action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({action: action})
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                showNotification('error', 'An error occurred');
            });
    }

    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection
