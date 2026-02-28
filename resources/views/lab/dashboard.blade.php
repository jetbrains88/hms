@extends('layouts.app')

@section('title', 'Laboratory Dashboard')

@section('content')
    <div x-data="labDashboard" x-init="init()" class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
        <!-- Quick Report Modal -->
        <div x-show="showQuickReportModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto"
                @click.away="showQuickReportModal = false">
                <!-- Modal content will be loaded via AJAX -->
                <div id="quickReportContent"></div>
            </div>
        </div>

        <!-- Header -->
        <div class="bg-white shadow-lg rounded-2xl mx-4 mt-6 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Laboratory Management</h1>
                    <p class="text-gray-600 mt-2">Monitor and manage all laboratory operations</p>
                </div>
                <div class="flex space-x-4">
                    <button @click="openQuickReportModal()"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                        <i class="fas fa-plus mr-2"></i>New Lab Report
                    </button>
                    <button @click="printQueue()"
                        class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                        <i class="fas fa-print mr-2"></i>Print Queue
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mx-4 mt-6">
            <!-- Pending Reports -->
            <div
                class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-xl mr-4">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-yellow-600 text-sm font-medium">Pending Reports</p>
                        <h3 class="text-3xl font-bold text-yellow-900" x-text="stats.pending || 0">0</h3>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('lab.reports.index', ['status' => 'pending']) }}"
                        class="text-yellow-600 hover:text-yellow-700 text-sm font-medium flex items-center">
                        View All <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Processing Reports -->
            <div
                class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-xl mr-4">
                        <i class="fas fa-flask text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Processing</p>
                        <h3 class="text-3xl font-bold text-blue-900" x-text="stats.processing || 0">0</h3>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('lab.reports.index', ['status' => 'processing']) }}"
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                        View All <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Urgent Reports -->
            <div
                class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-xl mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-red-600 text-sm font-medium">Urgent</p>
                        <h3 class="text-3xl font-bold text-red-900" x-text="stats.urgent || 0">0</h3>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('lab.reports.index', ['priority' => 'urgent']) }}"
                        class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center">
                        View All <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Today's Reports -->
            <div
                class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-xl mr-4">
                        <i class="fas fa-calendar-day text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-green-600 text-sm font-medium">Today's Reports</p>
                        <h3 class="text-3xl font-bold text-green-900" x-text="stats.today || 0">0</h3>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('lab.reports.index', ['date_from' => \Carbon\Carbon::today()->format('Y-m-d')]) }}"
                        class="text-green-600 hover:text-green-700 text-sm font-medium flex items-center">
                        View All <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mx-4 mt-6">
            <!-- Pending Queue -->
            <div class="bg-white rounded-2xl shadow-lg p-6 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Pending Queue</h2>
                    <button @click="refreshQueue()" class="text-blue-600 hover:text-blue-700">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <template x-for="report in pendingQueue" :key="report.id">
                        <div class="border border-gray-200 rounded-xl p-4 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-gray-900" x-text="report.test_name"></h4>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="text-sm text-gray-600">
                                            <i class="fas fa-user mr-1"></i>
                                            <span x-text="report.patient ? report.patient.name : 'N/A'"></span>
                                        </span>
                                        <span class="text-sm text-gray-600">
                                            <i class="fas fa-user-md mr-1"></i>
                                            <span x-text="report.doctor ? report.doctor.name : 'N/A'"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click="startProcessing(report.id)"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-play mr-1"></i> Start
                                    </button>
                                    <a :href="'/lab/orders/' + report.id"
                                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="pendingQueue.length === 0" class="text-center py-8 text-gray-500">
                        <i class="fas fa-check-circle text-3xl mb-2"></i>
                        <p>No pending reports in queue</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Stats</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Completed Today</span>
                        <span class="font-bold text-green-600" x-text="stats.completed_today || 0"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Overdue Reports</span>
                        <span class="font-bold text-red-600" x-text="stats.overdue || 0"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">This Week</span>
                        <span class="font-bold text-blue-600" x-text="stats.this_week || 0"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Reports</span>
                        <span class="font-bold text-gray-900" x-text="stats.total || 0"></span>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('labDashboard', () => ({
                    stats: {
                        pending: 0,
                        processing: 0,
                        urgent: 0,
                        today: 0,
                        completed_today: 0,
                        overdue: 0,
                        this_week: 0,
                        total: 0
                    },
                    pendingQueue: [],
                    showQuickReportModal: false,

                    init() {
                        this.fetchStats();
                        this.refreshQueue();
                        // Refresh every 30 seconds
                        setInterval(() => {
                            this.fetchStats();
                            this.refreshQueue();
                        }, 30000);
                    },

                    async fetchStats() {
                        try {
                            const response = await fetch('/lab/statistics');
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            const data = await response.json();
                            if (data.success) {
                                this.stats = data.data;
                            }
                        } catch (error) {
                            console.error('Error fetching stats:', error);
                            // Use default stats on error
                        }
                    },

                    async refreshQueue() {
                        try {
                            const response = await fetch('/lab/pending');
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            const data = await response.json();
                            if (data.success) {
                                this.pendingQueue = data.data;
                            }
                        } catch (error) {
                            console.error('Error refreshing queue:', error);
                            this.pendingQueue = [];
                        }
                    },

                    async startProcessing(reportId) {
                        try {
                            const response = await fetch(`/lab/orders/items/${reportId}/start`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();
                            if (data.success) {
                                this.refreshQueue();
                                this.fetchStats();
                                showNotification('Report marked as processing!', 'success');
                            } else {
                                showNotification(data.message || 'Error updating status', 'error');
                            }
                        } catch (error) {
                            console.error('Error starting processing:', error);
                            showNotification('Failed to update status', 'error');
                        }
                    },

                    async openQuickReportModal() {
                        try {
                            const response = await fetch('/lab/orders/create');
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            const html = await response.text();
                            document.getElementById('quickReportContent').innerHTML = html;

                            // Reinitialize Alpine components in the modal
                            if (window.Alpine) {
                                // Wait for DOM to update
                                setTimeout(() => {
                                    window.Alpine.initTree(document.getElementById(
                                        'quickReportContent'));
                                }, 100);
                            }

                            this.showQuickReportModal = true;
                        } catch (error) {
                            console.error('Error loading modal:', error);
                            showNotification('Failed to load form. Please try again.', 'error');
                        }
                    },

                    printQueue() {
                        window.open('/lab/reports', '_blank');
                    },


                }));
            });
        </script>
    </div>
@endsection
