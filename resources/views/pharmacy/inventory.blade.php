<x-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Pharmacy Inventory</h1>
        
        <!-- Add Medicine Form -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Add New Medicine</h2>
            <form action="{{ route('pharmacy.medicine.store') }}" method="POST" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @csrf
                <input type="text" name="name" placeholder="Medicine Name" required class="border p-2 rounded">
                <input type="number" name="stock" placeholder="Initial Stock" required class="border p-2 rounded">
                <input type="number" step="0.01" name="price" placeholder="Price" required class="border p-2 rounded">
                <input type="date" name="expiry_date" placeholder="Expiry Date" class="border p-2 rounded">
                <button type="submit" class="col-span-2 bg-green-600 text-white p-2 rounded">Add Medicine</button>
            </form>
        </div>
        
        <!-- Inventory Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-3">Medicine</th>
                        <th class="p-3">Stock</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Expiry</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                    @php
                        $isLowStock = $medicine->stock < $medicine->reorder_level;
                        $isExpired = $medicine->expiry_date && $medicine->expiry_date < today();
                    @endphp
                    <tr class="border-b {{ $isExpired ? 'bg-red-50' : '' }}">
                        <td class="p-3 font-bold">{{ $medicine->name }}</td>
                        <td class="p-3 {{ $isLowStock ? 'text-red-600 font-bold' : '' }}">
                            {{ $medicine->stock }}
                            @if($isLowStock)
                            <span class="text-xs bg-red-100 px-2 py-1 rounded">LOW</span>
                            @endif
                        </td>
                        <td class="p-3">Rs. {{ number_format($medicine->price, 2) }}</td>
                        <td class="p-3 {{ $isExpired ? 'text-red-600' : '' }}">
                            {{ $medicine->expiry_date ? $medicine->expiry_date->format('d M Y') : 'N/A' }}
                        </td>
                        <td class="p-3">
                            @if($isExpired)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">EXPIRED</span>
                            @elseif($isLowStock)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">LOW STOCK</span>
                            @else
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">IN STOCK</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <button onclick="showUpdateStockModal({{ $medicine->id }})" 
                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Update Stock</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>