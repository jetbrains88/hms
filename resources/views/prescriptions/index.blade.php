@extends('layouts.app')

@section('title', 'Prescription Management')
@section('page-title', 'Prescription Management')
@section('page-description', 'Manage patient prescriptions and dispensing')

@section('content')
    <div x-data="prescriptionManager()" class="space-y-6">
        <!-- Header with Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Prescriptions -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Total Prescriptions</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1" x-text="stats.total">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-prescription-bottle-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 text-xs text-blue-500">
                    <span x-text="stats.pending" class="font-semibold"></span> pending
                </div>
            </div>

            <!-- Pending Dispensing -->
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-600">Pending Dispensing</p>
                        <p class="text-2xl font-bold text-yellow-900 mt-1" x-text="stats.pending">0</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 text-xs text-yellow-500">
                    <span x-text="stats.today_dispensed" class="font-semibold"></span> dispensed today
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Completed</p>
                        <p class="text-2xl font-bold text-green-900 mt-1" x-text="stats.completed">0</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 text-xs text-green-500">
                    <span x-text="stats.completed_today" class="font-semibold"></span> today
                </div>
            </div>

            <!-- Cancelled -->
            <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Cancelled</p>
                        <p class="text-2xl font-bold text-red-900 mt-1" x-text="stats.cancelled">0</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 text-xs text-red-500">
                    Last 7 days
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        x-model="filters.search"
                        @input.debounce.300ms="searchPrescriptions"
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search by patient, medicine, or prescription ID..."
                    >
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-2">
                    <!-- Status Filter -->
                    <select x-model="filters.status" @change="fetchPrescriptions"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="dispensed">Dispensed</option>
                    </select>

                    <!-- Date Range -->
                    <input type="date" x-model="filters.start_date" @change="fetchPrescriptions"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <span class="self-center text-gray-500">to</span>
                    <input type="date" x-model="filters.end_date" @change="fetchPrescriptions"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm">

                    <!-- Per page Filter -->
                    <select x-model="filters.per_page" @change="fetchPrescriptions"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="All">All</option>

                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button @click="exportToCSV"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                        <i class="fas fa-file-export"></i>
                        <span class="hidden md:inline">Export</span>
                    </button>
                    <button @click="refreshData"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i>
                        <span class="hidden md:inline">Refresh</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Prescriptions Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prescription ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Patient
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Medicine
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dosage & Frequency
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prescribed On
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="prescription in prescriptions" :key="prescription.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Prescription ID -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono font-semibold text-gray-900">
                                    RX-<span x-text="prescription.id.toString().padStart(6, '0')"></span>
                                </div>
                            </td>

                            <!-- Patient Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-injured text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"
                                             x-text="prescription.patient_name"></div>
                                        <div class="text-xs text-gray-500">
                                            MRN: <span x-text="prescription.emrn" class="font-mono"></span>
                                        </div>
                                        <div class="text-xs text-gray-500" x-text="prescription.age"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Medicine -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"
                                     x-text="prescription.medicine_name"></div>
                                <div class="text-xs text-gray-500" x-text="prescription.generic_name"></div>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold"
                                          :class="{
                                            'bg-green-50 text-md-green': prescription.stock_status === 'in_stock',
                                            'bg-orange-50 text-md-orange': prescription.stock_status === 'low_stock',
                                            'bg-red-50 text-md-red': prescription.stock_status === 'out_of_stock'
                                        }">
                                        <i class="fas fa-box mr-1"></i>
                                        <span x-text="prescription.available_stock"></span>  available
                                    </span>
                                </div>
                            </td>

                            <!-- Dosage & Frequency -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900" x-text="prescription.dosage"></div>
                                <div class="text-xs text-gray-500" x-text="prescription.frequency"></div>
                                <div class="text-xs text-gray-500" x-text="prescription.duration"></div>
                                <div class="text-xs text-gray-500 mt-1" x-text="prescription.instructions"></div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full font-bold"
                                      :class="{
                                        'bg-orange-50 text-md-orange': prescription.status === 'pending',
                                        'bg-blue-50 text-md-blue': prescription.status === 'in_progress',
                                        'bg-green-50 text-md-green': prescription.status === 'completed',
                                        'bg-red-50 text-md-red': prescription.status === 'cancelled',
                                        'bg-purple-50 text-md-purple': prescription.status === 'dispensed'
                                    }">
                                    <i class="fas fa-circle text-[8px] mr-1"></i>
                                    <span x-text="prescription.status.replace('_', ' ')" class="capitalize"></span>
                                </span>
                                <div class="text-xs text-gray-500 mt-1" x-show="prescription.dispensed_at">
                                    Dispensed: <span x-text="formatDate(prescription.dispensed_at)"></span>
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="formatDate(prescription.created_at)"></div>
                                <div class="text-xs text-gray-500" x-text="formatTime(prescription.created_at)"></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    By: <span x-text="prescription.doctor_name" class="font-medium"></span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- View Button -->
                                    <button @click="viewPrescription(prescription.id)"
                                            class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </button>

                                    <!-- Dispense Button (if pending) -->
                                    <button
                                        x-show="prescription.status === 'pending' || prescription.status === 'in_progress'"
                                        @click="openDispenseModal(prescription)"
                                        class="text-green-600 hover:text-green-900" title="Dispense">
                                        <i class="fas fa-pills"></i>
                                        Dispense
                                    </button>

                                    <!-- Print Button -->
                                    <button @click="printPrescription(prescription.id)"
                                            class="text-purple-600 hover:text-purple-900" title="Print">
                                        <i class="fas fa-print"></i>
                                        Print
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Loading State -->
                    <tr x-show="loading">
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                <span class="text-gray-600">Loading prescriptions...</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr x-show="!loading && prescriptions.length === 0">
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-prescription-bottle-alt text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No prescriptions found</h3>
                                <p class="text-gray-500">Try adjusting your filters or search term</p>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span x-text="pagination.from"></span> to
                    <span x-text="pagination.to"></span> of
                    <span x-text="pagination.total"></span> results
                </div>
                <div class="flex space-x-2">
                    <button @click="changePage(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1"
                            class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <template x-for="page in pagination.links" :key="page.label">
                        <button @click="changePage(page.label)"
                                :class="{
                            'bg-blue-600 text-white': page.active,
                            'border border-gray-300 text-gray-700': !page.active
                        }"
                                class="px-3 py-1 rounded text-sm"
                                x-text="page.label"
                                :disabled="page.url === null || page.label === '...'">
                        </button>
                    </template>
                    <button @click="changePage(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page"
                            class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- Dispense Modal -->
        <div x-show="showDispenseModal"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4" @click.away="showDispenseModal = false">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Dispense Medication
                    </h3>

                    <div class="space-y-4">
                        <!-- Medicine Info -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-blue-900" x-text="dispenseModal.medicine_name"></h4>
                                    <p class="text-sm text-blue-700" x-text="dispenseModal.generic_name"></p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-blue-600">Available Stock</div>
                                    <div class="text-lg font-bold text-blue-900"
                                         x-text="dispenseModal.available_stock"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Dosage Info -->
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500">Dosage:</span>
                                    <span class="font-medium ml-2" x-text="dispenseModal.dosage"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Frequency:</span>
                                    <span class="font-medium ml-2" x-text="dispenseModal.frequency"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Duration:</span>
                                    <span class="font-medium ml-2" x-text="dispenseModal.duration"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Prescribed:</span>
                                    <span class="font-medium ml-2" x-text="dispenseModal.quantity"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Dispense Form -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Quantity to Dispense
                                </label>
                                <input type="number" x-model="dispenseModal.dispense_quantity"
                                       :max="Math.min(dispenseModal.quantity, dispenseModal.available_stock)"
                                       min="1"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">
                                    Max: <span
                                        x-text="Math.min(dispenseModal.quantity, dispenseModal.available_stock)"></span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Batch Number (Optional)
                                </label>
                                <input type="text" x-model="dispenseModal.batch_number"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Notes (Optional)
                                </label>
                                <textarea x-model="dispenseModal.notes"
                                          rows="3"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button @click="showDispenseModal = false"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button @click="submitDispense"
                                    :disabled="dispenseModal.dispense_quantity <= 0 || dispenseModal.dispense_quantity > Math.min(dispenseModal.quantity, dispenseModal.available_stock)"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Dispense
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function prescriptionManager() {
                return {
                    loading: true,
                    prescriptions: [],
                    activeActionMenu: null,
                    showDispenseModal: false,
                    dispenseModal: {
                        id: null,
                        medicine_name: '',
                        generic_name: '',
                        dosage: '',
                        frequency: '',
                        duration: '',
                        quantity: 0,
                        available_stock: 0,
                        dispense_quantity: 1,
                        batch_number: '',
                        notes: ''
                    },
                    filters: {
                        search: '',
                        status: '',
                        start_date: '',
                        end_date: '',
                        per_page: ''
                    },
                    pagination: {
                        current_page: 1,
                        from: 0,
                        to: 0,
                        total: 0,
                        last_page: 1,
                        links: []
                    },
                    stats: {
                        total: 0,
                        pending: 0,
                        completed: 0,
                        cancelled: 0,
                        today_dispensed: 0,
                        completed_today: 0
                    },
                    bulkAction: '',

                    init() {
                        this.fetchStats();
                        this.fetchPrescriptions();
                    },

                    // Update these fetch calls in the Alpine.js component:

                    fetchStats() {
                        fetch('/pharmacy/dashboard/stats')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                this.stats = data.stats;
                            })
                            .catch(error => {
                                console.error('Error fetching stats:', error);
                                // Set default stats
                                this.stats = {
                                    total: 0,
                                    pending: 0,
                                    completed: 0,
                                    cancelled: 0,
                                    today_dispensed: 0,
                                    completed_today: 0
                                };
                            });
                    },

                    fetchPrescriptions() {
                        this.loading = true;
                        let params = new URLSearchParams({
                            page: this.pagination.current_page,
                            ...this.filters
                        });

                        // Remove empty filters
                        Object.keys(this.filters).forEach(key => {
                            if (!this.filters[key]) {
                                params.delete(key);
                            }
                        });

                        fetch(`/pharmacy/prescriptions/list?${params}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.error) {
                                    throw new Error(data.message);
                                }

                                this.prescriptions = data.data || [];
                                this.pagination = {
                                    current_page: data.current_page || 1,
                                    from: data.from || 0,
                                    to: data.to || 0,
                                    total: data.total || 0,
                                    last_page: data.last_page || 1,
                                    links: data.links || []
                                };
                                this.loading = false;
                            })
                            .catch(error => {
                                console.error('Error fetching prescriptions:', error);
                                showNotification('Failed to load prescriptions. Please try again.', 'error');
                                this.prescriptions = [];
                                this.pagination = {
                                    current_page: 1,
                                    from: 0,
                                    to: 0,
                                    total: 0,
                                    last_page: 1,
                                    links: []
                                };
                                this.loading = false;
                            });
                    },

                    searchPrescriptions() {
                        this.pagination.current_page = 1;
                        this.fetchPrescriptions();
                    },

                    changePage(page) {
                        if (page < 1 || page > this.pagination.last_page) return;
                        this.pagination.current_page = page;
                        this.fetchPrescriptions();
                    },

                    toggleActionMenu(id) {
                        this.activeActionMenu = this.activeActionMenu === id ? null : id;
                    },

                    viewPrescription(id) {
                        window.location.href = `/pharmacy/prescriptions/${id}`;
                    },

                    openDispenseModal(prescription) {
                        this.dispenseModal = {
                            id: prescription.id,
                            medicine_name: prescription.medicine_name,
                            generic_name: prescription.generic_name,
                            dosage: prescription.dosage,
                            frequency: prescription.frequency,
                            duration: prescription.duration,
                            quantity: prescription.quantity,
                            available_stock: prescription.available_stock,
                            dispense_quantity: Math.min(prescription.quantity, prescription.available_stock),
                            batch_number: '',
                            notes: ''
                        };
                        this.showDispenseModal = true;
                    },

                    async submitDispense() {
                        try {
                            const response = await fetch(`/pharmacy/dispense/${this.dispenseModal.id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    dispensed_quantity: this.dispenseModal.dispense_quantity,
                                    batch_number: this.dispenseModal.batch_number,
                                    dispense_notes: this.dispenseModal.notes
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                showNotification(data.message, 'success');
                                this.showDispenseModal = false;
                                this.fetchPrescriptions();
                                this.fetchStats();
                            } else {
                                showNotification(data.message || 'Failed to dispense', 'error');
                            }
                        } catch (error) {
                            showNotification('Error dispensing medication', 'error');
                            console.error('Error:', error);
                        }
                    },

                    printPrescription(id) {
                        window.open(`/print/prescription/${id}`, '_blank');
                    },

                    editPrescription(id) {
                        window.location.href = `/pharmacy/prescriptions/${id}/edit`;
                    },

                    async refillPrescription(id) {
                        if (!confirm('Refill this prescription?')) return;

                        try {
                            const response = await fetch(`/prescriptions/${id}/refill`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                showNotification('Prescription refilled successfully', 'success');
                                this.fetchPrescriptions();
                            } else {
                                showNotification(data.message || 'Failed to refill', 'error');
                            }
                        } catch (error) {
                            showNotification('Error refilling prescription', 'error');
                            console.error('Error:', error);
                        }
                    },

                    viewHistory(patientId) {
                        window.location.href = `/patients/${patientId}`;
                    },

                    async cancelPrescription(id) {
                        if (!confirm('Are you sure you want to cancel this prescription?')) return;

                        try {
                            const response = await fetch(`/pharmacy/prescriptions/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                showNotification('Prescription cancelled', 'success');
                                this.fetchPrescriptions();
                                this.fetchStats();
                            } else {
                                showNotification(data.message || 'Failed to cancel', 'error');
                            }
                        } catch (error) {
                            showNotification('Error cancelling prescription', 'error');
                            console.error('Error:', error);
                        }
                    },

                    exportToCSV() {
                        let params = new URLSearchParams(this.filters);
                        window.open(`/prescriptions/export?${params}`, '_blank');
                    },

                    refreshData() {
                        this.fetchStats();
                        this.fetchPrescriptions();
                    },

                    formatDate(dateString) {
                        if (!dateString) return 'N/A';
                        const date = new Date(dateString);
                        return date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                    },

                    formatTime(dateString) {
                        if (!dateString) return '';
                        const date = new Date(dateString);
                        return date.toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                }
            }
        </script>
    @endpush
@endsection
