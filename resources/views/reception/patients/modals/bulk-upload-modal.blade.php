<div id="bulk-upload-modal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Bulk Patient Upload</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl"
                        onclick="window.closeBulkUploadModal()">
                    &times;
                </button>
            </div>
        </div>

        <form id="bulk-upload-form" class="p-6">
            @csrf
            <div class="space-y-6">
                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload CSV File
                    </label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                 viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="csv-file"
                                       class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="csv-file" name="csv_file" type="file" accept=".csv" class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">CSV up to 10MB</p>
                        </div>
                    </div>
                    <div id="file-name" class="mt-2 text-sm text-gray-500 hidden"></div>
                </div>

                <!-- Template Download -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-bold text-gray-800 mb-2">CSV Template</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Download our template file to ensure proper formatting. Required columns: name, phone, dob,
                        gender.
                    </p>
                    <a href="{{ route('reception.patients.template') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                        <i class="fas fa-download"></i>
                        Download Template
                    </a>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="window.closeBulkUploadModal()"
                            class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="bulk-upload-submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-lg hover:shadow-lg transition-all flex items-center gap-2">
                        <i class="fas fa-spinner fa-spin hidden" id="bulk-upload-spinner"></i>
                        <span id="bulk-upload-submit-text">Upload & Process</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Bulk Upload Modal Functions
    window.showBulkUploadModal = function () {
        document.getElementById('bulk-upload-modal').classList.remove('hidden');
    };

    window.closeBulkUploadModal = function () {
        document.getElementById('bulk-upload-modal').classList.add('hidden');
    };

    // File input handler
    document.getElementById('csv-file').addEventListener('change', function (e) {
        const fileName = document.getElementById('file-name');
        if (this.files.length > 0) {
            fileName.textContent = `Selected file: ${this.files[0].name}`;
            fileName.classList.remove('hidden');
        } else {
            fileName.classList.add('hidden');
        }
    });

    // Form submission
    document.getElementById('bulk-upload-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const fileInput = document.getElementById('csv-file');
        if (!fileInput.files.length) {
            showError('Please select a CSV file to upload', 'Error');
            return;
        }

        const formData = new FormData();
        formData.append('csv_file', fileInput.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        const submitBtn = document.getElementById('bulk-upload-submit');
        const spinner = document.getElementById('bulk-upload-spinner');
        const submitText = document.getElementById('bulk-upload-submit-text');

        // Disable button and show spinner
        submitBtn.disabled = true;
        spinner.classList.remove('hidden');
        submitText.textContent = 'Processing...';

        try {
            const response = await fetch('{{ route("reception.patients.bulk-upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showSuccess(`Successfully uploaded ${result.processed} patients`, 'Success');
                window.closeBulkUploadModal();

                // Refresh patient list if Alpine component exists
                if (window.alpinePatientManagement) {
                    window.alpinePatientManagement.refreshExistingPatients();
                }
            } else {
                showError(result.message || 'Error processing upload', 'Error');
            }
        } catch (error) {
            console.error('Error uploading file:', error);
            showError('Network error. Please try again.', 'Network Error');
        } finally {
            // Re-enable button
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
            submitText.textContent = 'Upload & Process';
        }
    });

    // Close modal when clicking outside
    document.getElementById('bulk-upload-modal').addEventListener('click', function (event) {
        if (event.target === this) {
            window.closeBulkUploadModal();
        }
    });
</script>
