<div id="duplicate-patient-modal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Patient Already Exists</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="window.closeDuplicateModal()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 mb-4">A patient with similar details already exists:</p>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <span class="font-medium text-gray-700">Name:</span>
                        <span class="ml-2" id="duplicate-patient-name"></span>
                    </div>
                    <div class="flex items-center mb-2">
                        <span class="font-medium text-gray-700">EMRN:</span>
                        <span class="ml-2 font-mono" id="duplicate-patient-emrn"></span>
                    </div>
                    <div class="flex items-center mb-2">
                        <span class="font-medium text-gray-700">Phone:</span>
                        <span class="ml-2" id="duplicate-patient-phone"></span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-medium text-gray-700">CNIC:</span>
                        <span class="ml-2" id="duplicate-patient-cnic"></span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col space-y-3">
                <button type="button" onclick="window.useExistingPatient()"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold shadow-md">
                    <i class="fas fa-plus"></i> Add Visit for This Patient
                </button>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="window.viewPatientHistory()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                        <i class="fas fa-history mr-1"></i> View History
                    </button>
                    <button type="button" onclick="window.closeDuplicateModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
