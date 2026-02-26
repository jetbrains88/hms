// Global modal functions
window.showDuplicatePatientModal = function (existingPatient) {
    console.log('Showing duplicate modal for:', existingPatient);

    // Update modal content
    document.getElementById('duplicate-patient-name').textContent = existingPatient.name;
    document.getElementById('duplicate-patient-emrn').textContent = existingPatient.emrn;
    document.getElementById('duplicate-patient-phone').textContent = existingPatient.phone;
    document.getElementById('duplicate-patient-cnic').textContent = existingPatient.cnic || 'N/A';

    // Store the existing patient in a global variable
    window.selectedExistingPatient = existingPatient;

    // Show modal
    const modal = document.getElementById('duplicate-patient-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
};

window.closeDuplicateModal = function () {
    const modal = document.getElementById('duplicate-patient-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
    window.selectedExistingPatient = null;
};

window.useExistingPatient = function () {
    if (!window.selectedExistingPatient) {
        showError('No patient selected');
        return;
    }

    console.log('Using existing patient:', window.selectedExistingPatient);

    // Set the patient ID in the hidden field
    const patientIdField = document.querySelector('input[name="patient_id"]');
    if (patientIdField) {
        patientIdField.value = window.selectedExistingPatient.id;
        console.log('Set patient_id to:', window.selectedExistingPatient.id);
    }

    // Switch to existing patient tab if not already
    if (typeof Alpine !== 'undefined') {
        const alpineElement = document.querySelector('[x-data="receptionDashboard()"]');
        if (alpineElement) {
            const alpineData = Alpine.$data(alpineElement);
            if (alpineData) {
                alpineData.activeTab = 'existing';
                alpineData.selectedExistingPatient = window.selectedExistingPatient;
                alpineData.selectedPatientId = window.selectedExistingPatient.id;
                alpineData.searchQuery = '';
                alpineData.searchResults = [];

                // Show success message
                showSuccess(`Patient selected: ${window.selectedExistingPatient.name}`, 'Patient Selected', 5000);
                console.log('success: Patient selected:', window.selectedExistingPatient.name);
            }
        }
    }

    window.closeDuplicateModal();
};

window.continueRegistration = function () {
    window.closeDuplicateModal();
    // Continue with new patient registration
    console.log('Continuing with new patient registration');
};
