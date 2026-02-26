@extends('layouts.app')

@section('title', 'Add New Medicine')
@section('page-title', 'Pharmacy Inventory')
@section('breadcrumb', 'Add New Medicine')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">Add New Medicine</h2>
                <p class="text-gray-600 mt-1">Fill in the details below to add a new medicine to the inventory</p>
            </div>

            <form action="{{ route('pharmacy.inventory.store') }}" method="POST">
                @csrf

                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ADD THIS: Code field -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                                Medicine Code *
                            </label>
                            <input type="text" name="code" id="code" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., MED-001">
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Medicine Name *
                            </label>
                            <input type="text" name="name" id="name" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="generic_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Generic Name
                            </label>
                            <input type="text" name="generic_name" id="generic_name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- ADD THIS: Brand field -->
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">
                                Brand
                            </label>
                            <input type="text" name="brand" id="brand"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Category *
                            </label>
                            <select name="category_id" id="category_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="manufacturer" class="block text-sm font-medium text-gray-700 mb-1">
                                Manufacturer
                            </label>
                            <input type="text" name="manufacturer" id="manufacturer"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- ADD THIS: Form field (required) -->
                        <div>
                            <label for="form" class="block text-sm font-medium text-gray-700 mb-1">
                                Form *
                            </label>
                            <select name="form" id="form" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Form</option>
                                <option value="tablet">Tablet</option>
                                <option value="capsule">Capsule</option>
                                <option value="syrup">Syrup</option>
                                <option value="injection">Injection</option>
                                <option value="ointment">Ointment</option>
                                <option value="cream">Cream</option>
                                <option value="drops">Drops</option>
                                <option value="inhaler">Inhaler</option>
                                <option value="powder">Powder</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- ADD THIS: Strength field (required) -->
                        <div>
                            <label for="strength" class="block text-sm font-medium text-gray-700 mb-1">
                                Strength *
                            </label>
                            <input type="text" name="strength" id="strength" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., 500mg, 10ml, 5%">
                        </div>
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                        <i class="fas fa-boxes mr-2 text-green-500"></i>Stock Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                                Initial Stock *
                            </label>
                            <input type="number" name="stock" id="stock" required min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">
                                Unit *
                            </label>
                            <input type="text" name="unit" id="unit" required
                                   placeholder="e.g., tablets, ml, mg"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- CHANGE THIS: Rename min_stock_level to reorder_level and make required -->
                        <div>
                            <label for="reorder_level" class="block text-sm font-medium text-gray-700 mb-1">
                                Reorder Level *
                            </label>
                            <input type="number" name="reorder_level" id="reorder_level" required min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Alert when stock reaches this level">
                        </div>
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                        <i class="fas fa-tag mr-2 text-purple-500"></i>Pricing Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">
                                Unit Price *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" name="unit_price" id="unit_price" required step="0.01" min="0"
                                       class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-1">
                                Selling Price *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" name="selling_price" id="selling_price" required step="0.01"
                                       min="0"
                                       class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                        <i class="fas fa-notes-medical mr-2 text-orange-500"></i>Additional Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="batch_number" class="block text-sm font-medium text-gray-700 mb-1">
                                Batch Number
                            </label>
                            <input type="text" name="batch_number" id="batch_number"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Expiry Date
                            </label>
                            <input type="date" name="expiry_date" id="expiry_date"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- ADD THIS: Storage Conditions -->
                        <div>
                            <label for="storage_conditions" class="block text-sm font-medium text-gray-700 mb-1">
                                Storage Conditions
                            </label>
                            <input type="text" name="storage_conditions" id="storage_conditions"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., Room temperature, Refrigerated">
                        </div>

                        <!-- ADD THIS: Prescription Requirement -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Prescription Requirement
                            </label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="requires_prescription" value="1"
                                           class="form-radio text-blue-600">
                                    <span class="ml-2 text-gray-700">Requires Prescription</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="requires_prescription" value="0" checked
                                           class="form-radio text-blue-600">
                                    <span class="ml-2 text-gray-700">Over-the-Counter</span>
                                </label>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('pharmacy.inventory') }}"
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-8 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-medium hover:shadow-lg transition-all">
                        <i class="fas fa-plus-circle mr-2"></i> Add Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
