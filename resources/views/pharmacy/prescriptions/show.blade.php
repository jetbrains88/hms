@extends('layouts.app')

@section('title', 'Prescription Details')
@section('page-title', 'Pharmacy Prescriptions')
@section('breadcrumb', 'Prescription Details')

@section('content')
<div class="space-y-6">
    <!-- Prescription Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
            <div>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-prescription text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Prescription #{{ $prescription->prescription_number }}</h2>
                        <p class="text-gray-600">Prescribed by Dr. {{ $prescription->prescriber->name ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-clock mr-1"></i> Pending Dispense
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Patient Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                    <i class="fas fa-user-injured mr-2 text-blue-500"></i>Patient Information
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">
                                {{ strtoupper(substr($prescription->diagnosis->visit->patient->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-bold text-gray-900">{{ $prescription->diagnosis->visit->patient->name }}</div>
                            <div class="text-sm text-gray-600">Patient ID: #{{ $prescription->diagnosis->visit->patient->id }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Age</div>
                            <div class="text-sm text-gray-900">{{ $prescription->diagnosis->visit->patient->age ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Gender</div>
                            <div class="text-sm text-gray-900">{{ $prescription->diagnosis->visit->patient->gender ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Blood Group</div>
                            <div class="text-sm text-gray-900">{{ $prescription->diagnosis->visit->patient->blood_group ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Allergies</div>
                            <div class="text-sm text-gray-900">{{ $prescription->diagnosis->visit->patient->allergies ?? 'None' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescription Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                    <i class="fas fa-file-medical mr-2 text-green-500"></i>Prescription Details
                </h3>
                <div class="space-y-6">
                    <!-- Medicine Details -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-teal-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-pills text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $prescription->medicine->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $prescription->medicine->category->name ?? 'Uncategorized' }}</p>
                            </div>
                            <div class="ml-auto">
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    Stock: {{ $prescription->medicine->stock ?? 0 }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase">Quantity</div>
                                <div class="text-lg font-bold text-gray-900">{{ $prescription->quantity }} {{ $prescription->medicine->unit }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase">Frequency</div>
                                <div class="text-lg font-bold text-gray-900">{{ $prescription->frequency }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase">Duration</div>
                                <div class="text-lg font-bold text-gray-900">{{ $prescription->duration }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase">Priority</div>
                                <div>
                                    @if($prescription->priority == 'high')
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i> High
                                    </span>
                                    @elseif($prescription->priority == 'medium')
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Medium
                                    </span>
                                    @else
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Low
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div>
                        <h4 class="text-md font-semibold text-gray-900 mb-2">Special Instructions</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $prescription->instructions ?? 'No special instructions provided.' }}</p>
                        </div>
                    </div>

                    <!-- Dispense Form -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Dispense Medication</h4>
                        <form action="{{ route('pharmacy.prescriptions.dispense', $prescription) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="batch_number" class="block text-sm font-medium text-gray-700 mb-1">Batch Number *</label>
                                    <input type="text" name="batch_number" id="batch_number" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>
                                <div>
                                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                                    <input type="date" name="expiry_date" id="expiry_date" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>
                                <div>
                                    <label for="dispensed_quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity to Dispense *</label>
                                    <input type="number" name="dispensed_quantity" id="dispensed_quantity" 
                                           value="{{ $prescription->quantity }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea name="notes" id="notes" rows="2"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <a href="{{ route('pharmacy.prescriptions.index') }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-lg font-medium hover:shadow-lg transition-all">
                                    <i class="fas fa-pills mr-2"></i> Dispense Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Inventory History -->
            @if($inventoryLogs->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                    <i class="fas fa-history mr-2 text-purple-500"></i>Recent Inventory Activity
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock Before</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock After</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">By</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($inventoryLogs as $log)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $log->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $log->type == 'in' ? 'bg-green-100 text-green-800' : 
                                           ($log->type == 'out' ? 'bg-red-100 text-red-800' : 
                                            'bg-blue-100 text-blue-800') }}">
                                        {{ $log->type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $log->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $log->previous_stock }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $log->new_stock }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $log->user->name ?? 'System' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection