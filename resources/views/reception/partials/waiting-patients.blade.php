<div class="divide-y divide-gray-100">
    @forelse($waitingPatientsList as $visit)
        <div class="p-4 hover:bg-orange-50 transition-colors cursor-pointer waiting-patient-item"
             data-visit-id="{{ $visit->id }}" data-patient-name="{{ $visit->patient->name }}"
             data-emrn="{{ $visit->patient->emrn }}" data-token="{{ $visit->queue_token }}">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-injured text-orange-600"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-gray-800 truncate">{{ $visit->patient->name }}</h4>
                        <span class="text-xs font-mono bg-gray-100 text-gray-600 px-2 py-1 rounded">
                            {{ $visit->queue_token }}
                        </span>
                    </div>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ formatPhone($visit->patient->phone) }}</span>
                        @if ($visit->patient->gender)
                            <span
                                class="text-xs px-2 py-0.5 rounded-full {{ $visit->patient->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($visit->patient->gender) }}
                            </span>
                        @endif
                    </div>
                    @if ($visit->latestVital)
                        <div class="mt-2 grid grid-cols-5 gap-2 text-xs"
                             data-vitals-temp="{{ $visit->latestVital->temperature }}"
                             data-vitals-pulse="{{ $visit->latestVital->pulse }}"
                             data-vitals-bgl="{{ $visit->latestVital->blood_glucose }}"
                             data-vitals-so2="{{ $visit->latestVital->oxygen_saturation }}"
                             data-vitals-bps="{{ $visit->latestVital->blood_pressure_systolic }}"
                             data-vitals-bpd="{{ $visit->latestVital->blood_pressure_diastolic }}">
                            <!-- Temperature -->
                            <div class="relative group">
                                <div class="bg-gray-50 p-1 rounded text-center cursor-help
                                    {{ $visit->latestVital->temperature >= 38 ? 'border border-red-200 bg-red-50' :
                                       ($visit->latestVital->temperature >= 37.5 ? 'border border-yellow-200 bg-yellow-50' : '') }}">
                                    <span class="font-bold
                                        {{ $visit->latestVital->temperature >= 38 ? 'text-red-700' :
                                           ($visit->latestVital->temperature >= 37.5 ? 'text-yellow-700' : 'text-gray-700') }}">
                                        {{ $visit->latestVital->temperature ?? '--' }}°
                                    </span>
                                    @if($visit->latestVital->temperature)
                                        <i class="fas fa-thermometer-half ml-1
                                            {{ $visit->latestVital->temperature >= 38 ? 'text-red-500' :
                                               ($visit->latestVital->temperature >= 37.5 ? 'text-yellow-500' : 'text-blue-400') }}"></i>
                                    @endif
                                    <div class="text-gray-500">Temp</div>
                                </div>
                            </div>
                            <!-- Blood Glucose -->
                            <div class="relative group">
                                <div class="bg-gray-50 p-1 rounded text-center cursor-help
                                    {{ $visit->latestVital->blood_glucose >= 200 ? 'border border-red-200 bg-red-50' :
                                       ($visit->latestVital->blood_glucose >= 140 ? 'border border-yellow-200 bg-yellow-50' : '') }}">
                                    <span class="font-bold
                                        {{ $visit->latestVital->blood_glucose >= 200 ? 'text-red-700' :
                                           ($visit->latestVital->blood_glucose >= 140 ? 'text-yellow-700' : 'text-gray-700') }}">
                                        {{ $visit->latestVital->blood_glucose ?? '--' }}
                                    </span>
                                    @if($visit->latestVital->blood_glucose)
                                        <i class="fas fa-tint ml-1
                                            {{ $visit->latestVital->blood_glucose >= 200 ? 'text-red-500' :
                                               ($visit->latestVital->blood_glucose >= 140 ? 'text-yellow-500' : 'text-green-400') }}"></i>
                                    @endif
                                    <div class="text-gray-500">BGL</div>
                                </div>
                            </div>
                            <!-- Oxygen -->
                            <div class="relative group">
                                <div class="bg-gray-50 p-1 rounded text-center cursor-help
                                    {{ $visit->latestVital->oxygen_saturation <= 92 ? 'border border-red-200 bg-red-50' :
                                       ($visit->latestVital->oxygen_saturation <= 95 ? 'border border-yellow-200 bg-yellow-50' : '') }}">
                                    <span class="font-bold
                                        {{ $visit->latestVital->oxygen_saturation <= 92 ? 'text-red-700' :
                                           ($visit->latestVital->oxygen_saturation <= 95 ? 'text-yellow-700' : 'text-gray-700') }}">
                                        {{ $visit->latestVital->oxygen_saturation ?? '--' }}
                                    </span>
                                    @if($visit->latestVital->oxygen_saturation)
                                        <i class="fas fa-lungs ml-1
                                            {{ $visit->latestVital->oxygen_saturation <= 92 ? 'text-red-500' :
                                               ($visit->latestVital->oxygen_saturation <= 95 ? 'text-yellow-500' : 'text-green-400') }}"></i>
                                    @endif
                                    <div class="text-gray-500">SpO₂</div>
                                </div>
                            </div>
                            <!-- Pulse -->
                            <div class="relative group">
                                <div class="bg-gray-50 p-1 rounded text-center cursor-help
                                    {{ $visit->latestVital->pulse >= 120 ? 'border border-red-200 bg-red-50' :
                                       ($visit->latestVital->pulse >= 100 ? 'border border-amber-200 bg-amber-50' : 'border border-emerald-200 bg-emerald-50') }}">
                                    <span class="font-bold flex items-center justify-center
                                        {{ $visit->latestVital->pulse >= 120 ? 'text-red-700' :
                                           ($visit->latestVital->pulse >= 100 ? 'text-amber-700' : 'text-emerald-700') }}">
                                        {{ $visit->latestVital->pulse ?? '--' }}
                                        @if($visit->latestVital->pulse >= 100)
                                            <i class="fas fa-heartbeat ml-1
                                                {{ $visit->latestVital->pulse >= 120 ? 'text-red-500 animate-pulse' : 'text-amber-500 animate-pulse' }}"></i>
                                        @elseif($visit->latestVital->pulse)
                                            <i class="fas fa-heart-pulse ml-1 text-emerald-400 animate-pulse"></i>
                                        @endif
                                    </span>
                                    <div class="text-gray-500">Pulse</div>
                                </div>
                            </div>
                            <!-- Blood Pressure -->
                            <div class="relative group">
                                <div class="bg-gray-50 p-1 rounded text-center cursor-help
                                    {{ ($visit->latestVital->blood_pressure_systolic >= 140 || $visit->latestVital->blood_pressure_diastolic >= 90) ? 'border border-red-200 bg-red-50' :
                                       (($visit->latestVital->blood_pressure_systolic >= 130 || $visit->latestVital->blood_pressure_diastolic >= 85) ? 'border border-yellow-200 bg-yellow-50' : '') }}">
                                    <span class="font-bold
                                        {{ ($visit->latestVital->blood_pressure_systolic >= 140 || $visit->latestVital->blood_pressure_diastolic >= 90) ? 'text-red-700' :
                                           (($visit->latestVital->blood_pressure_systolic >= 130 || $visit->latestVital->blood_pressure_diastolic >= 85) ? 'text-yellow-700' : 'text-gray-700') }}">
                                        {{ $visit->latestVital->blood_pressure_systolic ?? '--' }}/{{ $visit->latestVital->blood_pressure_diastolic ?? '--' }}
                                    </span>
                                    <div class="text-gray-500">BP</div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold
                            @if ($visit->status == 'waiting')
                                {{ $visit->created_at->diffInMinutes(now()) > 30 ? 'bg-red-100 text-red-800 border border-red-300 animate-pulse' : 'bg-yellow-100 text-yellow-800' }}
                            @elseif($visit->status == 'in_progress')
                                bg-blue-100 text-blue-800
                            @elseif($visit->status == 'completed')
                                bg-green-100 text-green-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif">
                            <i class="fas fa-circle text-xs mr-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                            @if($visit->status == 'waiting' && $visit->created_at->diffInMinutes(now()) > 45)
                                <i class="fas fa-exclamation-triangle ml-1"></i>
                            @endif
                        </span>
                        <span class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $visit->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                <i class="fas fa-user-clock text-4xl"></i>
            </div>
            <p class="text-gray-500">No patients in waiting room</p>
        </div>
    @endforelse
</div>
