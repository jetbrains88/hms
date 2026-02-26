{{-- resources/views/pharmacy/inventory/show.blade.php --}}
@extends('layouts.app')

@section('title', $medicine->name)
@section('page-title', 'Medicine Details')
@section('breadcrumb', $medicine->name)

@section('content')
    <div class="space-y-6">
        <!-- Medicine Header -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-xl">
                                    <i class="fas fa-pills text-white text-3xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $medicine->name }}</h1>
                                <div class="flex flex-wrap items-center gap-3 mt-3">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $medicine->code }}
                                </span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">
                                    {{ $medicine->category->name }}
                                </span>
                                    @if($medicine->requires_prescription)
                                        <span
                                            class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                    Prescription Required
                                </span>
                                    @endif
                                    @if($medicine->schedule)
                                        <span
                                            class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                    Schedule {{ $medicine->schedule }}
                                </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:text-right">
                        <div
                            class="text-5xl font-bold {{ $medicine->stock <= $medicine->reorder_level ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $medicine->stock }}
                        </div>
                        <div class="text-sm text-gray-600 mt-1">units in stock</div>
                        <div class="mt-4 flex lg:justify-end gap-3">
                            <button onclick="showUpdateStockModal({{ $medicine->id }}, '{{ $medicine->name }}')"
                                    class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg font-medium hover:shadow-lg transition-all">
                                <i class="fas fa-edit mr-2"></i>Update Stock
                            </button>
                            <a href="{{ route('pharmacy.inventory.edit', $medicine) }}"
                               class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-medium hover:shadow-lg transition-all">
                                <i class="fas fa-cog mr-2"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Status Bar -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span class="font-medium text-gray-700">Stock Status:</span>
                        <span class="ml-2 px-3 py-1 rounded-full text-sm font-medium
                        {{ $medicine->stock_status == 'out_of_stock' ? 'bg-rose-100 text-rose-800' :
                           ($medicine->stock_status == 'low_stock' ? 'bg-orange-100 text-orange-800' : 'bg-emerald-100 text-emerald-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $medicine->stock_status)) }}
                    </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        Reorder Level: <span class="font-bold">{{ $medicine->reorder_level }}</span>
                    </div>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                    @php
                        $percentage = $medicine->reorder_level > 0
                            ? min(100, ($medicine->stock / $medicine->reorder_level) * 100)
                            : 0;
                        $color = $medicine->stock == 0 ? 'bg-rose-500' :
                                ($medicine->stock <= $medicine->reorder_level ? 'bg-orange-500' : 'bg-emerald-500');
                    @endphp
                    <div class="h-full {{ $color }}" style="width: {{ $percentage }}%"></div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
                <div class="space-y-1">
                    <div class="text-sm text-gray-500">Generic Name</div>
                    <div class="font-medium text-gray-900">{{ $medicine->generic_name ?? 'N/A' }}</div>
                </div>
                <div class="space-y-1">
                    <div class="text-sm text-gray-500">Brand</div>
                    <div class="font-medium text-gray-900">{{ $medicine->brand ?? 'N/A' }}</div>
                </div>
                <div class="space-y-1">
                    <div class="text-sm text-gray-500">Strength & Form</div>
                    <div class="font-medium text-gray-900">{{ $medicine->strength }} {{ $medicine->form }}</div>
                </div>
                <div class="space-y-1">
                    <div class="text-sm text-gray-500">Unit</div>
                    <div class="font-medium text-gray-900">{{ $medicine->unit }}</div>
                </div>
                <div class="space-y-1">
                    <div class="text-sm text-gray-500">Cost Price</div>
                    <div class="font-medium text-gray-900">{{ number_format($medicine->cost_price, 2) }}</div>
                </div>
                <div class="space-y-1">
                    <div class="text-sm text-gray-500">Selling Price</div>
                    <div class="font-medium text-gray-900">{{ number_format($medicine->selling_price, 2) }}</div>
                </div>
                <div class="space-y-1">
                    <div class="text-sm text-gray-500">Tax Rate</div>
                    <div class="font-medium text-gray-900">{{ $medicine->tax_rate ?? 0 }}%</div>
                </div>
                <div class="space-y-1">
                    <div class="text-sm text-gray-500">Minimum Order Qty</div>
                    <div class="font-medium text-gray-900">{{ $medicine->minimum_order_quantity ?? 'N/A' }}</div>
                </div>
                @if($medicine->supplier)
                    <div class="space-y-1">
                        <div class="text-sm text-gray-500">Supplier</div>
                        <div class="font-medium text-gray-900">{{ $medicine->supplier->name }}</div>
                        <div class="text-xs text-gray-500">{{ $medicine->supplier->contact_person }}
                            • {{ $medicine->supplier->phone }}</div>
                    </div>
                @endif
                @if($medicine->expiry_date)
                    <div class="space-y-1">
                        <div class="text-sm text-gray-500">Expiry Date</div>
                        <div
                            class="font-medium {{ $medicine->isExpired() ? 'text-red-600': ($medicine->isAboutToExpire() ? 'text-amber-600' : 'text-green-900') }}">
                            {{ $medicine->expiry_date->format('d M Y') }}
                            @if($medicine->isExpired())
                                <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                                    Expired
                                </span>
                            @elseif($medicine->isAboutToExpire())
                                <span class="ml-2 text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">
                                    Expiring soon
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
                @if($medicine->manufacture_date)
                    <div class="space-y-1">
                        <div class="text-sm text-gray-500">Manufacture Date</div>
                        <div class="font-medium text-gray-900">{{ $medicine->manufacture_date->format('d M Y') }}</div>
                    </div>
                @endif
                @if($medicine->batch_number)
                    <div class="space-y-1">
                        <div class="text-sm text-gray-500">Batch Number</div>
                        <div class="font-medium text-gray-900">{{ $medicine->batch_number }}</div>
                    </div>
                @endif
                @if($medicine->storage_conditions)
                    <div class="space-y-1">
                        <div class="text-sm text-gray-500">Storage Conditions</div>
                        <div class="font-medium text-gray-900">{{ $medicine->storage_conditions }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
            <!-- Tab Headers -->
            <div class="border-b border-gray-100">
                <nav class="flex -mb-px">
                    <button id="inventory-tab"
                            class="tab-button active py-4 px-6 font-medium text-gray-700 border-b-2 border-blue-500">
                        Inventory History
                    </button>
                    <button id="dispense-tab"
                            class="tab-button py-4 px-6 font-medium text-gray-500 hover:text-gray-700">
                        Dispense History
                    </button>
                    <button id="details-tab"
                            class="tab-button py-4 px-6 font-medium text-gray-500 hover:text-gray-700">
                        Additional Details
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Inventory History Tab -->
                <div id="inventory-content" class="tab-content active">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Previous Stock
                                </th>
                                <th class="px6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    New Stock
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @forelse($inventoryLogs as $log)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $log->type == 'add' ? 'bg-emerald-100 text-emerald-800' :
                                           ($log->type == 'remove' ? 'bg-rose-100 text-rose-800' :
                                           ($log->type == 'dispense' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($log->type) }}
                                    </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="font-medium {{ $log->quantity > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                                    </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->previous_stock }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $log->new_stock }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->user->name ?? 'System' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        {{ $log->notes }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-history text-3xl text-gray-300 mb-4"></i>
                                        <p>No inventory history available</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($inventoryLogs->hasPages())
                        <div class="mt-6">
                            {{ $inventoryLogs->links() }}
                        </div>
                    @endif
                </div>

                <!-- Dispense History Tab -->
                <div id="dispense-content" class="tab-content hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    MRN
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Doctor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dispensed By
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @forelse($dispenseHistory as $prescription)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $prescription->dispensed_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $prescription->diagnosis->visit->patient->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $prescription->diagnosis->visit->patient->emrn }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                        {{ $prescription->dispensed_quantity }}
                                    </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $prescription->prescriber->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $prescription->dispenser->name ?? 'Unknown' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-pills text-3xl text-gray-300 mb-4"></i>
                                        <p>No dispense history available</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Additional Details Tab -->
                <div id="details-content" class="tab-content hidden">
                    <div class="space-y-6">
                        @if($medicine->description)
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">Description</h4>
                                <div class="prose max-w-none">
                                    {{ $medicine->description }}
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="font-semibold text-gray-900 mb-4">Inventory Information</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Last Restocked</span>
                                        <span class="font-medium">
                                        {{ $medicine->last_restocked_at ? $medicine->last_restocked_at->diffForHumans() : 'Never' }}
                                    </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Inventory Value</span>
                                        <span class="font-medium">
                                        {{ number_format($medicine->stock * $medicine->cost_price, 2) }}
                                    </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status</span>
                                        <span
                                            class="font-medium {{ $medicine->is_active ? 'text-emerald-600' : 'text-gray-600' }}">
                                        {{ $medicine->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="font-semibold text-gray-900 mb-4">Quick Actions</h4>
                                <div class="space-y-3">
                                    <button onclick="showUpdateStockModal({{ $medicine->id }}, '{{ $medicine->name }}')"
                                            class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-medium hover:shadow-lg transition-all text-center">
                                        <i class="fas fa-box mr-2"></i>Update Stock
                                    </button>
                                    <a href="{{ route('pharmacy.inventory.edit', $medicine) }}"
                                       class="block px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg font-medium hover:shadow-lg transition-all text-center">
                                        <i class="fas fa-edit mr-2"></i>Edit Medicine
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include update stock modal from inventory index -->
    @include('pharmacy.inventory.partials.update-stock-modal')
@endsection

@push('scripts')
    <script>
        // Tab switching
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function () {
                // Update active tab
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'border-blue-500', 'text-gray-700');
                    btn.classList.add('text-gray-500');
                });

                this.classList.add('active', 'border-blue-500', 'text-gray-700');
                this.classList.remove('text-gray-500');

                // Show active content
                const tabId = this.id.replace('-tab', '-content');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                });

                document.getElementById(tabId).classList.remove('hidden');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Include update stock modal functionality
        function showUpdateStockModal(medicineId, medicineName) {
            currentMedicineId = medicineId;
            document.getElementById('modalMedicineName').textContent = medicineName;
            document.getElementById('updateStockForm').action = `/pharmacy/inventory/${medicineId}/update-stock`;
            document.getElementById('updateStockModal').classList.remove('hidden');
            document.getElementById('updateStockModal').classList.add('flex');

            fetch(`/api/pharmacy/medicines/${medicineId}/stock`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('currentStock').textContent = data.stock;
                });
        }

        function closeUpdateStockModal() {
            document.getElementById('updateStockModal').classList.add('hidden');
            document.getElementById('updateStockModal').classList.remove('flex');
        }
    </script>
@endpush
