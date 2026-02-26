@extends('layouts.app')

@section('title', 'Medicine Management')
@section('page-title', 'Medicine Management')
@section('breadcrumb', 'Pharmacy / Medicines')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900">Medicines</h2>
        <a href="{{ route('pharmacy.medicines.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Medicine
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" placeholder="Search medicines..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
                <div class="flex gap-2">
                    <select class="block w-full py-2 pl-3 pr-10 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option>All Categories</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($medicines ?? [] as $medicine)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mr-3">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $medicine->generic_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $medicine->category->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($medicine->total_stock ?? 0) <= ($medicine->reorder_level ?? 0) ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $medicine->total_stock ?? 0 }} {{ $medicine->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            No medicines found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($medicines) && method_exists($medicines, 'links'))
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $medicines->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
