@extends('layouts.app')

@section('title', 'Lab Order Details')

@section('page-title', 'Lab Order #' . $labOrder->lab_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Order Information</h3>
                <div class="flex gap-2">
                    @if($labOrder->priority == 'urgent')
                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-sm">Urgent</span>
                    @endif
                    <span class="px-3 py-1 
                        @if($labOrder->status == 'pending') bg-amber-100 text-amber-600
                        @elseif($labOrder->status == 'processing') bg-blue-100 text-blue-600
                        @else bg-green-100 text-green-600 @endif 
                        rounded-full text-sm">
                        {{ ucfirst($labOrder->status) }}
                    </span>
                    @if($labOrder->is_verified)
                        <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-sm">Verified</span>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-400 uppercase">Patient</p>
                    <p class="font-medium text-slate-800">{{ $labOrder->patient?->name ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-500">EMRN: {{ $labOrder->patient?->emrn ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-500">DOB: {{ $labOrder->patient?->dob ? $labOrder->patient->dob->format('d M Y') : 'N/A' }}</p>
                </div>
                
                <div>
                    <p class="text-xs text-slate-400 uppercase">Ordered By</p>
                    <p class="font-medium text-slate-800">Dr. {{ $labOrder->doctor?->name ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-500">{{ $labOrder->created_at?->format('d M Y H:i') }}</p>
                </div>
                
                @if($labOrder->collection_date)
                <div>
                    <p class="text-xs text-slate-400 uppercase">Collection Date</p>
                    <p class="font-medium text-slate-800">{{ $labOrder->collection_date->format('d M Y H:i') }}</p>
                </div>
                @endif
                
                @if($labOrder->reporting_date)
                <div>
                    <p class="text-xs text-slate-400 uppercase">Reporting Date</p>
                    <p class="font-medium text-slate-800">{{ $labOrder->reporting_date->format('d M Y H:i') }}</p>
                </div>
                @endif
                
                @if($labOrder->device_name)
                <div>
                    <p class="text-xs text-slate-400 uppercase">Device</p>
                    <p class="font-medium text-slate-800">{{ $labOrder->device_name }}</p>
                </div>
                @endif
                
                @if($labOrder->comments)
                <div class="col-span-2">
                    <p class="text-xs text-slate-400 uppercase">Comments</p>
                    <p class="text-sm text-slate-700">{{ $labOrder->comments }}</p>
                </div>
                @endif
                
                @if($labOrder->verified_at)
                <div class="col-span-2">
                    <p class="text-xs text-slate-400 uppercase">Verified By</p>
                    <p class="font-medium text-slate-800">{{ $labOrder->verifiedBy->name ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-500">{{ $labOrder->verified_at->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Test Items -->
        @foreach($labOrder->items as $item)
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="text-md font-semibold text-slate-800">{{ $item->labTestType->name }}</h4>
                    <p class="text-xs text-slate-500">Department: {{ $item->labTestType->department }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 
                        @if($item->status == 'pending') bg-amber-100 text-amber-600
                        @elseif($item->status == 'processing') bg-blue-100 text-blue-600
                        @else bg-green-100 text-green-600 @endif 
                        rounded-full text-xs">
                        {{ ucfirst($item->status) }}
                    </span>
                    
                    @if($item->technician)
                        <span class="text-xs text-slate-500">
                            <i class="fas fa-user mr-1"></i>{{ $item->technician->name }}
                        </span>
                    @endif
                    
                    @if($item->status == 'pending' && auth()->user()->hasRole('lab'))
                        <form method="POST" action="{{ route('lab.orders.start-item', $item) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-xl text-sm hover:bg-blue-600">
                                Start
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($item->status, ['processing', 'completed']) && auth()->user()->hasRole('lab'))
                        <a href="{{ route('lab.results.create', $item) }}" 
                           class="px-3 py-1 bg-purple-500 text-white rounded-xl text-sm hover:bg-purple-600">
                            <i class="fas fa-edit mr-1"></i>Enter Results
                        </a>
                    @endif
                </div>
            </div>
            
            @if($item->labResults->count() > 0)
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-slate-500">Parameter</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-slate-500">Result</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-slate-500">Reference Range</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-slate-500">Unit</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($item->labResults as $result)
                        <tr>
                            <td class="px-3 py-2">{{ $result->labTestParameter->name }}</td>
                            <td class="px-3 py-2 font-medium">{{ $result->display_value }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $result->labTestParameter->reference_range ?? '-' }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $result->labTestParameter->unit ?? '-' }}</td>
                            <td class="px-3 py-2">
                                @if($result->is_abnormal)
                                    <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs">Abnormal</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    
    <!-- Actions Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        @if(!$labOrder->is_verified && $labOrder->status == 'completed' && auth()->user()->hasPermission('verify_lab_reports'))
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Verify Report</h3>
            <form method="POST" action="{{ route('lab.orders.verify', $labOrder) }}">
                @csrf
                <textarea name="verification_notes" 
                          rows="3" 
                          class="w-full px-4 py-2 border border-slate-200 rounded-xl mb-4"
                          placeholder="Verification notes (optional)"></textarea>
                <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600">
                    <i class="fas fa-check-circle mr-2"></i>Verify Report
                </button>
            </form>
        </div>
        @endif
        
        @if($labOrder->status == 'completed' && $labOrder->is_verified)
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Report Actions</h3>
            <a href="{{ route('lab.orders.print', $labOrder) }}" 
               target="_blank"
               class="block w-full px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 text-center mb-3">
                <i class="fas fa-print mr-2"></i>Print Report
            </a>
            <a href="{{ route('lab.reports.pdf', $labOrder) }}" 
               class="block w-full px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 text-center">
                <i class="fas fa-download mr-2"></i>Download PDF
            </a>
        </div>
        @endif
    </div>
</div>
@endsection