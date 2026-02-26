<!-- Update Stock Modal -->
<div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-boxes text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Update Stock</h3>
            <p class="text-sm text-gray-500 text-center mb-6">Update inventory for {{ $medicine->name }}</p>
            
            <form action="{{ route('pharmacy.inventory.update-stock', $medicine) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Adjustment Type *</label>
                    <select name="action" id="action" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="add">Stock In (Add)</option>
                        <option value="remove">Stock Out (Deduct)</option>
                        <option value="adjust">Stock Correction</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                    <input type="number" name="quantity" id="quantity" required min="1" step="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter quantity">
                </div>
                
                <div class="mb-4">
                    <label for="batch_number" class="block text-sm font-medium text-gray-700 mb-1">Batch Number</label>
                    <input type="text" name="batch_number" id="batch_number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter batch number">
                </div>
                
                <div class="mb-4">
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ $medicine->expiry_date ? \Carbon\Carbon::parse($medicine->expiry_date)->format('Y-m-d') : '' }}">
                </div>
                
                <div class="mb-4">
                    <label for="unit_cost" class="block text-sm font-medium text-gray-700 mb-1">Unit Cost ($)</label>
                    <input type="number" name="unit_cost" id="unit_cost" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ $medicine->unit_price }}"
                           placeholder="Enter unit cost">
                </div>
                
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter any notes about this stock adjustment..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStockModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-medium">
                        Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showStockModal() {
    document.getElementById('stockModal').classList.remove('hidden');
}

function closeStockModal() {
    document.getElementById('stockModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('stockModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStockModal();
    }
});

// Update quantity placeholder based on adjustment type
document.getElementById('action').addEventListener('change', function() {
    const type = this.value;
    const quantityInput = document.getElementById('quantity');
    const currentStock = {{ $medicine->stock }};
    
    if (type === 'add') {
        quantityInput.placeholder = 'Enter quantity to add';
    } else if (type === 'remove') {
        quantityInput.placeholder = `Enter quantity to deduct (Current: ${currentStock})`;
    } else if (type === 'adjust') {
        quantityInput.placeholder = `Enter correct quantity (Current: ${currentStock})`;
    }
});
</script>