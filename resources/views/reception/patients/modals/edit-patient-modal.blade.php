<div id="edit-patient-modal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Edit Patient Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl"
                        onclick="window.closeEditPatientModal()">
                    &times;
                </button>
            </div>
        </div>

        <form id="edit-patient-form" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit-patient-id">

            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="section-card">
                    <h4 class="font-bold text-gray-800 mb-4">Basic Information</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" id="edit-name" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
                            <input type="text" name="cnic" id="edit-cnic"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                   oninput="formatCNIC(this)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" name="phone" id="edit-phone" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                   oninput="validatePhone(this)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                            <input type="date" name="dob" id="edit-dob" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                   max="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                            <select name="gender" id="edit-gender" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Blood Group</label>
                            <select name="blood_group" id="edit-blood-group"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select Blood Group</option>
                                @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" id="edit-address" rows="2"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="section-card">
                    <h4 class="font-bold text-gray-800 mb-4">Medical Information</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Known Allergies</label>
                            <textarea name="allergies" id="edit-allergies" rows="2"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Chronic Conditions</label>
                            <textarea name="chronic_conditions" id="edit-chronic-conditions" rows="2"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medical History</label>
                            <textarea name="medical_history" id="edit-medical-history" rows="2"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="window.closeEditPatientModal()"
                            class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="edit-patient-submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-lg hover:shadow-lg transition-all flex items-center gap-2">
                        <i class="fas fa-spinner fa-spin hidden" id="edit-patient-spinner"></i>
                        <span id="edit-patient-submit-text">Update Patient</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Edit Patient Modal Functions
    window.showEditPatientModal = function (patient) {
        const modal = document.getElementById('edit-patient-modal');
        const form = document.getElementById('edit-patient-form');

        // Set form action
        form.action = `{{ route("reception.patients.update", ":id") }}`.replace(':id', patient.id);

        // Fill form fields
        document.getElementById('edit-patient-id').value = patient.id;
        document.getElementById('edit-name').value = patient.name || '';
        document.getElementById('edit-cnic').value = patient.cnic || '';
        document.getElementById('edit-phone').value = patient.phone || '';
        document.getElementById('edit-dob').value = patient.dob || '';
        document.getElementById('edit-gender').value = patient.gender || '';
        document.getElementById('edit-blood-group').value = patient.blood_group || '';
        document.getElementById('edit-address').value = patient.address || '';
        document.getElementById('edit-allergies').value = patient.allergies || '';
        document.getElementById('edit-chronic-conditions').value = patient.chronic_conditions || '';
        document.getElementById('edit-medical-history').value = patient.medical_history || '';

        modal.classList.remove('hidden');
    };

    window.closeEditPatientModal = function () {
        document.getElementById('edit-patient-modal').classList.add('hidden');
    };

    // Form submission
    document.getElementById('edit-patient-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        const submitBtn = document.getElementById('edit-patient-submit');
        const spinner = document.getElementById('edit-patient-spinner');
        const submitText = document.getElementById('edit-patient-submit-text');

        // Disable button and show spinner
        submitBtn.disabled = true;
        spinner.classList.remove('hidden');
        submitText.textContent = 'Updating...';

        try {
            const response = await fetch(form.action, {
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
                showSuccess('Patient updated successfully!', 'Success');
                window.closeEditPatientModal();

                // Refresh patient list if Alpine component exists
                if (window.alpinePatientManagement) {
                    window.alpinePatientManagement.refreshExistingPatients();
                }
            } else {
                if (result.errors) {
                    Object.keys(result.errors).forEach(field => {
                        showError(result.errors[field][0], 'Validation Error');
                    });
                } else {
                    showError(result.message || 'An error occurred', 'Error');
                }
            }
        } catch (error) {
            console.error('Error updating patient:', error);
            showError('Network error. Please try again.', 'Network Error');
        } finally {
            // Re-enable button
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
            submitText.textContent = 'Update Patient';
        }
    });

    // Close modal when clicking outside
    document.getElementById('edit-patient-modal').addEventListener('click', function (event) {
        if (event.target === this) {
            window.closeEditPatientModal();
        }
    });
</script>
