@extends('layouts.app')

@section('title', 'Consultation')
@section('page-title', 'Consultation - ' . $visit->patient->name)
@section('page-description', 'Patient consultation and diagnosis')

@section('content')
    <div class="h-[calc(100vh-140px)] flex flex-col gap-4 overflow-hidden" 
         x-data="consultationData()">
        
        <!-- Sticky Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100 shrink-0">
                    <i class="fas fa-user-injured text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ $visit->patient->name }}</h2>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $visit->patient->emrn }}</span>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $visit->patient->age_formatted }}</span>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $visit->patient->gender }}</span>
                        @if ($visit->patient->is_nhmp)
                            <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded flex items-center gap-1">
                                <i class="fas fa-shield-alt text-[10px]"></i> NHMP
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Status & Time -->
                <div class="hidden md:flex flex-col items-end">
                    <div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Visit Time</div>
                    <div class="text-sm font-bold text-gray-700">{{ $visit->created_at->format('h:i A') }}</div>
                </div>
                
                <div class="h-10 w-px bg-gray-100"></div>

                <div class="flex items-center gap-2">
                    <button @click="historyModalOpen = true" 
                            class="flex items-center gap-2 px-4 py-2 bg-gray-50 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                        <i class="fas fa-history text-blue-500"></i>
                        <span class="hidden sm:inline">Medical History</span>
                    </button>
                    
                    @if ($visit->status === 'in_progress')
                        <button onclick="showCompleteModal()"
                                class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-emerald-200 transition-all border-b-4 border-emerald-700 active:border-b-0 active:translate-y-1">
                            <i class="fas fa-check-circle"></i>
                            <span>Complete Visit</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Workspace: Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex-1 flex flex-col overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex border-b border-gray-100 bg-gray-50/50 p-1">
                <button @click="activeTab = 'vitals'" 
                        :class="activeTab === 'vitals' ? 'bg-white text-blue-600 shadow-sm border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50'"
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2 border border-transparent">
                    <i class="fas fa-heartbeat"></i> Vitals
                </button>
                <button @click="activeTab = 'notes'" 
                        :class="activeTab === 'notes' ? 'bg-white text-blue-600 shadow-sm border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50'"
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2 border border-transparent">
                    <i class="fas fa-file-medical"></i> Notes & Diagnosis
                </button>
                <button @click="activeTab = 'prescriptions'" 
                        :class="activeTab === 'prescriptions' ? 'bg-white text-blue-600 shadow-sm border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50'"
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2 border border-transparent relative">
                    <i class="fas fa-pills"></i> Prescriptions
                    @if($visit->prescriptions->count() > 0)
                        <span class="absolute top-2 right-4 w-5 h-5 bg-blue-600 text-white text-[10px] flex items-center justify-center rounded-full border-2 border-white">{{ $visit->prescriptions->count() }}</span>
                    @endif
                </button>
                <button @click="activeTab = 'labs'" 
                        :class="activeTab === 'labs' ? 'bg-white text-blue-600 shadow-sm border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50'"
                        class="flex-1 py-3 text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2 border border-transparent">
                    <i class="fas fa-microscope"></i> Lab Orders
                </button>
            </div>

            <!-- Tab Panels -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Vitals Tab -->
                <div x-show="activeTab === 'vitals'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Current Vital Signs</h3>
                        <button @click="vitalsModalOpen = true" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                            <i class="fas fa-plus"></i> Record New Vitals
                        </button>
                    </div>

                    @if ($visit->latestVital)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <div class="bg-blue-50/50 border border-blue-100 p-4 rounded-2xl">
                                <div class="text-[10px] text-blue-500 font-bold uppercase tracking-wider mb-1">Temperature</div>
                                <div class="text-2xl font-black text-blue-700">{{ $visit->latestVital->temperature }}°C</div>
                            </div>
                            <div class="bg-rose-50/50 border border-rose-100 p-4 rounded-2xl">
                                <div class="text-[10px] text-rose-500 font-bold uppercase tracking-wider mb-1">Blood Pressure</div>
                                <div class="text-2xl font-black text-rose-700">{{ $visit->latestVital->blood_pressure_systolic }}/{{ $visit->latestVital->blood_pressure_diastolic }}</div>
                            </div>
                            <div class="bg-amber-50/50 border border-amber-100 p-4 rounded-2xl">
                                <div class="text-[10px] text-amber-500 font-bold uppercase tracking-wider mb-1">Pulse</div>
                                <div class="text-2xl font-black text-amber-700">{{ $visit->latestVital->pulse }} <span class="text-xs font-bold">BPM</span></div>
                            </div>
                            <div class="bg-emerald-50/50 border border-emerald-100 p-4 rounded-2xl">
                                <div class="text-[10px] text-emerald-500 font-bold uppercase tracking-wider mb-1">Respiration</div>
                                <div class="text-2xl font-black text-emerald-700">{{ $visit->latestVital->respiratory_rate }} <span class="text-xs font-bold">RPM</span></div>
                            </div>
                            <div class="bg-indigo-50/50 border border-indigo-100 p-4 rounded-2xl">
                                <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mb-1">O2 Saturation</div>
                                <div class="text-2xl font-black text-indigo-700">{{ $visit->latestVital->oxygen_saturation }}%</div>
                            </div>
                            <div class="bg-purple-50/50 border border-purple-100 p-4 rounded-2xl">
                                <div class="text-[10px] text-purple-500 font-bold uppercase tracking-wider mb-1">Weight</div>
                                <div class="text-2xl font-black text-purple-700 text-sm">N/A</div>
                            </div>
                        </div>

                        @if($visit->latestVital->notes)
                            <div class="mt-6 p-4 bg-gray-50 rounded-2xl border border-gray-100 text-sm text-gray-600 italic">
                                <strong>Nurse's Notes:</strong> {{ $visit->latestVital->notes }}
                            </div>
                        @endif
                    @else
                        <div class="flex flex-col items-center justify-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <div class="p-4 bg-white rounded-2xl shadow-sm mb-4">
                                <i class="fas fa-heartbeat text-gray-300 text-4xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No vitals recorded yet for this visit.</p>
                            <button @click="vitalsModalOpen = true" class="mt-4 text-blue-600 font-bold hover:underline">Record Now</button>
                        </div>
                    @endif
                </div>

                <!-- Notes & Diagnosis Tab -->
                <div x-show="activeTab === 'notes'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left: Input Form -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Primary Diagnosis <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="diagnosis.text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-gray-900" placeholder="e.g. Acute Viral Infection">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Severity <span class="text-rose-500">*</span></label>
                                    <select x-model="diagnosis.severity" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-gray-900">
                                        <option value="mild">Mild</option>
                                        <option value="moderate">Moderate</option>
                                        <option value="severe">Severe</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Clinical Notes & Observations</label>
                                <textarea x-model="diagnosis.notes" rows="6" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-gray-900" placeholder="Describe symptoms, duration, findings..."></textarea>
                            </div>
                            
                            <div class="flex flex-wrap gap-4">
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-100 transition-all">
                                    <input type="checkbox" x-model="diagnosis.is_urgent" class="w-5 h-5 rounded text-rose-600 focus:ring-rose-500">
                                    <span class="text-sm font-bold text-gray-700 uppercase tracking-wide">Mark as Urgent</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-100 transition-all">
                                    <input type="checkbox" x-model="diagnosis.is_chronic" class="w-5 h-5 rounded text-amber-600 focus:ring-amber-500">
                                    <span class="text-sm font-bold text-gray-700 uppercase tracking-wide">Chronic Condition</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-100 transition-all">
                                    <input type="checkbox" x-model="diagnosis.has_prescription" class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-bold text-gray-700 uppercase tracking-wide">Prescription Required</span>
                                </label>
                            </div>

                            <button @click="saveDiagnosis()" :disabled="saving" class="w-full lg:w-auto px-8 py-4 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all disabled:opacity-50 flex items-center justify-center gap-3">
                                <i class="fas fa-save" x-show="!saving"></i>
                                <i class="fas fa-spinner fa-spin" x-show="saving"></i>
                                <span x-text="saving ? 'Saving...' : 'Save Clinical Record'"></span>
                            </button>
                        </div>

                        <!-- Right: Previous Diagnoses -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-history text-xs"></i> Session History
                            </h4>
                            <div class="space-y-3" id="diagnosesList">
                                @forelse ($visit->diagnoses as $diag)
                                    @include('doctor.partials.diagnosis-item', ['diagnosis' => $diag])
                                @empty
                                    <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">No diagnoses recorded</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescriptions Tab -->
                <div x-show="activeTab === 'prescriptions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Medicines & Prescriptions</h3>
                        <div class="flex items-center gap-2">
                            <span id="prescriptionCount" class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                {{ $visit->prescriptions->count() }} prescribed
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left: Add form -->
                        <div class="lg:col-span-1 bg-gray-50 rounded-2xl border border-gray-200 p-5">
                            <form id="addPrescriptionForm" class="space-y-4">
                                <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                                <input type="hidden" name="diagnosis_id" id="active_diagnosis_id" value="{{ $visit->diagnoses->last() ? $visit->diagnoses->last()->id : '' }}">
                                <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase mb-2">Select Medicine</label>
                                    <select name="medicine_id" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-bold text-sm">
                                        <option value="">Choose medicine...</option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}" data-stock="{{ $medicine->stock }}">
                                                {{ $medicine->name }} ({{ $medicine->strength }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="medicineStock" class="mt-2 text-xs font-bold"></div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">Dosage</label>
                                        <input type="text" name="dosage" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold" placeholder="e.g. 500mg">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">Freq.</label>
                                        <select name="frequency" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                                            <option value="OD (Once daily)">Once daily (OD)</option>
                                            <option value="BD (Twice daily)">Twice daily (BD)</option>
                                            <option value="TDS (Thrice daily)">Thrice daily (TDS)</option>
                                            <option value="QID (Four times daily)">Four times daily (QID)</option>
                                            <option value="PRN (As needed)">As needed (PRN)</option>
                                            <option value="STAT (Immediately)">Immediately (STAT)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">Dur.</label>
                                        <input type="text" name="duration" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold" placeholder="e.g. 5 days">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">Qty.</label>
                                        <input type="number" name="quantity" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold" value="1">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase mb-2">Clinical Instructions</label>
                                    <textarea name="instructions" rows="2" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium" placeholder="Take after food, avoid cold water etc."></textarea>
                                </div>

                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2">
                                    <i class="fas fa-plus"></i> Add to Prescription
                                </button>
                            </form>
                        </div>

                        <!-- Right: List -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-1 min-h-[300px]">
                                <div id="prescriptionsList" class="divide-y divide-gray-100">
                                    @forelse($visit->prescriptions as $pres)
                                        @include('doctor.partials.prescription-item', ['prescription' => $pres])
                                    @empty
                                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                                            <i class="fas fa-prescription text-4xl mb-3 opacity-20"></i>
                                            <p class="text-xs font-black uppercase tracking-widest">No medications prescribed</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lab Orders Tab -->
                <div x-show="activeTab === 'labs'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Lab Investigations</h3>
                        <div class="flex items-center gap-2">
                            <button @click="labModalOpen = true" class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-xl hover:bg-purple-700 transition-colors shadow-lg shadow-purple-200">
                                <i class="fas fa-flask"></i> New Lab Order
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="labOrdersList">
                        @forelse($visit->labOrders ?? [] as $order)
                            @include('doctor.partials.lab-order-item', ['order' => $order])
                        @empty
                            <div class="col-span-3 flex flex-col items-center justify-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="p-4 bg-white rounded-2xl shadow-sm mb-4">
                                    <i class="fas fa-microscope text-gray-300 text-4xl"></i>
                                </div>
                                <p class="text-gray-500 font-medium tracking-tight">No lab orders placed in this session.</p>
                                <button @click="labModalOpen = true" class="mt-4 text-purple-600 font-bold hover:underline">Order Diagnostic Tests</button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


    <!-- Modals Section -->
    <div x-show="vitalsModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="vitalsModalOpen = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden border border-gray-100" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-blue-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 leading-tight">Record Vital Signs</h3>
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">Session ID: #{{ $visit->consultancy_no ?? $visit->id }}</p>
                    </div>
                </div>
                <button @click="vitalsModalOpen = false" class="w-8 h-8 rounded-full hover:bg-white flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <form id="vitalsForm" class="p-6 space-y-6">
                <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Temp (°C)</label>
                        <input type="number" step="0.1" name="temperature" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">BP (Sys/Dia)</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="blood_pressure_systolic" placeholder="120" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-center">
                            <span class="text-gray-300">/</span>
                            <input type="text" name="blood_pressure_diastolic" placeholder="80" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-center">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Pulse (BPM)</label>
                        <input type="number" name="pulse" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">SpO₂ (%)</label>
                        <input type="number" name="oxygen_saturation" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Observation Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-medium"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-blue-600 text-white font-black rounded-xl hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Save Vital Signs
                </button>
            </form>
        </div>
    </div>

    <!-- Lab Order Modal -->
    <div x-show="labModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="labModalOpen = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden border border-gray-100" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-purple-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 leading-tight">Diagnostic Investigation Request</h3>
                        <p class="text-xs font-bold text-purple-600 uppercase tracking-widest">Patient: {{ $visit->patient->name }}</p>
                    </div>
                </div>
                <button @click="labModalOpen = false" class="w-8 h-8 rounded-full hover:bg-white flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Lab search and selection logic -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">Available Tests</h4>
                        <div class="relative mb-4">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" x-model="labSearch" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 transition-all font-medium" placeholder="Search tests (e.g. CBC, Lipid Profile)...">
                        </div>
                        <div class="space-y-2 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($labTestTypes as $test)
                            <template x-if="!labSearch || '{{ strtolower($test->name) }}'.includes(labSearch.toLowerCase())">
                                <button @click="toggleLabTest({{ $test->id }}, '{{ $test->name }}', {{ $test->price ?? 0 }})" 
                                        :class="selectedTests.find(t => t.id === {{ $test->id }}) ? 'border-purple-500 bg-purple-50' : 'border-gray-100 bg-white hover:border-purple-200'"
                                        class="w-full p-4 border rounded-2xl flex items-center justify-between group transition-all">
                                    <div class="text-left">
                                        <div class="font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $test->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $test->category ?? 'General' }}</div>
                                    </div>
                                    <i :class="selectedTests.find(t => t.id === {{ $test->id }}) ? 'fas fa-check-circle text-purple-500' : 'fas fa-plus-circle text-gray-200 group-hover:text-purple-300'"></i>
                                </button>
                            </template>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200 flex flex-col">
                        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">Selected Investigations</h4>
                        <div class="flex-1 space-y-3 mb-6" id="selectedLabsList">
                            <template x-if="selectedTests.length === 0">
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                                    <i class="fas fa-flask text-3xl mb-3 opacity-20"></i>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">No tests selected</p>
                                </div>
                            </template>
                            <template x-for="test in selectedTests" :key="test.id">
                                <div class="bg-white p-3 rounded-xl border border-gray-100 flex items-center justify-between shadow-sm">
                                    <div class="font-bold text-sm text-gray-800" x-text="test.name"></div>
                                    <button @click="toggleLabTest(test.id)" class="text-rose-500 hover:bg-rose-50 w-6 h-6 rounded-lg flex items-center justify-center transition-colors">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Priority</label>
                                    <select x-model="labPriority" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                                        <option value="normal">Normal</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Clinical Indication</label>
                                    <textarea x-model="labNotes" rows="1" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium" placeholder="E.g. Persistent fever..."></textarea>
                                </div>
                            </div>
                            <button @click="submitLabOrder()" :disabled="selectedTests.length === 0 || savingLabs" class="w-full py-4 bg-purple-600 text-white font-black rounded-xl hover:bg-purple-700 shadow-xl shadow-purple-200 disabled:opacity-50 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane" x-show="!savingLabs"></i>
                                <i class="fas fa-spinner fa-spin" x-show="savingLabs"></i>
                                <span x-text="savingLabs ? 'Placing Order...' : 'Place Lab Order'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full EMR Modal -->
    <div x-show="historyModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="historyModalOpen = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col border border-gray-100" x-transition:enter="transition ease-out duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-600">
                        <i class="fas fa-folder-open text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 leading-tight">Electronic Medical Record</h3>
                        <p class="text-sm font-bold text-gray-500">{{ $visit->patient->name }} | Age: {{ $visit->patient->age }} | Sex: {{ ucfirst($visit->patient->gender) }}</p>
                    </div>
                </div>
                <button @click="historyModalOpen = false" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-gray-400"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-hidden flex">
                <!-- History Tabs -->
                <div class="w-64 border-r border-gray-100 p-4 space-y-1">
                    <button class="w-full text-left p-4 rounded-xl font-bold bg-blue-50 text-blue-600 flex items-center justify-between">
                        Past Visits <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                    <button class="w-full text-left p-4 rounded-xl font-bold text-gray-500 hover:bg-gray-50 flex items-center justify-between transition-colors">
                        Medications <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                    <button class="w-full text-left p-4 rounded-xl font-bold text-gray-500 hover:bg-gray-50 flex items-center justify-between transition-colors">
                        Lab Results <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
                
                <!-- Content Area -->
                <div class="flex-1 overflow-y-auto p-8 bg-gray-50/50">
                    <div id="fullHistoryContent" class="space-y-6">
                        <!-- Content loaded via AJAX -->
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <i class="fas fa-spinner fa-spin text-3xl mb-4"></i>
                            <p class="text-sm font-bold uppercase tracking-widest">Loading comprehensive history...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Consultation Confirmation -->
    <div x-show="completeModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="completeModalOpen = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center border border-gray-100" x-transition:enter="transition ease-out duration-300">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Finalize Consultation?</h3>
            <p class="text-gray-500 font-medium mb-8">This will mark the patient's visit as complete and generate the final prescription.</p>
            
            <div class="flex flex-col gap-3">
                <button @click="finalizeVisit()" :disabled="saving" class="w-full py-4 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-200 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-spinner fa-spin" x-show="saving"></i>
                    <span x-text="saving ? 'Finalizing...' : 'Yes, Complete Visit'"></span>
                </button>
                <button @click="completeModalOpen = false" class="w-full py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all">
                    Not Yet, Keep Editing
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('consultationData', () => ({
                activeTab: 'notes',
                vitalsModalOpen: false,
                labModalOpen: false,
                historyModalOpen: false,
                completeModalOpen: false,
                saving: false,
                savingLabs: false,
                labSearch: '',
                labNotes: '',
                labPriority: 'normal',
                selectedTests: [],
                diagnosis: {
                    text: '{{ $visit->diagnoses->last() ? $visit->diagnoses->last()->diagnosis : "" }}',
                    notes: '{{ $visit->diagnoses->last() ? $visit->diagnoses->last()->doctor_notes : "" }}',
                    severity: '{{ $visit->diagnoses->last() ? $visit->diagnoses->last()->severity : "moderate" }}',
                    is_urgent: {{ $visit->diagnoses->last() && $visit->diagnoses->last()->is_urgent ? "true" : "false" }},
                    is_chronic: {{ $visit->diagnoses->last() && $visit->diagnoses->last()->is_chronic ? "true" : "false" }},
                    has_prescription: true
                },

                toggleLabTest(id, name, price) {
                    const index = this.selectedTests.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.selectedTests.splice(index, 1);
                    } else {
                        this.selectedTests.push({ id, name, price });
                    }
                },

                async saveDiagnosis() {
                    if (!this.diagnosis.text) {
                        showNotification('Primary diagnosis is required', 'error');
                        return;
                    }
                    this.saving = true;
                    try {
                        const response = await fetch('/doctor/diagnoses', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                visit_id: {{ $visit->id }},
                                diagnosis: this.diagnosis.text,
                                doctor_notes: this.diagnosis.notes,
                                severity: this.diagnosis.severity,
                                is_urgent: this.diagnosis.is_urgent ? 1 : 0,
                                is_chronic: this.diagnosis.is_chronic ? 1 : 0,
                                has_prescription: this.diagnosis.has_prescription ? 1 : 0
                            })
                        });
                        const data = await response.json();
                        if (data.success) {
                            showNotification('Clinical record updated', 'success');
                            
                            // Update hidden diagnosis_id for prescriptions
                            if (data.diagnosis && data.diagnosis.id) {
                                const diagIdEl = document.getElementById('active_diagnosis_id');
                                if (diagIdEl) diagIdEl.value = data.diagnosis.id;
                            }

                            // Refresh diagnoses list
                            const list = document.getElementById('diagnosesList');
                            if (list && data.html) {
                                list.insertAdjacentHTML('afterbegin', data.html);
                            }
                            if (this.diagnosis.has_prescription) {
                                this.activeTab = 'prescriptions';
                            }
                        }
                    } catch (e) {
                        showNotification('Failed to save diagnosis', 'error');
                    } finally {
                        this.saving = false;
                    }
                },

                async submitLabOrder() {
                    if (this.selectedTests.length === 0) return;
                    this.savingLabs = true;
                    try {
                        const response = await fetch('/doctor/lab-orders', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                visit_id: {{ $visit->id }},
                                patient_id: {{ $visit->patient_id }},
                                test_type_ids: this.selectedTests.map(t => t.id),
                                priority: this.labPriority,
                                comments: this.labNotes
                            })
                        });
                        const data = await response.json();
                        if (data.success) {
                            showNotification('Lab orders placed successfully', 'success');
                            this.labModalOpen = false;
                            this.selectedTests = [];
                            this.labNotes = '';
                            // Refresh labs list
                            window.location.reload();
                        }
                    } catch (e) {
                        showNotification('Failed to place lab order', 'error');
                    } finally {
                        this.savingLabs = false;
                    }
                },

                async finalizeVisit() {
                    this.saving = true;
                    try {
                        const response = await fetch(`/doctor/consultancy/{{ $visit->id }}/complete`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            showNotification('Consultation completed!', 'success');
                            window.location.href = data.redirect || '/doctor/consultancy';
                        }
                    } catch (e) {
                        showNotification('Failed to complete consultation', 'error');
                    } finally {
                        this.saving = false;
                    }
                }
            }));
        });

        // Legacy form handling integration
        document.getElementById('vitalsForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const response = await fetch('/doctor/vitals/record', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    showNotification('Vitals updated', 'success');
                    window.location.reload();
                }
            } catch (e) {
                showNotification('Error saving vitals', 'error');
            }
        });

        document.getElementById('addPrescriptionForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const response = await fetch('/doctor/prescriptions', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    showNotification('Medication added', 'success');
                    document.getElementById('prescriptionsList').insertAdjacentHTML('afterbegin', data.html);
                    this.reset();
                    // Update count
                    const el = document.getElementById('prescriptionCount');
                    el.textContent = (parseInt(el.textContent) + 1) + ' prescribed';
                }
            } catch (e) {
                showNotification('Error adding medicine', 'error');
            }
        });

        function printConsultation() { window.print(); }
        function printPrescription(id) { window.open(`/print/prescription/${id}`, '_blank'); }
    </script>
    @endpush
@endsection
