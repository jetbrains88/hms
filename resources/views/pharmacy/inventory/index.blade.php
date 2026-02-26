{{-- resources/views/pharmacy/inventory/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventory Management')
@section('page-title', 'Inventory Management')
@section('page-description', 'Manage medicine stock and inventory')

@section('content')
    <div class="space-y-6"
         x-data="inventoryManager()">

        <!-- Header with Actions -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Medicine Inventory</h1>
                    <p class="text-gray-600 mt-1">Track and manage medicine stock levels</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('pharmacy.inventory.create') }}"
                       class="px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg font-bold hover:shadow-lg transition-all flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Medicine
                    </a>
                    <button
                        @click="showBulkUpdateModal()"
                        class="px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-bold hover:shadow-lg transition-all flex items-center">
                        <i class="fas fa-boxes mr-2"></i>
                        Bulk Update
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 justify-between">
                <!-- Length -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Length</label>
                    <select x-model="filters.length"
                            @change="fetchInventory()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="16" selected>16</option>
                        <option value="32">32</option>
                        <option value="64">64</option>
                        <option value="All">All</option>
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <input type="text"
                               x-model.debounce.500ms="filters.search"
                               @input.debounce.500ms="fetchInventory()"
                               placeholder="Name, code, or brand..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select x-model="filters.category"
                            @change="fetchInventory()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="All">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                    <select x-model="filters.stock_status"
                            @change="fetchInventory()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="All">All</option>
                        <option value="low">Low Stock</option>
                        <option value="out">Out of Stock</option>
                        <option value="normal">Normal</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select x-model="filters.sort_by"
                            @change="fetchInventory()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="name">Name (A-Z)</option>
                        <option value="stock">Stock (Low to High)</option>
                        <option value="stock_desc">Stock (High to Low)</option>
                        <option value="expiry_date">Expiry Date</option>
                    </select>
                </div>
                <div class="flex mt-5 items-center">
                    <button @click="resetFilters()" :disabled="!hasActiveFilters()"
                            class="w-full flex items-center justify-center text-white py-2.5
                   text-center bg-gradient-to-r from-rose-500 to-rose-600
                   rounded-lg font-medium hover:from-rose-600 hover:to-rose-700
                   disabled:opacity-50 disabled:cursor-not-allowed transition-all
                   gap-2 shadow-md hover:shadow-lg">
                        <i class="fas fa-filter-circle-xmark"></i>
                        Clear All Filters
                    </button>
                </div>

            </div>
        </div>

        <!-- Stats Summary -->
        <div x-show="stats.total > 0"
             x-transition
             class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-xl">
                    <div class="text-2xl font-bold text-blue-700" x-text="stats.total"></div>
                    <div class="text-sm text-blue-600">Total Medicines</div>
                </div>
                <div class="text-center p-4 bg-emerald-50 rounded-xl">
                    <div class="text-2xl font-bold text-emerald-700"
                         x-text="stats.total - stats.low_stock - stats.out_of_stock"></div>
                    <div class="text-sm text-emerald-600">Normal Stock</div>
                </div>
                <div class="text-center p-4 bg-amber-50 rounded-xl">
                    <div class="text-2xl font-bold text-amber-700" x-text="stats.low_stock"></div>
                    <div class="text-sm text-amber-600">Low Stock</div>
                </div>
                <div class="text-center p-4 bg-rose-50 rounded-xl">
                    <div class="text-2xl font-bold text-rose-700" x-text="stats.out_of_stock"></div>
                    <div class="text-sm text-rose-600">Out of Stock</div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading"
             x-transition
             class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center">
            <div
                class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-emerald-500 mb-4"></div>
            <p class="text-gray-600">Loading inventory...</p>
        </div>

        <!-- Inventory Grid -->
        <div x-show="!loading && medicines.length > 0"
             x-transition
             class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="medicine in medicines" :key="medicine.id">
                <div
                    class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300">
                    <!-- Medicine Header -->
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg truncate" x-text="medicine.name"></h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm text-gray-600" x-text="medicine.code"></span>
                                    <span x-show="medicine.requires_prescription"
                                          class="text-xs px-2 py-0.5 rounded-full bg-purple-100 text-purple-800">
                                        Rx Required
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold"
                                     :class="medicine.stock <= medicine.reorder_level ? 'text-rose-600' : 'text-emerald-600'"
                                     x-text="medicine.stock"></div>
                                <div class="text-xs text-gray-500">in stock</div>
                            </div>
                        </div>
                    </div>

                    <!-- Medicine Details -->
                    <div class="p-6">
                        <!-- Stock Status Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Current Stock</span>
                                <span x-text="'Reorder Level: ' + medicine.reorder_level"></span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full"
                                     :class="medicine.stock_color"
                                     :style="'width: ' + medicine.stock_percentage + '%'"></div>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-gray-50 p-2 rounded">
                                    <div class="text-xs text-gray-500">Strength</div>
                                    <div class="font-medium text-gray-800" x-text="medicine.strength"></div>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <div class="text-xs text-gray-500">Form</div>
                                    <div class="font-medium text-gray-800" x-text="medicine.form"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-gray-50 p-2 rounded">
                                    <div class="text-xs text-gray-500">Category</div>
                                    <div class="font-medium text-gray-800" x-text="medicine.category_name"></div>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <div class="text-xs text-gray-500">Brand</div>
                                    <div class="font-medium text-gray-800" x-text="medicine.brand"></div>
                                </div>
                            </div>

                            <div x-show="medicine.expiry_date"
                                 class="bg-amber-50 border border-amber-200 p-2 rounded">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-xs text-amber-700">Expiry Date</div>
                                        <div class="font-medium text-amber-900"
                                             x-text="medicine.expiry_date"></div>
                                    </div>
                                    <i x-show="medicine.is_about_to_expire"
                                       class="fas fa-exclamation-triangle text-amber-500"></i>
                                </div>
                            </div>

                            <div x-show="medicine.supplier_name"
                                 class="bg-blue-50 border border-blue-200 p-2 rounded">
                                <div class="text-xs text-blue-700">Supplier</div>
                                <div class="font-medium text-blue-900 truncate"
                                     x-text="medicine.supplier_name"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-6 border-t border-gray-100 bg-gray-50">
                        <div class="flex items-center gap-2">
                            <a :href="medicine.view_url"
                               class="flex-1 px-3 py-2 bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-lg font-medium hover:shadow transition-all text-center">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <button @click="showUpdateStockModal(medicine.id, medicine.name)"
                                    class="flex-1 px-3 py-2 bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 rounded-lg font-medium hover:shadow transition-all">
                                <i class="fas fa-edit mr-1"></i> Update
                            </button>
                            <a :href="medicine.edit_url"
                               class="px-3 py-2 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 rounded-lg font-medium hover:shadow transition-all">
                                <i class="fas fa-cog"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && medicines.length === 0"
             x-transition
             class="col-span-full">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center">
                <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No medicines found</h3>
                <p class="text-gray-600">Try adjusting your filters or add a new medicine</p>
            </div>
        </div>

        <!-- Pagination -->
        <div x-show="!loading && pagination.total > pagination.per_page"
             x-transition
             x-html="pagination.links"
             @click.prevent="handlePaginationClick($event)"
             class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        </div>
    </div>

    <!-- Update Stock Modal (keep as is) -->
    <!-- ... existing modal code ... -->
