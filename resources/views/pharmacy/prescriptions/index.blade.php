@extends('layouts.app')

@section('title', 'Pending Prescriptions')
@section('page-title', 'Pharmacy Prescriptions')
@section('breadcrumb', 'Pending Prescriptions')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Pending Prescriptions</h2>
            <p class="text-gray-600 mt-1">Review and dispense medications for patients</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <button class="px-4 py-2 bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:shadow transition-all">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <button class="px-4 py-2 bg-gradient-to-r from-green-50 to-green-100 text-green-700 rounded-lg text-sm font-medium hover:shadow transition-all">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>
    </div>

    @if($prescriptions->isEmpty())
    <div class="text-center py-12">
        <div class="text-gray-400 text-6xl mb-4">
            <i class="fas fa-prescription-bottle-alt"></i>
        </div>
        <h3 class="text-xl font-medium text-gray-600 mb-2">No Pending Prescriptions</h3>
        <p class="text-gray-500">All prescriptions have been dispensed.</p>
    </div>
    @else
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Patient & Prescription
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Medicine
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Dosage
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Priority
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Prescribed On
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($prescriptions as $prescription)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-sm">
                                    {{ strtoupper(substr($prescription->diagnosis->visit->patient->name, 0, 2)) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $prescription->diagnosis->visit->patient->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    #{{ $prescription->prescription_number }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $prescription->medicine->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $prescription->medicine->category->name ?? 'Uncategorized' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $prescription->quantity }} {{ $prescription->medicine->unit }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $prescription->frequency }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $prescription->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('pharmacy.prescriptions.show', $prescription) }}" 
                           class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye mr-1"></i> View
                        </a>
                        <a href="{{ route('pharmacy.prescriptions.show', $prescription) }}" 
                           class="text-green-600 hover:text-green-900">
                            <i class="fas fa-pills mr-1"></i> Dispense
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $prescriptions->links() }}
    </div>
    @endif
</div>
@endsection