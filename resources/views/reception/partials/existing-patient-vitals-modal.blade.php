<div id="existing-patient-vitals-modal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Add Vitals for New Visit</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl"
                        onclick="window.closeExistingPatientVitalsModal()">
                    &times;
                </button>
            </div>
        </div>

        <form id="existingPatientVitalsForm" @submit.prevent="submitExistingPatientVitalsForm" class="p-6">
            @csrf
            <input type="hidden" name="patient_id" id="existingPatientId">
            <input type="hidden" name="visit_type" id="existingPatientVisitType" value="routine">

            <!-- Patient Information -->
            <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i>
                    Patient Information
                </h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-600">Name:</span>
                        <span class="font-bold ml-2" id="existingPatientName"></span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">EMRN:</span>
                        <span class="font-mono ml-2" id="existingPatientEMRN"></span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Phone:</span>
                        <span class="ml-2" id="existingPatientPhone"></span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">CNIC:</span>
                        <span class="ml-2" id="existingPatientCNIC"></span>
                    </div>
                </div>
            </div>

            <!-- Visit Type Selection -->
            <div class="mb-6 p-4 border-2 border-dashed border-indigo-200 rounded-lg bg-indigo-50/30">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-stethoscope mr-2 text-indigo-600"></i>
                    Visit Type
                </h3>
                <div class="grid md:grid-cols-3 gap-3">
                    <label
                        class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500">
                        <input type="radio" name="visit_type" value="routine" required
                               class="w-4 h-4 text-indigo-600 visit-type-radio"
                               onchange="document.getElementById('existingPatientVisitType').value = 'routine'">
                        <div class="ml-3">
                            <span class="font-medium text-gray-700">Routine</span>
                            <p class="text-xs text-gray-500">Regular checkup or consultation</p>
                        </div>
                    </label>
                    <label
                        class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition has-[:checked]:bg-red-50 has-[:checked]:border-red-500">
                        <input type="radio" name="visit_type" value="emergency" required
                               class="w-4 h-4 text-red-600 visit-type-radio"
                               onchange="document.getElementById('existingPatientVisitType').value = 'emergency'">
                        <div class="ml-3">
                            <span class="font-medium text-gray-700">Emergency</span>
                            <p class="text-xs text-gray-500">Urgent medical attention needed</p>
                        </div>
                    </label>
                    <label
                        class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-white transition has-[:checked]:bg-green-50 has-[:checked]:border-green-500">
                        <input type="radio" name="visit_type" value="followup" required
                               class="w-4 h-4 text-green-600 visit-type-radio"
                               onchange="document.getElementById('existingPatientVisitType').value = 'followup'">
                        <div class="ml-3">
                            <span class="font-medium text-gray-700">Follow-up</span>
                            <p class="text-xs text-gray-500">Post-treatment review</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Vital Signs -->
            <div class="section-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold">1</span>
                        <h3 class="font-bold text-gray-800">Vital Signs</h3>
                    </div>
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full">Required</span>
                </div>

                <div class="space-y-6">
                    <!-- Temperature & Pulse -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Temperature (°F)</label>
                            <input type="number" step="0.1" name="temperature" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                   value="97.7">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pulse (BPM)</label>
                            <input type="number" name="pulse" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                   value="72">
                        </div>
                    </div>

                    <!-- Blood Pressure -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure (mmHg)</label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <input type="number" name="blood_pressure_systolic" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                       placeholder="120" value="120">
                                <span class="text-xs text-gray-500 mt-1 block">Systolic</span>
                            </div>
                            <div>
                                <input type="number" name="blood_pressure_diastolic" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                       placeholder="80" value="80">
                                <span class="text-xs text-gray-500 mt-1 block">Diastolic</span>
                            </div>
                        </div>
                    </div>

                    <!-- Oxygen & Respiratory -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Oxygen Saturation (%)</label>
                            <input type="number" name="oxygen_saturation" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                   value="98">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Respiratory Rate</label>
                            <input type="number" name="respiratory_rate" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                   value="16">
                        </div>
                    </div>

                    <!-- Weight & Height -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                            <input type="number" step="0.1" name="height"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                    </div>

                    <!-- Pain Scale & Blood Glucose -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pain Scale (0-10)</label>
                            <input type="range" name="pain_scale" min="0" max="10" value="0"
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                   oninput="document.getElementById('painScaleValue').textContent = this.value + '/10'">
                            <div class="text-center text-sm text-gray-600 mt-1">
                                <span id="painScaleValue">0/10</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Blood Glucose (mg/dL)</label>
                            <input type="number" step="0.1" name="blood_glucose"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                    </div>

                    <!-- Clinical Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Clinical Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                  placeholder="Any additional observations or remarks..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="window.closeExistingPatientVitalsModal()"
                        class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" id="submitExistingPatientVitals"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-lg hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="fas fa-spinner fa-spin hidden" id="existingPatientSpinner"></i>
                    <span id="existingPatientSubmitText">Complete Registration & Generate Visit</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal functions for existing patient vitals
    window.showExistingPatientVitalsModal = function (patient) {
        // Fill patient information
        document.getElementById('existingPatientId').value = patient.id;
        document.getElementById('existingPatientName').textContent = patient.name;
        document.getElementById('existingPatientEMRN').textContent = patient.emrn;
        document.getElementById('existingPatientPhone').textContent = patient.phone;
        document.getElementById('existingPatientCNIC').textContent = patient.cnic || 'N/A';

        // Set default visit type
        document.querySelector('input[name="visit_type"][value="routine"]').checked = true;
        document.getElementById('existingPatientVisitType').value = 'routine';

        // Reset form values to defaults
        const form = document.getElementById('existingPatientVitalsForm');
        form.reset();

        // Set default values
        form.querySelector('input[name="temperature"]').value = '97.7';
        form.querySelector('input[name="pulse"]').value = '72';
        form.querySelector('input[name="blood_pressure_systolic"]').value = '120';
        form.querySelector('input[name="blood_pressure_diastolic"]').value = '80';
        form.querySelector('input[name="oxygen_saturation"]').value = '98';
        form.querySelector('input[name="respiratory_rate"]').value = '16';
        form.querySelector('input[name="pain_scale"]').value = '0';
        document.getElementById('painScaleValue').textContent = '0/10';

        // Show modal
        document.getElementById('existing-patient-vitals-modal').classList.remove('hidden');
    };

    window.closeExistingPatientVitalsModal = function () {
        document.getElementById('existing-patient-vitals-modal').classList.add('hidden');
    };

    // Form submission for existing patient vitals
    window.submitExistingPatientVitalsForm = async function (event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        // Get submit button elements
        const submitBtn = document.getElementById('submitExistingPatientVitals');
        const spinner = document.getElementById('existingPatientSpinner');
        const submitText = document.getElementById('existingPatientSubmitText');

        // Disable button and show spinner
        submitBtn.disabled = true;
        spinner.classList.remove('hidden');
        submitText.textContent = 'Processing...';

        try {
            // Submit the form
            const response = await fetch('{{ route("reception.visits.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Check if we have a notification function
                if (window.showToast) {
                    window.showToast(result.message || 'Visit registered successfully!', 'success');
                } else {
                    alert(result.message || 'Visit registered successfully!');
                }

                // Close modal
                window.closeExistingPatientVitalsModal();

                // Clear selected patient in Alpine
                const dashboardEl = document.querySelector('[x-data="receptionDashboard()"]');
                if (dashboardEl && dashboardEl.__x) {
                    const alpineData = dashboardEl.__x.$data;
                    alpineData.selectedExistingPatient = null;
                    alpineData.searchQuery = '';
                    alpineData.searchResults = [];
                    
                    // Refresh the lists
                    if (typeof alpineData.loadInProgressPatients === 'function') {
                        await alpineData.loadInProgressPatients();
                    }
                    if (typeof alpineData.loadWaitingPatients === 'function') {
                        await alpineData.loadWaitingPatients();
                    }
                }

                // Instead of reload, just notify
                console.log('Visit started successfully without reload');
            } else {
                if (result.errors) {
                    Object.keys(result.errors).forEach(field => {
                        showError(result.errors[field][0], 'Validation Error');
                    });
                } else {
                    showError(result.message || 'An error occurred during registration', 'Registration Failed');
                }
            }
        } catch (error) {
            console.error('Form submission error:', error);
            showError('Network error. Please check your connection and try again.', 'Network Error');
        } finally {
            // Re-enable button
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
            submitText.textContent = 'Complete Registration & Generate Visit';
        }
    };

    // Close modal when clicking outside
    document.getElementById('existing-patient-vitals-modal').addEventListener('click', function (event) {
        if (event.target === this) {
            window.closeExistingPatientVitalsModal();
        }
    });
</script>