@endsection

@push('scripts')
    <script>
        // Inventory Manager Alpine.js Component
        function inventoryManager() {
            return {
                // State
                medicines: [],
                defaultFilters: {
                    category: 'All',
                    stock_status: 'All',
                    sort_by: 'expiry_date',
                    sort_direction: 'asc',
                    length: 16,
                    search: ''
                },
                filters: @json(json_decode($initialFilters, true)),
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 16,
                    total: 0,
                    links: ''
                },
                stats: {
                    total: 0,
                    low_stock: 0,
                    out_of_stock: 0
                },
                loading: false,
                debounceTimer: null,

                // Initialize - fetch initial data on page load
                init() {
                    this.fetchInventory();
                    this.updateURL();
                },

                // Reset all filters to default values
                resetFilters() {
                    this.filters = {...this.defaultFilters};
                    this.fetchInventory();
                    showNotification('Filters reset to default', 'info');
                },

                // Remove a specific filter
                removeFilter(filterKey) {
                    if (filterKey in this.defaultFilters) {
                        this.filters[filterKey] = this.defaultFilters[filterKey];
                        this.fetchInventory();
                    }
                },

                // Check if a specific filter is active (not default)
                isFilterActive(key) {
                    if (!this.filters || typeof this.filters !== 'object') {
                        return false;
                    }

                    // Check if key exists in filters
                    if (!Object.prototype.hasOwnProperty.call(this.filters, key)) {
                        return false;
                    }

                    const value = this.filters[key];
                    const defaultValues = this.defaultFilters;

                    // Special handling for search
                    if (key === 'search') {
                        return value && value.trim() !== '';
                    }

                    // Special handling for category (0 means "All Categories")
                    if (key === 'category') {
                        return value !== 'All';
                    }

                    // For other filters, check if different from default
                    if (key in defaultValues) {
                        return value !== defaultValues[key];
                    }

                    return false;
                },

                // Check if any filters are active
                hasActiveFilters() {
                    return Object.keys(this.filters).some(key =>
                        this.isFilterActive(key, this.filters[key])
                    );
                },

                // Get human-readable label for active filter
                getFilterLabel(key, value) {
                    if (!value) return '';

                    const labels = {
                        'length': `Show: ${value === 'All' ? 'All Items' : value + ' items'}`,
                        'search': `Search: "${value}"`,
                        'category': this.getCategoryLabel(value),
                        'stock_status': this.getStockStatusLabel(value),
                        'sort_by': this.getSortLabel(value),
                        'sort_direction': `Sort: ${value === 'asc' ? 'Ascending' : 'Descending'}`
                    };

                    return labels[key] || `${key}: ${value}`;
                },

                // Helper methods for filter labels
                getCategoryLabel(categoryId) {
                    // You could fetch category names from a data attribute or API
                    // For now, we'll use a simple approach
                    const categorySelect = document.getElementById('category-filter');
                    if (categorySelect) {
                        const option = categorySelect.querySelector(`option[value="${categoryId}"]`);
                        if (option) return `Category: ${option.textContent}`;
                    }
                    return `Category: ${categoryId}`;
                },

                getStockStatusLabel(status) {
                    const labels = {
                        'low': 'Low Stock',
                        'out': 'Out of Stock',
                        'normal': 'Normal Stock'
                    };
                    return labels[status] || status;
                },

                getSortLabel(sortBy) {
                    const labels = {
                        'name': 'Name (A-Z)',
                        'stock': 'Stock (Low to High)',
                        'stock_desc': 'Stock (High to Low)',
                        'expiry_date': 'Expiry Date'
                    };
                    return `Sort: ${labels[sortBy] || sortBy}`;
                },

                // Fetch inventory data from inventoryList endpoint
                async fetchInventory() {
                    this.loading = true;

                    try {
                        // Build query string from filters
                        const queryString = new URLSearchParams(this.filters).toString();

                        // Use the same endpoint for both initial and filtered data
                        const response = await fetch(`/pharmacy/inventory/list?${queryString}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            showNotification(response.statusText, "error", "Inventory List Loading")
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }

                        const result = await response.json();
                        if (result.success) {
                            this.medicines = result.data;
                            this.pagination = result.pagination;
                            this.stats = result.stats;
                            // Update filters with any server-side adjustments
                            if (result.filters) {
                                this.filters = {...this.filters, ...result.filters};
                            }

                            // Update browser URL
                            this.updateURL();
                        } else {
                            showNotification(result.message || 'Error loading data', 'error');
                        }
                    } catch (error) {
                        console.error('Error fetching inventory:', error);
                        showNotification('Error loading inventory data. Please try again.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                // Handle pagination clicks
                handlePaginationClick(event) {
                    const link = event.target.closest('a');
                    if (!link || !link.href) return;

                    event.preventDefault();

                    const url = new URL(link.href);
                    const page = url.searchParams.get('page');

                    if (page) {
                        // Update page in filters and refetch
                        this.filters.page = page;
                        this.fetchInventory();
                    }
                },

                // Update browser URL without reloading
                updateURL() {
                    // Remove page from filters for URL (we'll use pagination links)
                    const urlFilters = {...this.filters};
                    delete urlFilters.page;

                    const queryString = new URLSearchParams(urlFilters).toString();
                    const newUrl = `${window.location.pathname}?${queryString}`;
                    window.history.pushState({path: newUrl}, '', newUrl);
                },

                // Show bulk update modal
                showBulkUpdateModal() {
                    alert('Bulk update feature coming soon!');
                },

                // Show update stock modal
                showUpdateStockModal(medicineId, medicineName) {
                    // Dispatch event to modal component
                    const event = new CustomEvent('show-stock-modal', {
                        detail: {medicineId, medicineName}
                    });
                    window.dispatchEvent(event);

                    // Fetch current stock for the modal
                    this.fetchMedicineStock(medicineId);
                },

                // Fetch medicine stock for modal
                async fetchMedicineStock(medicineId) {
                    try {
                        const response = await fetch(`/pharmacy/inventory/${medicineId}/stock`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();

                            // Update modal with stock data
                            const event = new CustomEvent('update-stock-data', {
                                detail: {
                                    medicineId,
                                    currentStock: data.stock
                                }
                            });
                            window.dispatchEvent(event);
                        }
                    } catch (error) {
                        console.error('Error fetching stock:', error);
                    }
                },
            }
        }

        // // Handle browser back/forward buttons
        // window.addEventListener('popstate', function (event) {
        //     // If we need to handle browser navigation, we can reload filters from URL
        //     const urlParams = new URLSearchParams(window.location.search);
        //     const inventoryManager = document.querySelector('[x-data="inventoryManager()"]');
        //
        //     if (inventoryManager && inventoryManager.__x) {
        //         const component = inventoryManager.__x.$data;
        //
        //         // Update filters from URL
        //         component.filters.length = urlParams.get('length') || 16;
        //         component.filters.search = urlParams.get('search') || '';
        //         component.filters.category = urlParams.get('category') || 'All';
        //         component.filters.stock_status = urlParams.get('stock_status') || 'All';
        //         component.filters.sort_by = urlParams.get('sort_by') || 'name';
        //         component.filters.sort_direction = urlParams.get('sort_direction') || 'asc';
        //
        //         // Refetch data
        //         component.fetchInventory();
        //     }
        // });
    </script>
@endpush
