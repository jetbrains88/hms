@extends('layouts.app')

@section('title', 'Stock Alerts')
@section('page-title', 'Pharmacy Alerts')
@section('breadcrumb', 'Stock Alerts Management')

@section('content')
<div class="space-y-6">
    <!-- Alert Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl p-6 border border-red-100">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $alerts->total() }}</div>
                    <div class="text-sm text-gray-600">Active Alerts</div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-6 border border-yellow-100">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ $alerts->where('alert_type', 'low_stock')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Low Stock Alerts</div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-6 border border-orange-100">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ $alerts->where('alert_type', 'expired')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Expiry Alerts</div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-times text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Stock Alerts</h3>
                    <p class="text-gray-600 mt-1">Manage and resolve inventory alerts</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button class="px-4 py-2 bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:shadow transition-all">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh Alerts
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Medicine
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Alert Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Raised On
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($alerts as $alert)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-pills text-white"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $alert->medicine->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $alert->medicine->category->name ?? 'Uncategorized' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($alert->alert_type == 'low_stock')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-1"></i> Low Stock
                            </span>
                            @elseif($alert->alert_type == 'out_of_stock')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Out of Stock
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800">
                                <i class="fas fa-calendar-times mr-1"></i> Expiring Soon
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if($alert->alert_type == 'low_stock')
                                Current Stock: {{ $alert->medicine->stock }} {{ $alert->medicine->unit }}
                                <div class="text-xs text-gray-500">Minimum: {{ $alert->medicine->min_stock_level }}</div>
                                @elseif($alert->alert_type == 'out_of_stock')
                                Stock Level: 0
                                @else
                                Expiry Date: {{ \Carbon\Carbon::parse($alert->medicine->expiry_date)->format('M d, Y') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $alert->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="showResolveModal({{ $alert->id }})"
                                    class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-check-circle mr-1"></i> Resolve
                            </button>
                            <a href="{{ route('pharmacy.inventory.show', $alert->medicine) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">No Active Alerts</h3>
                            <p class="text-gray-500">All stock levels are within acceptable limits.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($alerts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $alerts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Resolve Alert Modal -->
<div id="resolveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Resolve Alert</h3>
            <p class="text-sm text-gray-500 text-center mb-6">Please provide resolution notes</p>
            
            <form id="resolveForm" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea name="resolution_notes" id="resolution_notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="Enter resolution notes..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-lg font-medium">
                        Confirm Resolution
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showResolveModal(alertId) {
    document.getElementById('resolveForm').action = `/pharmacy/alerts/${alertId}/resolve`;
    document.getElementById('resolveModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('resolveModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('resolveModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
@endsection