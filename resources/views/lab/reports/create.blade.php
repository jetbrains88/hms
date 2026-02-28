@extends('layouts.app')

@section('title', 'Create Lab Report')

@section('content')
    <div x-data="labReportForm" x-init="init()" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- Header -->
        <div class="bg-white shadow-xl rounded-2xl mx-4 mt-6 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Lab Report</h1>
                    <p class="text-gray-600 mt-2">Fill in the details to create a new laboratory report</p>
                </div>
                <div>
                    <a href="{{ route('lab.reports.index') }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="mx-4 mt-6">
            <form @submit.prevent="submitForm" id="labReportForm" class="bg-white shadow-xl rounded-2xl p-6">
                @csrf

                <!-- Progress Steps -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div :class="{'bg-blue-600 text-white': step >= 1, 'bg-gray-200 text-gray-600': step < 1}"
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold">
                                    1
                                </div>
                                <div class="ml-2">
                                    <div class="text-sm font-medium">Patient Info</div>
                                </div>
                            </div>

                            <div class="h-1 w-12 bg-gray-300"></div>

                            <div class="flex items-center">
                                <div :class="{'bg-blue-600 text-white': step >= 2, 'bg-gray-200 text-gray-600': step < 2}"
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold">
                                    2
                                </div>
                                <div class="ml-2">
                                    <div class="text-sm font-medium">Test Details</div>
                                </div>
                            </div>

                            <div class="h-1 w-12 bg-gray-300"></div>

                            <div class="flex items-center">
                                <div :class="{'bg-blue-600 text-white': step >= 3, 'bg-gray-200 text-gray-600': step < 3}"
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold">
                                    3
                                </div>
                                <div class="ml-2">
                                    <div class="text-sm font-medium">Sample Info</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Patient Information -->
                <div x-show="step === 1" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Selection -->
                        <div class="md:col-span-2">
                            <label for="patient_search" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Patient <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="patient_search" x-model="patientSearch"
                                    @input.debounce.300ms="searchPatients" @blur="touched.patient_id = true"
                                    :aria-invalid="!form.patient_id && touched.patient_id" aria-describedby="patient-error"
                                    placeholder="Search by name, CNIC, or EMRN..." :class="{
                                                   'border-red-500': !form.patient_id && touched.patient_id,
                                                   'border-green-500': form.patient_id && touched.patient_id,
                                                   'border-gray-300': !touched.patient_id
                                               }"
                                    class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                                <!-- Success indicator -->
                                <div x-show="form.patient_id && touched.patient_id"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>

                                <!-- Patient Search Results -->
                                <div x-show="patientResults.length > 0 && patientSearch"
                                    class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                    <template x-for="patient in patientResults" :key="patient.id">
                                        <div @click="selectPatient(patient)"
                                            class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-200 last:border-b-0">
                                            <div class="font-medium text-gray-900" x-text="patient.name"></div>
                                            <div class="text-sm text-gray-600">
                                                CNIC: <span x-text="patient.cnic"></span> |
                                                EMRN: <span x-text="patient.emrn"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Error Message -->
                            <p x-show="!form.patient_id && touched.patient_id" id="patient-error" role="alert"
                                aria-live="assertive" class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> Please search and select a patient
                            </p>

                            <!-- Selected Patient Info -->
                            <div x-show="form.patient_id" class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-900" x-text="selectedPatient?.name"></div>
                                        <div class="text-sm text-gray-600">
                                            CNIC: <span x-text="selectedPatient?.cnic"></span> |
                                            Age: <span x-text="selectedPatient?.age"></span> years |
                                            Gender: <span x-text="selectedPatient?.gender"></span>
                                        </div>
                                    </div>
                                    <button type="button" @click="clearPatient" class="text-red-600 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Selection -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Referring Doctor <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <select x-model="form.doctor_id" id="doctor_id" required @blur="touched.doctor_id = true"
                                    :aria-invalid="!form.doctor_id && touched.doctor_id" aria-describedby="doctor-error"
                                    :class="{
                                                    'border-red-500': !form.doctor_id && touched.doctor_id,
                                                    'border-green-500': form.doctor_id && touched.doctor_id,
                                                    'border-gray-300': !touched.doctor_id
                                                }"
                                    class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                                <div x-show="form.doctor_id && touched.doctor_id"
                                    class="absolute inset-y-0 right-8 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                            </div>
                            <p x-show="!form.doctor_id && touched.doctor_id" id="doctor-error" role="alert"
                                aria-live="assertive" class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> Please select the referring doctor
                            </p>
                        </div>

                        <!-- Visit Selection -->
                        <div>
                            <label for="visit_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Visit (Optional)
                            </label>
                            <select x-model="form.visit_id" id="visit_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">No specific visit</option>
                                <template x-for="visit in patientVisits" :key="visit.id">
                                    <option :value="visit.id" x-text="'Visit #' + visit.id + ' - ' + visit.created_at">
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-end">
                        <button type="button" @click="goToStep(2)" :disabled="!isStep1Valid()"
                            :class="{'bg-blue-600 hover:bg-blue-700': isStep1Valid(), 'bg-gray-400 cursor-not-allowed': !isStep1Valid()}"
                            class="text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200">
                            Next: Test Details <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Test Details -->
                <div x-show="step === 2" class="space-y-6">
                    <!-- Quick Test Templates - MOVED TO TOP -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Test Selection</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($predefinedTests as $test)
                                <button type="button" 
                                        @click="applyTemplate(@js($test))"
                                        :class="{'border-blue-500 bg-blue-50 shadow-md ring-2 ring-blue-300': form.lab_test_type_id && selectedTestName === '{{ $test['name'] }}', 'border-gray-300 bg-white hover:border-blue-300': form.lab_test_type_id !== getTestTypeId('{{ $test['name'] }}')}"
                                        class="border rounded-xl p-4 text-left hover:shadow-md transition-all duration-200">
                                    <div class="font-medium text-gray-900">{{ $test['name'] }}</div>
                                    <div class="text-sm text-gray-600 mt-1">{{ $test['test_type'] }}</div>
                                    <div class="text-xs text-blue-600 mt-2 flex items-center">
                                        <i class="fas fa-bolt mr-1"></i> Click to select
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Test Type Selection - MAIN SELECTION -->
                        <div class="md:col-span-2">
                            <label for="lab_test_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Test/Report Type <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <select x-model="form.lab_test_type_id" 
                                        id="lab_test_type_id" 
                                        required 
                                        @change="updateTestDetails"
                                        @blur="touched.lab_test_type_id = true"
                                        :aria-invalid="!form.lab_test_type_id && touched.lab_test_type_id"
                                        aria-describedby="test-type-error"
                                        :class="{
                                            'border-red-500': !form.lab_test_type_id && touched.lab_test_type_id,
                                            'border-green-500': form.lab_test_type_id && touched.lab_test_type_id,
                                            'border-gray-300': !touched.lab_test_type_id
                                        }"
                                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white">
                                    <option value="">-- Select Test Type --</option>
                                    @foreach($testTypes as $type)
                                        <option value="{{ $type->id }}" 
                                                data-name="{{ $type->name }}"
                                                data-type="{{ $type->department ?? 'General' }}"
                                                data-sample="{{ $type->sample_type ?? 'Blood' }}">
                                            {{ $type->name }} ({{ $type->department ?? 'General' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div x-show="form.lab_test_type_id && touched.lab_test_type_id" 
                                    class="absolute inset-y-0 right-8 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                            </div>
                            <p x-show="!form.lab_test_type_id && touched.lab_test_type_id" 
                            id="test-type-error" 
                            role="alert" 
                            aria-live="assertive" 
                            class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> Please select a test type
                            </p>
                            
                            <!-- Hidden fields for legacy compatibility -->
                            <input type="hidden" x-model="form.test_name" name="test_name">
                            <input type="hidden" x-model="form.test_type" name="test_type">
                        </div>

                        <!-- Lab Number -->
                        <div>
                            <label for="lab_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Lab Number
                            </label>
                            <input type="text" 
                                x-model="form.lab_number" 
                                id="lab_number"
                                placeholder="Auto-generated"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                Priority <span class="text-red-600">*</span>
                            </label>
                            <select x-model="form.priority" id="priority" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>

                        <!-- Technician -->
                        <div>
                            <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Assigned Technician
                            </label>
                            <select x-model="form.technician_id" id="technician_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Technician</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Clinical Notes</label>
                        <textarea x-model="form.notes" id="notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Any clinical notes or special requirements..."></textarea>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between pt-4">
                        <button type="button" @click="step = 1"
                                class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" @click="goToStep(3)" :disabled="!isStep2Valid()"
                                :class="{'bg-blue-600 hover:bg-blue-700': isStep2Valid(), 'bg-gray-400 cursor-not-allowed': !isStep2Valid()}"
                                class="text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200">
                            Next: Sample Info <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Sample Information -->
                <div x-show="step === 3" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sample Collection Time -->
                        <div>
                            <label for="sample_collected_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Sample Collection Time
                            </label>
                            <input type="datetime-local" x-model="form.sample_collected_at" id="sample_collected_at"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Sample ID (hidden for optimized tests) -->
                        <div x-show="!isOptimizedTest">
                            <label for="sample_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sample ID
                            </label>
                            <input type="text" x-model="form.sample_id" id="sample_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Auto-generated sample ID">
                        </div>

                        <!-- Sample Container (hidden for optimized tests) -->
                        <div x-show="!isOptimizedTest">
                            <label for="sample_container" class="block text-sm font-medium text-gray-700 mb-2">
                                Sample Container
                            </label>
                            <select x-model="form.sample_container" id="sample_container"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Container</option>
                                <option value="vacutainer">Vacutainer</option>
                                <option value="urine_cup">Urine Cup</option>
                                <option value="stool_container">Stool Container</option>
                                <option value="swab">Swab</option>
                                <option value="culture_bottle">Culture Bottle</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Sample Quantity (hidden for optimized tests) -->
                        <div x-show="!isOptimizedTest">
                            <label for="sample_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Sample Quantity
                            </label>
                            <div class="flex">
                                <input type="number" x-model="form.sample_quantity" id="sample_quantity" step="0.01"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-l-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Quantity">
                                <select x-model="form.sample_quantity_unit"
                                    class="px-4 py-3 border border-l-0 border-gray-300 rounded-r-xl bg-gray-50">
                                    <option value="ml">ml</option>
                                    <option value="g">g</option>
                                    <option value="pieces">pieces</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Condition (hidden for optimized tests) -->
                    <div x-show="!isOptimizedTest">
                        <label for="sample_condition" class="block text-sm font-medium text-gray-700 mb-2">
                            Sample Condition
                        </label>
                        <textarea x-model="form.sample_condition" id="sample_condition" rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Describe sample condition (e.g., hemolyzed, lipemic, etc.)"></textarea>
                    </div>

                    <!-- Special Instructions -->
                    <div>
                        <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Special Instructions
                        </label>
                        <textarea x-model="form.special_instructions" id="special_instructions" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Any special handling or processing instructions..."></textarea>
                    </div>

                    <!-- Navigation & Submit -->
                    <div class="flex justify-between">
                        <button type="button" @click="step = 2"
                            class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <div class="space-x-4">
                            <button type="button" @click="saveAsDraft"
                                class="bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-yellow-700 transition-colors duration-200">
                                Save as Draft
                            </button>
                            <button type="submit" :disabled="isSubmitting || !isFormValid()"
                                :class="{'bg-blue-600 hover:bg-blue-700': !isSubmitting && isFormValid(), 'bg-blue-400 cursor-not-allowed': isSubmitting || !isFormValid()}"
                                class="text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200">
                                <span x-show="!isSubmitting">Create Lab Report</span>
                                <span x-show="isSubmitting">Creating...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading Overlay -->
        <div x-show="isSubmitting" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <div class="text-gray-700 font-medium">Creating lab report...</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('labReportForm', () => ({
                step: 1,
                isSubmitting: false,
                patientSearch: '',
                patientResults: [],
                patientVisits: [],
                selectedPatient: null,
                isOptimizedTest: false,

                // Track which fields have been interacted with (blurred)
                touched: {
                    patient_id: false,
                    doctor_id: false,
                    test_name: false,
                    test_type: false,
                    sample_type: false,
                },

                form: {
                    patient_id: null,
                    visit_id: null,
                    doctor_id: null,
                    technician_id: null,
                    test_name: '',
                    lab_number: '',
                    test_type: '',
                    sample_type: '',
                    priority: 'normal',
                    notes: '',
                    sample_collected_at: '',
                    sample_id: '',
                    sample_container: '',
                    sample_quantity: '',
                    sample_quantity_unit: 'ml',
                    sample_condition: '',
                    special_instructions: ''
                },

                    // Add these to the labReportForm Alpine data object

                // Store test type IDs mapping
                testTypeIds: {},

                init() {
                    // Set default sample collection time to now
                    this.form.sample_collected_at = new Date().toISOString().slice(0, 16);
                    // Generate sample ID
                    this.generateSampleId();
                    
                    // Build test type ID mapping
                    this.buildTestTypeMapping();
                },

                buildTestTypeMapping() {
                    const selects = document.querySelectorAll('#lab_test_type_id option');
                    selects.forEach(option => {
                        if (option.value && option.dataset.name) {
                            this.testTypeIds[option.dataset.name] = option.value;
                        }
                    });
                },

                getTestTypeId(testName) {
                    return this.testTypeIds[testName] || null;
                },

                selectedTestName() {
                    if (!this.form.lab_test_type_id) return null;
                    const select = document.getElementById('lab_test_type_id');
                    const option = select?.selectedOptions[0];
                    return option?.dataset?.name || null;
                },

                applyTemplate(template) {
                    const testTypeId = this.getTestTypeId(template.name);
                    if (testTypeId) {
                        this.form.lab_test_type_id = testTypeId;
                        this.form.test_name = template.name;
                        this.form.test_type = template.test_type;
                        this.form.sample_type = template.sample_type;
                        
                        // Mark as touched
                        this.touched.lab_test_type_id = true;
                        this.touched.test_name = true;
                        this.touched.test_type = true;
                        this.touched.sample_type = true;
                        
                        // Update the select element
                        setTimeout(() => {
                            const select = document.getElementById('lab_test_type_id');
                            if (select) {
                                select.value = testTypeId;
                                // Trigger change event
                                const event = new Event('change', { bubbles: true });
                                select.dispatchEvent(event);
                            }
                        }, 10);
                        
                        this.checkOptimization();
                        this.showNotification('Test template applied successfully!', 'success');
                    } else {
                        console.warn('Test type ID not found for:', template.name);
                        this.showNotification('Could not find matching test type', 'error');
                    }
                },

                updateTestDetails() {
                    const select = document.getElementById('lab_test_type_id');
                    if (select && select.selectedOptions.length > 0) {
                        const option = select.selectedOptions[0];
                        if (option.value) {
                            this.form.lab_test_type_id = option.value;
                            this.form.test_name = option.dataset.name;
                            this.form.test_type = option.dataset.type;
                            this.form.sample_type = option.dataset.sample || 'Blood';
                            
                            // Mark as touched
                            this.touched.lab_test_type_id = true;
                            this.touched.test_name = true;
                            this.touched.test_type = true;
                            this.touched.sample_type = true;
                            
                            this.checkOptimization();
                        }
                    }
                },

                isStep2Valid() {
                    return this.form.lab_test_type_id && this.form.priority;
                },

                // --- Validation Methods ---

                isStep1Valid() {
                    return this.form.patient_id && this.form.doctor_id;
                },

                isStep2Valid() {
                    return this.form.test_name && this.form.test_type && this.form.sample_type;
                },

                isFormValid() {
                    return this.isStep1Valid() && this.isStep2Valid();
                },

                /** Mark all fields in a step as touched (for navigating forward) */
                touchStep(stepNumber) {
                    if (stepNumber === 1) {
                        this.touched.patient_id = true;
                        this.touched.doctor_id = true;
                    } else if (stepNumber === 2) {
                        this.touched.test_name = true;
                        this.touched.test_type = true;
                        this.touched.sample_type = true;
                    }
                },

                /** Navigate to a step, touching current step fields first */
                goToStep(targetStep) {
                    // Touch current step so errors show if user tries to skip
                    this.touchStep(this.step);

                    if (this.step === 1 && !this.isStep1Valid()) return;
                    if (this.step === 2 && !this.isStep2Valid()) return;

                    this.step = targetStep;
                },

                // --- Patient Methods ---

                async searchPatients() {
                    if (this.patientSearch.length < 2) {
                        this.patientResults = [];
                        return;
                    }

                    try {
                        const response = await fetch(`/api/patients/search?q=${encodeURIComponent(this.patientSearch)}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.patientResults = data.data;
                        }
                    } catch (error) {
                        console.error('Error searching patients:', error);
                    }
                },

                async selectPatient(patient) {
                    this.selectedPatient = patient;
                    this.form.patient_id = patient.id;
                    this.touched.patient_id = true;
                    this.patientSearch = '';
                    this.patientResults = [];

                    // Fetch patient's recent visits
                    try {
                        const response = await fetch(`/api/patients/${patient.id}/visits`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.patientVisits = data.data;
                        }
                    } catch (error) {
                        console.error('Error fetching patient visits:', error);
                    }
                },

                clearPatient() {
                    this.selectedPatient = null;
                    this.form.patient_id = null;
                    this.form.visit_id = null;
                    this.patientVisits = [];
                },

                // --- Template & Optimization ---

                applyTemplate(template) {
                    this.form.test_name = template.name;
                    this.form.test_type = template.test_type;
                    this.form.sample_type = template.sample_type;
                    // Mark as touched since values are programmatically set
                    this.touched.test_name = true;
                    this.touched.test_type = true;
                    this.touched.sample_type = true;
                    this.checkOptimization();
                    showNotification('Test template applied successfully!', 'success');
                },

                updateTestDetails(option) {
                    if (!option || !option.value) return;
                    
                    this.form.lab_test_type_id = option.value;
                    this.form.test_name = option.dataset.name;
                    this.form.test_type = option.dataset.type;
                    
                    // Mark as touched
                    this.touched.lab_test_type_id = true;
                    this.touched.test_name = true;
                    this.touched.test_type = true;
                    
                    this.checkOptimization();
                },

                checkOptimization() {
                    const optimizedTests = ['Special Chemistry', 'Urine Routine Examination'];
                    this.isOptimizedTest = optimizedTests.includes(this.form.test_name);
                },

                generateSampleId() {
                    const date = new Date();
                    const year = date.getFullYear().toString().slice(-2);
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const day = date.getDate().toString().padStart(2, '0');
                    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');

                    this.form.sample_id = `SMP-${year}${month}${day}-${random}`;
                },

                // --- Submission ---

                async saveAsDraft() {
                    this.form.status = 'pending';
                    await this.submitForm();
                },

                async submitForm() {
                    if (this.isSubmitting) return;

                    // Final validation gate
                    if (!this.isFormValid()) {
                        this.touchStep(1);
                        this.touchStep(2);
                        showNotification('Please fill in all required fields before submitting.', 'error');
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        const response = await fetch('{{ route("lab.reports.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.form)
                        });

                        const data = await response.json();

                        if (data.success) {
                            showNotification(data.message, 'success');

                            // Redirect to show page
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        } else {
                            showNotification(data.message || 'Failed to create lab report', 'error');

                            // Show validation errors if any
                            if (data.errors) {
                                Object.entries(data.errors).forEach(([field, errors]) => {
                                    errors.forEach(error => {
                                        showNotification(`${field}: ${error}`, 'error');
                                    });
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error submitting form:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                },
            }));
        });
    </script>
@endsection