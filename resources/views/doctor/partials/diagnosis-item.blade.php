<div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h4 class="font-medium text-gray-900">{{ $diagnosis->diagnosis }}</h4>
            <div class="flex items-center space-x-3 mt-1">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                    {{ $diagnosis->severity == 'mild' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $diagnosis->severity == 'moderate' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $diagnosis->severity == 'severe' ? 'bg-orange-100 text-orange-800' : '' }}
                    {{ $diagnosis->severity == 'critical' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($diagnosis->severity) }}
                </span>

                @if($diagnosis->is_chronic)
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-history mr-1"></i>
                    Chronic
                </span>
                @endif

                @if($diagnosis->is_urgent)
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Urgent
                </span>
                @endif

                @if($diagnosis->follow_up_date)
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    <i class="fas fa-calendar-check mr-1"></i>
                    Follow-up: {{ \Carbon\Carbon::parse($diagnosis->follow_up_date)->format('M d, Y') }}
                </span>
                @endif
            </div>
        </div>

        <div class="flex space-x-2">
            @if($diagnosis->has_prescription && $diagnosis->prescriptions->count() == 0)
                <button onclick="openPrescriptionForm({{ $diagnosis->id }})"
                        class="text-green-600 hover:text-green-800 text-sm">
                    Add
                    <i class="fas fa-prescription mr-1"></i>
                </button>
            @endif

            @if($diagnosis->prescriptions->count() > 0)
                <span class="text-xs text-gray-500">
                {{ $diagnosis->prescriptions->count() }} Rx
            </span>
            @endif
        </div>
    </div>

    @if($diagnosis->doctor_notes)
        <div class="mt-3 pt-3 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                <span class="font-medium">Notes:</span> {{ $diagnosis->doctor_notes }}
            </p>
        </div>
    @endif

    <!-- Prescriptions for this diagnosis -->
    @if($diagnosis->prescriptions->count() > 0)
        <div class="mt-3 pt-3 border-t border-gray-200">
            <p class="text-sm font-medium text-gray-700 mb-2">Prescriptions:</p>
            <div class="space-y-2">
                @foreach($diagnosis->prescriptions as $prescription)
                    <div class="pl-3 border-l-2 border-green-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $prescription->medicine->name }}</p>
                                <p class="text-xs text-gray-600">
                                    {{ $prescription->dosage }} • {{ $prescription->frequency }}
                                    • {{ $prescription->duration }}
                                </p>
                            </div>
                            <span class="text-xs text-gray-500">
                        Qty: {{ $prescription->quantity }}
                    </span>
                        </div>
                        @if($prescription->instructions)
                            <p class="text-xs text-gray-500 mt-1">{{ $prescription->instructions }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
