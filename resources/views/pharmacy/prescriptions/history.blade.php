@extends('layouts.app')

@section('title', 'Dispense History')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div x-data="historyApp()" x-init="init()" x-cloak class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 p-4 md:p-8">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 tracking-tight">
                Dispense History
            </h1>
            <p class="text-gray-500 mt-2 font-medium">Track and manage every medication dispensation with precision.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="fetchData(1)" class="p-3 bg-white shadow-lg rounded-xl text-indigo-600 hover:text-indigo-700 hover:scale-105 transition-all duration-300">
                <i class="fas fa-sync-alt" :class="{ 'animate-spin': loading }"></i>
            </button>
            <div class="px-6 py-3 bg-indigo-600 text-white rounded-2xl shadow-xl shadow-indigo-200 font-bold flex items-center gap-2">
                <i class="fas fa-history"></i>
                Advanced Logs
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <template x-for="(stat, index) in stats" :key="index">
            <div class="bg-white/70 backdrop-blur-xl p-6 rounded-3xl border border-white shadow-xl hover:shadow-2xl transition-all duration-500 group">
                <div class="flex items-center justify-between mb-4">
                    <div :class="stat.bgColor" class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i :class="stat.icon" class="text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider" x-text="stat.label"></span>
                </div>
                <div class="text-3xl font-black text-gray-900 mb-1" x-text="stat.value"></div>
                <div class="text-sm font-medium text-gray-500" x-text="stat.description"></div>
            </div>
        </template>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white/80 backdrop-blur-md p-6 rounded-[2.5rem] border border-white/50 shadow-2xl mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-end">
            <div class="lg:col-span-2 relative">
                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Search Dispensations</label>
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors"></i>
                    <input type="text" 
                           x-model="filters.search" 
                           @input.debounce.500ms="fetchData(1)"
                           placeholder="Patient Name, EMRN, Medicine or Batch..." 
                           class="w-full pl-12 pr-4 py-4 bg-gray-50/50 border-2 border-gray-100 rounded-2xl focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none font-medium">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Date Range</label>
                <div class="flex items-center gap-2">
                    <input type="date" x-model="filters.date_from" @change="fetchData(1)" class="w-full px-3 py-3 bg-gray-50/50 border-2 border-gray-100 rounded-xl focus:border-indigo-500 outline-none text-sm font-semibold">
                    <span class="text-gray-400">to</span>
                    <input type="date" x-model="filters.date_to" @change="fetchData(1)" class="w-full px-3 py-3 bg-gray-50/50 border-2 border-gray-100 rounded-xl focus:border-indigo-500 outline-none text-sm font-semibold">
                </div>
            </div>
            <div class="flex gap-3">
                <button @click="resetFilters()" class="flex-1 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition-all">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table / List -->
    <div class="relative min-h-[300px]">
        <div x-show="loading" class="absolute inset-0 bg-white/50 backdrop-blur-[2px] z-10 flex items-center justify-center rounded-3xl">
            <div class="flex flex-col items-center gap-4">
                <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-indigo-600 font-bold animate-pulse">Scanning Registry...</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <template x-for="item in data.data" :key="item.id">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 p-6 flex flex-col md:flex-row items-center gap-6 group animate-slide-in">
                    <!-- Icon/Status -->
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 flex flex-col items-center justify-center shrink-0 group-hover:from-indigo-100 group-hover:to-purple-100 transition-colors">
                        <i class="fas fa-pills text-2xl text-indigo-600 mb-1"></i>
                        <span class="text-[10px] font-black text-indigo-400 uppercase" x-text="'#' + item.id"></span>
                    </div>

                    <!-- Main Info -->
                    <div class="flex-1 min-w-0 text-center md:text-left">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-2">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-widest" x-text="item.medicine_batch?.batch_number || 'SYSTEM'"></span>
                            <span class="text-xs font-bold text-gray-400" x-text="formatDate(item.dispensed_at)"></span>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 truncate" x-text="item.prescription?.medicine?.name"></h3>
                        <p class="text-sm font-medium text-gray-500">
                            Patient: <span class="text-indigo-600 font-bold" x-text="item.prescription?.diagnosis?.visit?.patient?.name"></span>
                            <span class="mx-2 text-gray-300">|</span>
                            EMRN: <span class="text-gray-900 font-bold" x-text="item.prescription?.diagnosis?.visit?.patient?.emrn"></span>
                        </p>
                    </div>

                    <!-- Quantity & Dispenser -->
                    <div class="flex flex-col items-center md:items-end gap-2 shrink-0">
                        <div class="text-3xl font-black text-indigo-600" x-text="item.quantity_dispensed"></div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Quantity Units</div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-xl mt-1">
                            <div class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-user-check text-[10px] text-indigo-600"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-700" x-text="item.dispensed_by?.name || 'Pharmacist'"></span>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="shrink-0 ml-0 md:ml-4">
                        <button @click="showDetails(item)" class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 hover:bg-indigo-600 hover:text-white transition-all duration-300">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && (!data.data || data.data.length === 0)" class="py-20 flex flex-col items-center">
            <div class="w-32 h-32 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-folder-open text-4xl text-indigo-200"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">No History Found</h3>
            <p class="text-gray-500">Try adjusting your filters or search terms.</p>
        </div>
    </div>

    <!-- Pagination -->
    <div x-show="data.last_page > 1" class="mt-12 flex justify-center items-center gap-3">
        <button @click="fetchData(data.current_page - 1)" :disabled="data.current_page === 1" 
                class="px-8 py-4 bg-white rounded-2xl text-indigo-600 font-black shadow-lg shadow-indigo-100 hover:shadow-indigo-200 disabled:opacity-30 disabled:shadow-none transition-all flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Prev
        </button>
        <div class="px-8 py-4 bg-white rounded-2xl font-black text-indigo-600 shadow-lg shadow-indigo-100 border-2 border-indigo-50">
            <span x-text="data.current_page"></span> <span class="mx-2 text-indigo-200">/</span> <span x-text="data.last_page"></span>
        </div>
        <button @click="fetchData(data.current_page + 1)" :disabled="data.current_page === data.last_page"
                class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black shadow-lg shadow-indigo-200 hover:scale-105 transition-all flex items-center gap-2">
            Next
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<script>
function historyApp() {
    return {
        loading: false,
        data: { data: [], current_page: 1, last_page: 1 },
        stats: [
            { label: 'Total Logs', value: '{{ $stats["total_dispensed"] }}', description: 'Lifetime tracked', icon: 'fas fa-database', bgColor: 'bg-indigo-600' },
            { label: 'Today', value: '{{ $stats["today_dispensed"] }}', description: 'Fresh records', icon: 'fas fa-bolt', bgColor: 'bg-emerald-500' },
            { label: 'Units Vol.', value: '{{ $stats["total_quantity"] }}', description: 'Stock flow', icon: 'fas fa-chart-line', bgColor: 'bg-purple-600' },
        ],
        filters: {
            search: '',
            date_from: '',
            date_to: '',
            per_page: 10
        },

        init() {
            this.fetchData();
        },

        async fetchData(page = 1) {
            if (page < 1 || (this.data.last_page && page > this.data.last_page)) return;
            
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: page,
                    search: this.filters.search,
                    date_from: this.filters.date_from,
                    date_to: this.filters.date_to,
                    per_page: this.filters.per_page
                });
                
                const response = await fetch(`/pharmacy/dispense-history/data?${params.toString()}`, {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                this.data = await response.json();
            } catch (error) {
                console.error("Fetch error:", error);
            } finally {
                this.loading = false;
            }
        },

        resetFilters() {
            this.filters = { search: '', date_from: '', date_to: '', per_page: 10 };
            this.fetchData(1);
        },

        formatDate(dateStr) {
            if (!dateStr) return 'N/A';
            const date = new Date(dateStr);
            return date.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        showDetails(item) {
            Swal.fire({
                title: 'Dispensation Details',
                html: `
                    <div class="text-left space-y-4">
                        <div class="p-4 bg-indigo-50 rounded-2xl">
                            <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Medicine</p>
                            <p class="text-lg font-black text-gray-900">${item.prescription?.medicine?.name}</p>
                            <p class="text-sm font-bold text-indigo-600">${item.medicine_batch?.batch_number || 'Internal Transfer'}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Dispensed Qty</p>
                                <p class="text-xl font-black text-gray-900">${item.quantity_dispensed}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Time</p>
                                <p class="text-xs font-bold text-gray-900">${this.formatDate(item.dispensed_at)}</p>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Notes</p>
                            <p class="text-sm font-medium text-gray-700 italic">${item.notes || 'No special instructions provided'}</p>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-indigo-600 rounded-2xl text-white">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-shield text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">Pharmacist</p>
                                <p class="text-sm font-bold">${item.dispensed_by?.name || 'Authorized Staff'}</p>
                            </div>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                background: '#f8fafc',
                customClass: {
                    popup: 'rounded-[2rem] border-none shadow-2xl overflow-hidden',
                }
            });
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }

@keyframes slide-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-slide-in {
    animation: slide-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
}

/* Custom Scrollbar */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection
