<div id="patient-details-modal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Patient Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl"
                        onclick="window.closePatientDetailsModal()">
                    &times;
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- Patient Information will be loaded here dynamically -->
            <div id="patient-details-content">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    // Patient Details Modal Functions
    window.showPatientDetailsModal = function (patient) {
        const modal = document.getElementById('patient-details-modal');
        const content = document.getElementById('patient-details-content');

        // Format the content
        content.innerHTML = `
        <div class="space-y-6">
            <!-- Personal Information -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-circle text-blue-600"></i>
                    Personal Information
                </h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-600">Full Name:</span>
                        <span class="font-bold ml-2">${patient.name}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">EMRN:</span>
                        <span class="font-mono ml-2">${patient.emrn}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">CNIC:</span>
                        <span class="ml-2">${patient.cnic || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Phone:</span>
                        <span class="ml-2">${patient.phone}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Date of Birth:</span>
                        <span class="ml-2">${patient.dob || 'N/A'} (${patient.age || 'N/A'} years)</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Gender:</span>
                        <span class="ml-2">${patient.gender ? patient.gender.charAt(0).toUpperCase() + patient.gender.slice(1) : 'N/A'}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Blood Group:</span>
                        <span class="ml-2">${patient.blood_group || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Address:</span>
                        <span class="ml-2">${patient.address || 'N/A'}</span>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            ${patient.allergies || patient.chronic_conditions || patient.medical_history ? `
            <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-medical text-green-600"></i>
                    Medical Information
                </h4>
                <div class="space-y-3">
                    ${patient.allergies ? `
                    <div>
                        <span class="text-sm text-gray-600">Allergies:</span>
                        <p class="mt-1">${patient.allergies}</p>
                    </div>
                    ` : ''}
                    ${patient.chronic_conditions ? `
                    <div>
                        <span class="text-sm text-gray-600">Chronic Conditions:</span>
                        <p class="mt-1">${patient.chronic_conditions}</p>
                    </div>
                    ` : ''}
                    ${patient.medical_history ? `
                    <div>
                        <span class="text-sm text-gray-600">Medical History:</span>
                        <p class="mt-1">${patient.medical_history}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
            ` : ''}

            <!-- NHMP Information -->
            ${patient.is_nhmp ? `
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-tie text-yellow-600"></i>
                    NHMP Staff Information
                </h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-600">Designation:</span>
                        <span class="font-bold ml-2">${patient.designation || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Rank/Grade:</span>
                        <span class="ml-2">${patient.rank || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Office:</span>
                        <span class="ml-2">${patient.office || 'N/A'}</span>
                    </div>
                </div>
            </div>
            ` : ''}

            <!-- Visit Summary -->
            <div class="bg-purple-50 p-4 rounded-lg">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-purple-600"></i>
                    Visit Summary
                </h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-600">Total Visits:</span>
                        <span class="font-bold ml-2">${patient.visits?.length || 0}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Registration Date:</span>
                        <span class="ml-2">${patient.created_at}</span>
                    </div>
                </div>
            </div>
        </div>
    `;

        modal.classList.remove('hidden');
    };

    window.closePatientDetailsModal = function () {
        document.getElementById('patient-details-modal').classList.add('hidden');
    };

    // Close modal when clicking outside
    document.getElementById('patient-details-modal').addEventListener('click', function (event) {
        if (event.target === this) {
            window.closePatientDetailsModal();
        }
    });
</script>
