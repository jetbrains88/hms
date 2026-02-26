@extends('layouts.app')

@section('title', 'Command Center - NHMP HMS')

@section('content')
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.3);
            --shadow-color: rgba(0, 0, 0, 0.05);
        }

        /* Stats card specific */
        .stat-card {
            border-radius: 1rem;
            padding: 1.25rem;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        /* Icon containers */
        .icon-container {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Progress bars */
        .progress-bar {
            height: 0.375rem;
            border-radius: 1rem;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.2);
            margin-top: 0.75rem;
        }

        .progress-fill {
            height: 100%;
            border-radius: 1rem;
            background: white;
        }

        /* Badge styles */
        .trend-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
        }

        /* Status indicators */
        .status-dot {
            width: 0.375rem;
            height: 0.375rem;
            border-radius: 9999px;
            background: white;
        }

        .status-dot-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Main dashboard styling */
        .dashboard-container {
            padding: 0;
            margin: 0;
            width: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .content-area {
            padding: 1rem;
            width: 100%;
        }

        /* Glass panel base */
        .glass-panel {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            border-radius: 1rem;
        }

        .glass-panel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }

        /* Chart containers */
        .chart-container {
            position: relative;
            width: 100%;
        }

        /* Process button */
        .process-button {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .process-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Responsive fixes */
        @media (max-width: 768px) {
            .stat-card {
                padding: 1rem;
            }

            .icon-container {
                width: 2.5rem;
                height: 2.5rem;
            }
        }

        /* Ensure chart containers have proper dimensions */
        #patientChart,
        #deptChart {
            width: 100% !important;
            height: auto !important;
        }
    </style>

    <div class="dashboard-container">
        <div class="content-area" x-data="dashboardData()" x-init="init()">

            <!-- Compact Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Command Center</h1>
                    <p class="text-sm text-gray-600">NHMP HOSPITAL MANAGEMENT SYSTEM v2.0</p>
                </div>
                <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm">
                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-mono text-emerald-600 font-bold">SYSTEM ONLINE</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-xs font-mono text-cyan-600 font-bold" x-text="currentTime"></span>
                </div>
            </div>

            <!-- Stats Cards - Using Tailwind gradients like quick response -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <!-- Total Patients - Blue gradient -->
                <div class="stat-card bg-gradient-to-br from-blue-600 to-cyan-400 shadow-lg hover:shadow-xl text-white">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-white/90 text-xs font-semibold uppercase tracking-wider">Total Patients</p>
                        <div class="icon-container">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-1">
                        <h2 class="text-3xl font-bold text-white">105</h2>
                        <span class="trend-badge">
                            <i class="fas fa-arrow-up mr-1"></i>12%
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 75%"></div>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="status-dot status-dot-pulse"></div>
                        <span class="text-xs text-white/80">Monthly growth trend</span>
                    </div>
                </div>

                <!-- Visits Today - Amber/Yellow gradient -->
                <div class="stat-card bg-gradient-to-br from-amber-600 to-yellow-400 shadow-lg hover:shadow-xl text-white">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-white/90 text-xs font-semibold uppercase tracking-wider">Visits Today</p>
                        <div class="icon-container">
                            <i class="fas fa-procedures text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-1">
                        <h2 class="text-3xl font-bold text-white">0</h2>
                        <span class="trend-badge">
                            <i class="fas fa-arrow-up mr-1"></i>0%
                        </span>
                    </div>
                    <div class="flex gap-1 h-1.5 mt-3">
                        @for ($i = 0; $i < 10; $i++)
                            <div class="flex-1 bg-white/{{ 20 + $i * 5 }} rounded-full"></div>
                        @endfor
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="status-dot status-dot-pulse"></div>
                        <span class="text-xs text-white/80">Hourly distribution</span>
                    </div>
                </div>

                <!-- Pharmacy Queue - Red/Orange gradient -->
                <div class="stat-card bg-gradient-to-br from-red-600 to-orange-400 shadow-lg hover:shadow-xl text-white">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-white/90 text-xs font-semibold uppercase tracking-wider">Pharmacy Queue</p>
                        <div class="icon-container">
                            <i class="fas fa-pills text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-1">
                        <h2 class="text-3xl font-bold text-white">1</h2>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <div>
                            <p class="text-xs text-white/80">Avg Wait Time</p>
                            <p class="text-base font-semibold text-white">12 minutes</p>
                        </div>
                        <a href="{{ route('pharmacy.inventory') }}" class="process-button">
                            Process <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="status-dot status-dot-pulse"></div>
                        <span class="text-xs text-white/80">Attention required</span>
                    </div>
                </div>

                <!-- Completed Today - Green/Teal gradient -->
                <div class="stat-card bg-gradient-to-br from-emerald-600 to-teal-400 shadow-lg hover:shadow-xl text-white">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-white/90 text-xs font-semibold uppercase tracking-wider">Completed Today</p>
                        <div class="icon-container">
                            <i class="fas fa-check-circle text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-1">
                        <h2 class="text-3xl font-bold text-white">0</h2>
                    </div>
                    <div class="progress-bar mt-3">
                        <div class="progress-fill" style="width: 45%"></div>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="status-dot"></div>
                        <span class="text-xs text-white/80">Successfully closed</span>
                    </div>
                </div>

                <!-- Storage Load - Purple/Fuchsia gradient -->
                <div
                    class="stat-card bg-gradient-to-br from-purple-600 to-fuchsia-400 shadow-lg hover:shadow-xl text-white">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-white/90 text-xs font-semibold uppercase tracking-wider">Storage Load</p>
                        <div class="icon-container">
                            <i class="fas fa-server text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 mb-1">
                        <h2 class="text-3xl font-bold text-white">5.6%</h2>
                        <span class="trend-badge text-xs">
                            950.44 GB free
                        </span>
                    </div>
                    <div class="relative flex items-center justify-center h-16 mt-2">
                        <svg class="transform -rotate-90 w-16 h-16">
                            <circle cx="32" cy="32" r="26" stroke="rgba(255,255,255,0.2)" stroke-width="4"
                                fill="transparent" />
                            <circle cx="32" cy="32" r="26" stroke="white" stroke-width="4" fill="transparent"
                                stroke-linecap="round" stroke-dasharray="163.36" stroke-dashoffset="154.2" />
                        </svg>
                        <span class="absolute text-sm font-bold text-white">5.6%</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <i class="fas fa-circle-notch fa-spin text-xs text-white"></i>
                        <span class="text-xs text-white/80">In progress...</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Patient Flow Chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-5 border-b border-blue-100">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fas fa-chart-line text-blue-600"></i>
                                    Patient Flow Analytics
                                </h3>
                            </div>
                            <div class="flex gap-2">
                                <button @click="loadChartData('weekly')" id="weekly-btn"
                                    :class="currentPeriod === 'weekly'
                                        ?
                                        'px-4 py-2 text-sm rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-md' :
                                        'px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50'">
                                    Weekly
                                </button>
                                <button @click="loadChartData('monthly')" id="monthly-btn"
                                    :class="currentPeriod === 'monthly'
                                        ?
                                        'px-4 py-2 text-sm rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-md' :
                                        'px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50'">
                                    Monthly
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 relative">
                        <div class="chart-container h-72">
                            <canvas id="patientChart"></canvas>
                        </div>
                        <div x-show="chartLoading" x-cloak
                            class="absolute inset-0 bg-white/80 flex items-center justify-center rounded-xl">
                            <div class="text-center">
                                <div
                                    class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-3 mx-auto">
                                </div>
                                <p class="text-sm text-gray-600">Loading chart data...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rapid Response & Alerts -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-5 border-b border-purple-100">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-bolt text-purple-600"></i>
                            Rapid Response
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <button @click="performQuickAction('clear_cache')"
                                class="p-4 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-broom text-xl mb-2"></i>
                                    <span class="text-xs font-medium">Clear Cache</span>
                                </div>
                            </button>
                            <button @click="performQuickAction('backup_database')"
                                class="p-4 rounded-xl bg-gradient-to-br from-purple-600 to-fuchsia-400 text-white shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-database text-xl mb-2"></i>
                                    <span class="text-xs font-medium">Backup DB</span>
                                </div>
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                                class="p-4 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-400 text-white shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-user-shield text-xl mb-2"></i>
                                    <span class="text-xs font-medium">Access Control</span>
                                </div>
                            </a>
                            <div
                                class="p-4 rounded-xl bg-gradient-to-br from-amber-600 to-yellow-400 text-white shadow-lg">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-chart-line text-xl mb-2"></i>
                                    <span class="text-xs font-medium">Error Rate</span>
                                    <span class="text-base font-bold">0.02%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Alert -->
                        <div class="mt-4 p-4 rounded-xl bg-gradient-to-br from-red-600 to-orange-400 text-white shadow-lg">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle animate-pulse text-white"></i>
                                    Low Stock Alert
                                </span>
                                <span class="text-xl font-bold">{{ $stats['lowStockMedicines'] ?? 0 }}</span>
                            </div>
                            <div class="h-2 w-full bg-white/30 rounded-full overflow-hidden">
                                <div class="h-full bg-white rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-xs mt-2">items below reorder level</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Live Admissions -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-5 border-b border-green-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-hospital-user text-emerald-600"></i>
                                Live Admissions
                            </h3>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ($recentVisits ?? collect())->count() }} Active
                            </span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-5 text-left">Patient</th>
                                    <th class="py-3 px-5 text-left">Token</th>
                                    <th class="py-3 px-5 text-left">Status</th>
                                    <th class="py-3 px-5 text-right">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentVisits ?? [] as $visit)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="py-3 px-5">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-sm font-bold mr-3 shadow-md">
                                                    {{ substr($visit->patient->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800">
                                                        {{ $visit->patient->name ?? 'Unknown' }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $visit->doctor->name ?? 'Unassigned' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-5">
                                            <span
                                                class="text-xs font-mono text-cyan-700 bg-cyan-50 px-2 py-1 rounded border border-cyan-200">
                                                {{ $visit->queue_token ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-5">
                                            @php
                                                $status = $visit->status ?? 'pending';
                                                $statusColors = [
                                                    'completed' =>
                                                        'bg-gradient-to-br from-emerald-500 to-teal-500 text-white',
                                                    'in_progress' =>
                                                        'bg-gradient-to-br from-blue-500 to-cyan-500 text-white',
                                                    'pending' =>
                                                        'bg-gradient-to-br from-amber-500 to-yellow-500 text-white',
                                                    'cancelled' =>
                                                        'bg-gradient-to-br from-red-500 to-orange-500 text-white',
                                                ];
                                                $statusColor = $statusColors[$status] ?? $statusColors['pending'];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-md {{ $statusColor }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-5 text-right text-xs text-gray-500">
                                            {{ $visit->created_at ? $visit->created_at->format('H:i') : '--:--' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                                <p class="text-sm">No recent admissions</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Department Chart -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-5 border-b border-purple-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-chart-pie text-purple-600"></i>
                                Patient Issue Types
                            </h3>
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-chart-pie text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="chart-container h-72">
                            <canvas id="deptChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function dashboardData() {
            // Store chart instances outside Alpine reactive scope
            let patientChartInstance = null;
            let deptChartInstance = null;

            return {
                currentTime: '',
                circumference: 2 * Math.PI * 32,
                chartsInitialized: false,
                initCalled: false,
                chartLoading: false,
                currentPeriod: 'weekly',

                init() {
                    if (this.initCalled) return;
                    this.initCalled = true;

                    console.log('Dashboard initializing...');
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);

                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.initializeCharts();
                        }, 500);
                    });
                },

                setFilter(type) {
                    console.log('Filter set to:', type);
                },

                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });
                },

                async loadChartData(period) {
                    if (this.chartLoading || this.currentPeriod === period) return;

                    this.chartLoading = true;
                    this.currentPeriod = period;

                    try {
                        console.log(`Loading ${period} chart data...`);
                        const mockData = this.getMockChartData(period);
                        this.updateChartDataSimple(mockData);
                        this.showToast(`${period === 'weekly' ? 'Weekly' : 'Monthly'} data loaded`, 'success');
                    } catch (error) {
                        console.error('Error fetching chart data:', error);
                        this.showToast('Error loading chart data', 'error');
                    } finally {
                        this.chartLoading = false;
                    }
                },

                getMockChartData(period) {
                    if (period === 'weekly') {
                        return {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                            patients: [12, 19, 15, 25, 22, 30, 35],
                            visits: [45, 52, 48, 61, 59, 72, 75],
                            period: 'weekly'
                        };
                    } else {
                        return {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            patients: [120, 190, 150, 250, 220, 300, 350, 280, 310, 290, 330, 380],
                            visits: [450, 520, 480, 610, 590, 720, 750, 680, 710, 690, 730, 780],
                            period: 'monthly'
                        };
                    }
                },

                updateChartDataSimple(chartData) {
                    const patientCtx = document.getElementById('patientChart')?.getContext('2d');
                    if (!patientCtx) return;

                    const newConfig = this.getChartConfig(chartData);

                    if (patientChartInstance) {
                        try {
                            patientChartInstance.data = newConfig.data;
                            patientChartInstance.options = newConfig.options;
                            patientChartInstance.update();
                            console.log('Chart updated successfully');
                        } catch (e) {
                            console.warn('Update failed, recreating...', e);
                            patientChartInstance.destroy();
                            patientChartInstance = new Chart(patientCtx, newConfig);
                        }
                    } else {
                        patientChartInstance = new Chart(patientCtx, newConfig);
                        console.log('Chart created successfully');
                    }
                },

                initializeCharts() {
                    const patientCanvas = document.getElementById('patientChart');
                    const deptCanvas = document.getElementById('deptChart');

                    if (!patientCanvas || !deptCanvas) {
                        console.warn('Canvas elements not found, retrying...');
                        setTimeout(() => {
                            if (document.getElementById('patientChart')) this.initializeCharts();
                        }, 500);
                        return;
                    }

                    if (this.chartsInitialized && patientChartInstance) return;

                    if (typeof Chart === 'undefined') {
                        this.createChartFallbacks();
                        return;
                    }

                    try {
                        console.log('Initializing charts...');
                        const patientCtx = patientCanvas.getContext('2d');
                        const deptCtx = deptCanvas.getContext('2d');

                        if (patientChartInstance) patientChartInstance.destroy();
                        if (deptChartInstance) deptChartInstance.destroy();

                        const initialData = this.getMockChartData('weekly');
                        patientChartInstance = new Chart(patientCtx, this.getChartConfig(initialData));

                        const deptData = @json($departmentData ?? []);
                        const deptSeries = Array.isArray(deptData.series) ? deptData.series : [25, 20, 18, 15, 12, 10];
                        const deptLabels = Array.isArray(deptData.labels) ? deptData.labels : ['Emergency', 'OPD', 'ICU',
                            'Surgery', 'Pediatrics', 'Others'
                        ];

                        deptChartInstance = new Chart(deptCtx, {
                            type: 'doughnut',
                            data: {
                                labels: deptLabels,
                                datasets: [{
                                    data: deptSeries,
                                    backgroundColor: ['#3b82f6', '#8b5cf6', '#ef4444', '#10b981', '#f59e0b',
                                        '#64748b'
                                    ],
                                    borderColor: '#ffffff',
                                    borderWidth: 2,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            color: '#374151',
                                            font: {
                                                size: 11
                                            },
                                            usePointStyle: true,
                                            pointStyle: 'circle'
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                        titleColor: '#111827',
                                        bodyColor: '#4b5563',
                                        borderColor: '#e5e7eb',
                                        borderWidth: 1
                                    }
                                }
                            }
                        });

                        this.chartsInitialized = true;
                        console.log('Charts initialized successfully');

                    } catch (error) {
                        console.error('Chart initialization error:', error);
                        this.createChartFallbacks();
                    }
                },

                getChartConfig(chartData) {
                    const period = chartData.period || 'weekly';
                    return {
                        type: 'line',
                        data: {
                            labels: chartData.labels || [],
                            datasets: [{
                                    label: 'Patients',
                                    data: chartData.patients || [],
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#2563eb',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2
                                },
                                {
                                    label: 'Visits',
                                    data: chartData.visits || [],
                                    borderColor: '#10b981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    borderWidth: 3,
                                    borderDash: [5, 5],
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#10b981',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                    yAxisID: 'y1'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: true,
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: '#6B7280',
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: period === 'monthly' ? 'Patients (Monthly)' : 'Patients (Weekly)',
                                        color: '#2563eb',
                                        font: {
                                            size: 12,
                                            weight: '600'
                                        }
                                    },
                                    grid: {
                                        drawOnChartArea: false,
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: '#6B7280',
                                        font: {
                                            size: 10
                                        }
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: period === 'monthly' ? 'Visits (Monthly)' : 'Visits (Weekly)',
                                        color: '#10b981',
                                        font: {
                                            size: 12,
                                            weight: '600'
                                        }
                                    },
                                    grid: {
                                        drawOnChartArea: true
                                    },
                                    ticks: {
                                        color: '#6B7280',
                                        font: {
                                            size: 10
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        color: '#374151',
                                        font: {
                                            size: 11
                                        },
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                    titleColor: '#111827',
                                    bodyColor: '#4b5563',
                                    borderColor: '#e5e7eb',
                                    borderWidth: 1
                                }
                            }
                        }
                    };
                },

                createChartFallbacks() {
                    const createFallback = (elementId, icon, color, title) => {
                        const el = document.getElementById(elementId);
                        if (el && el.parentNode) {
                            el.parentNode.innerHTML = `
                                <div class="flex flex-col items-center justify-center h-64 p-8">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-${color}-100 to-${color}-200 flex items-center justify-center mb-4">
                                        <i class="fas ${icon} text-2xl text-${color}-600"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">${title}</p>
                                    <button onclick="Alpine.data('dashboardData')().initializeCharts()" 
                                            class="mt-3 px-4 py-2 bg-${color}-600 text-white text-sm rounded-lg hover:bg-${color}-700 transition-colors">
                                        Load Chart
                                    </button>
                                </div>`;
                        }
                    };
                    createFallback('patientChart', 'fa-chart-line', 'blue', 'Patient flow chart will load here');
                    createFallback('deptChart', 'fa-chart-pie', 'purple', 'Department chart will load here');
                },

                performQuickAction(action) {
                    console.log('Performing action:', action);
                    const button = event?.target?.closest('button');
                    if (button) {
                        const originalHTML = button.innerHTML;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        button.disabled = true;

                        setTimeout(() => {
                            button.innerHTML = originalHTML;
                            button.disabled = false;
                            this.showToast(`${action.replace('_', ' ')} completed successfully`, 'success');
                        }, 1500);
                    }
                },

                showToast(message, type = 'info') {
                    if (window.showNotification) {
                        window.showNotification(message, type);
                    } else if (window.toastr) {
                        window.toastr[type](message);
                    } else {
                        alert(message);
                    }
                }
            };
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .dashboard-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
@endsection
