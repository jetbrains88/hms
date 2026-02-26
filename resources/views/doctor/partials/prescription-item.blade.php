<div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-pills text-green-600"></i>
            </div>
            <div>
                <h4 class="font-medium text-gray-900">{{ $prescription->medicine->name }}</h4>
                <p class="text-xs text-gray-500">
                    {{ $prescription->medicine->strength }} {{ $prescription->medicine->unit }}
                    • Generic: {{ $prescription->medicine->generic_name }}
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <span class="text-xs px-2 py-1 rounded-full
                {{ $prescription->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  ($prescription->status == 'dispensed' ? 'bg-green-100 text-green-800' :
                  'bg-red-100 text-red-800' )}}">
                {{ ucfirst($prescription->status) }}
            </span>

            @if($prescription->priority)
                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                {{ ucfirst($prescription->priority) }}
            </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
        <div>
            <p class="text-xs text-gray-500">Dosage</p>
            <p class="text-sm font-medium text-gray-900">{{ $prescription->dosage }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Frequency</p>
            <p class="text-sm font-medium text-gray-900">{{ $prescription->frequency }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Duration</p>
            <p class="text-sm font-medium text-gray-900">{{ $prescription->duration }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Quantity</p>
            <p class="text-sm font-medium text-gray-900">{{ $prescription->quantity }}</p>
        </div>
    </div>

    @if($prescription->instructions)
        <div class="mb-3">
            <p class="text-xs text-gray-500">Instructions</p>
            <p class="text-sm text-gray-700">{{ $prescription->instructions }}</p>
        </div>
    @endif

    <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
        <div class="text-xs text-gray-500">
            {{--            Refills: {{ $prescription->refills_used }}/{{ $prescription->refills_allowed }}--}}
        </div>

        @if($prescription->prescriber)
            <div class="text-xs text-gray-500">
                Prescribed by: {{ $prescription->prescriber->name }}
            </div>
        @endif
    </div>
</div>
