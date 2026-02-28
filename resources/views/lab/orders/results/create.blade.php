@extends('layouts.app')

@section('title', 'Enter Lab Results')

@section('page-title', 'Enter Results - ' . $orderItem->labTestType->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Test Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-slate-400 uppercase">Patient</p>
                <p class="font-medium">{{ $orderItem->labOrder->patient->name }}</p>
                <p class="text-sm text-slate-500">EMRN: {{ $orderItem->labOrder->patient->emrn }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Test</p>
                <p class="font-medium">{{ $orderItem->labTestType->name }}</p>
                <p class="text-sm text-slate-500">Department: {{ $orderItem->labTestType->department }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Ordered By</p>
                <p class="font-medium">Dr. {{ $orderItem->labOrder->doctor->name }}</p>
                <p class="text-sm text-slate-500">{{ $orderItem->labOrder->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Lab Order #</p>
                <p class="font-medium">{{ $orderItem->labOrder->lab_number }}</p>
            </div>
        </div>
    </div>
    
    <form method="POST" action="{{ route('lab.results.store', $orderItem) }}" id="resultsForm">
        @csrf
        
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Enter Results</h3>
            
            @foreach($orderItem->labTestType->parameters as $parameter)
            <div class="mb-6 p-4 border border-slate-100 rounded-xl">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <label class="font-medium text-slate-700">{{ $parameter->name }}</label>
                        @if($parameter->group_name)
                            <span class="ml-2 text-xs text-slate-500">({{ $parameter->group_name }})</span>
                        @endif
                    </div>
                    @if($parameter->unit)
                        <span class="text-sm text-slate-500">Unit: {{ $parameter->unit }}</span>
                    @endif
                </div>
                
                @if($parameter->reference_range)
                <p class="text-xs text-slate-500 mb-3">Reference: {{ $parameter->reference_range }}</p>
                @endif
                
                <div class="flex items-center gap-4">
                    @if($parameter->input_type == 'boolean')
                        <select name="results[{{ $parameter->id }}]" 
                                class="flex-1 px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="1" {{ isset($existingResults[$parameter->id]) && $existingResults[$parameter->id]->boolean_value ? 'selected' : '' }}>Positive</option>
                            <option value="0" {{ isset($existingResults[$parameter->id]) && !$existingResults[$parameter->id]->boolean_value ? 'selected' : '' }}>Negative</option>
                        </select>
                    @elseif($parameter->input_type == 'number')
                        <input type="number" 
                               name="results[{{ $parameter->id }}]" 
                               step="0.01"
                               value="{{ $existingResults[$parameter->id]->numeric_value ?? '' }}"
                               class="flex-1 px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @else
                        <input type="text" 
                               name="results[{{ $parameter->id }}]" 
                               value="{{ $existingResults[$parameter->id]->text_value ?? '' }}"
                               class="flex-1 px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @endif
                </div>
            </div>
            @endforeach
            
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('lab.orders.show', $orderItem->lab_order_id) }}" 
                   class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600">
                    Save Results
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Auto-save functionality
    let form = document.getElementById('resultsForm');
    let inputs = form.querySelectorAll('input, select');
    let autoSaveTimer;
    
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // You could implement auto-save via AJAX here
                console.log('Auto-save triggered');
            }, 2000);
        });
    });
</script>
@endpush