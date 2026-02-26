<div id="medical-history-modal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Medical History</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl"
                        onclick="window.closeMedicalHistoryModal()">
                    &times;
                </button>
            </div>
        </div>

        <div class="p-6">
            <div id="medical-history-content">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    // Medical History Modal Functions
    window.showMedicalHistoryModal = function (patient) {
        const modal = document.getElementById('medical-history-modal');
        const content = document.getElementById('medical-history-content');

        content.innerHTML = `
        <div class="space-y-6">
            <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-md text-green-600"></i>
                    Medical Information for ${patient.name}
                </h4>

                ${patient.allergies || patient.chronic_conditions || patient.medical_history ? `
                <div class="space-y-4">
                    ${patient.allergies ? `
                    <div>
                        <h5 class="font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-allergies text-red-500"></i>
                            Known Allergies
                        </h5>
                        <div class="bg-white p-3 rounded border border-gray-200">
                            ${patient.allergies}
                        </div>
                    </div>
                    ` : ''}

                    ${patient.chronic_conditions ? `
                    <div>
                        <h5 class="font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-heartbeat text-blue-500"></i>
                            Chronic Conditions
                        </h5>
                        <div class="bg-white p-3 rounded border border-gray-200">
                            ${patient.chronic_conditions}
                        </div>
                    </div>
                    ` : ''}

                    ${patient.medical_history ? `
                    <div>
                        <h5 class="font-medium text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-history text-purple-500"></i>
                            Medical History
                        </h5>
                        <div class="bg-white p-3 rounded border border-gray-200">
                            ${patient.medical_history}
                        </div>
                    </div>
                    ` : ''}
                </div>
                ` : `
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                        <i class="fas fa-file-medical-alt text-4xl"></i>
                    </div>
                    <p class="text-gray-500">No medical history recorded for this patient.</p>
                </div>
                `}
            </div>
        </div>
    `;

        modal.classList.remove('hidden');
    };

    window.closeMedicalHistoryModal = function () {
        document.getElementById('medical-history-modal').classList.add('hidden');
    };

    // Close modal when clicking outside
    document.getElementById('medical-history-modal').addEventListener('click', function (event) {
        if (event.target === this) {
            window.closeMedicalHistoryModal();
        }
    });
</script>
