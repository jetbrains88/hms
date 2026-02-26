@extends('layouts.app')

@section('title', 'Edit Lab Report')

@section('content')
    <div x-data="labReportForm" x-init="init()" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- Header -->
        <div class="bg-white shadow-xl rounded-2xl mx-4 mt-6 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Lab Report</h1>
                    <p class="text-gray-600 mt-2">Update laboratory report details</p>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="text-gray-600">Test Code: <strong>{{ $labReport->test_code }}</strong></span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{
    ($labReport->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
    ($labReport->status === 'processing' ? 'bg-blue-100 text-blue-800' :
    ($labReport->status === 'completed' ? 'bg-green-100 text-green-800' :
    'bg-red-100 text-red-800')))
}}">
                        {{ ucfirst($labReport->status) }}
                    </span>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('lab.reports.show', $labReport->id) }}"
                       class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                        <i class="fas fa-eye mr-2"></i>View Report
                    </a>
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
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Info (Readonly) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-4">
                            <div class="font-medium text-gray-900">{{ $labReport->patient->name }}</div>
                            <div class="text-sm text-gray-600">
                                CNIC: {{ $labReport->patient->cnic }} |
                                EMRN: {{ $labReport->patient->emrn }} |
                                Age: {{ \Carbon\Carbon::parse($labReport->patient->dob)->age }} years
                            </div>
                        </div>
                    </div>

                    <!-- Test Selection (Consolidated) -->
                    <div>
                        <label for="lab_test_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Test Name / Report Type <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select x-model="form.lab_test_type_id" id="lab_test_type_id" required
                                    @change="updateTestDetails"
                                    @blur="touched.lab_test_type_id = true"
                                    :aria-invalid="!form.lab_test_type_id && touched.lab_test_type_id"
                                    aria-describedby="test-type-error"
                                    :class="{
                                        'border-red-500': !form.lab_test_type_id && touched.lab_test_type_id,
                                        'border-green-500': form.lab_test_type_id && touched.lab_test_type_id,
                                        'border-gray-300': !touched.lab_test_type_id
                                    }"
                                    class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Test Report</option>
                                @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}"
                                            data-name="{{ $type->name }}"
                                            data-type="{{ $type->department ?? 'General' }}"
                                            {{ $labReport->lab_test_type_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->department ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                            <div x-show="form.lab_test_type_id && touched.lab_test_type_id" class="absolute inset-y-0 right-8 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                        <p x-show="!form.lab_test_type_id && touched.lab_test_type_id"
                           id="test-type-error" role="alert" aria-live="assertive"
                           class="text-red-500 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> Please select a test report type
                        </p>
                        <input type="hidden" x-model="form.test_name">
                        <input type="hidden" x-model="form.test_type">
                    </div>

                    <!-- Doctor Selection -->
                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Referring Doctor <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <select x-model="form.doctor_id" id="doctor_id" required
                                    @blur="touched.doctor_id = true"
                                    :aria-invalid="!form.doctor_id && touched.doctor_id"
                                    aria-describedby="doctor-error"
                                    :class="{
                                        'border-red-500': !form.doctor_id && touched.doctor_id,
                                        'border-green-500': form.doctor_id && touched.doctor_id,
                                        'border-gray-300': !touched.doctor_id
                                    }"
                                    class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option
                                        value="{{ $doctor->id }}" {{ $labReport->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div x-show="form.doctor_id && touched.doctor_id" class="absolute inset-y-0 right-8 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                        <p x-show="!form.doctor_id && touched.doctor_id"
                           id="doctor-error" role="alert" aria-live="assertive"
                           class="text-red-500 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> Please select the referring doctor
                        </p>
                    </div>

                    <!-- Lab Number (Test Code) -->
                    <div>
                        <label for="lab_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Lab Number
                        </label>
                        <input type="text" x-model="form.lab_number" id="lab_number"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ $labReport->lab_number }}">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select x-model="form.status" id="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="pending" {{ $labReport->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $labReport->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $labReport->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $labReport->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Comments -->
                    <div class="md:col-span-2">
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">General Comments / Notes</label>
                        <textarea x-model="form.comments" id="comments" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $labReport->comments }}</textarea>
                    </div>
                </div>

                <!-- Dirty check info banner -->
                <div x-show="!isDirty()" class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm text-gray-500 text-center">
                    <i class="fas fa-info-circle mr-1"></i> Make changes to enable the update button.
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('lab.reports.show', $labReport->id) }}"
                       class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="button" @click="deleteReport"
                            class="bg-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                    <button type="submit"
                            :disabled="isSubmitting || !isDirty() || !isFormValid()"
                            :class="{'bg-blue-600 hover:bg-blue-700': !isSubmitting && isDirty() && isFormValid(), 'bg-blue-400 cursor-not-allowed': isSubmitting || !isDirty() || !isFormValid()}"
                            class="text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-200">
                        <span x-show="!isSubmitting">Update Lab Report</span>
                        <span x-show="isSubmitting">Updating...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Overlay -->
        <div x-show="isSubmitting" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <div class="text-gray-700 font-medium">Updating lab report...</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('labReportForm', () => ({
                isSubmitting: false,
                originalForm: null,
                isOptimizedTest: false,

                // Track which fields have been interacted with (blurred)
                touched: {
                    lab_test_type_id: false,
                    doctor_id: false,
                },

                form: {
                    patient_id: {{ $labReport->patient_id }},
                    doctor_id: {{ $labReport->doctor_id ?? 'null' }},
                    technician_id: {{ auth()->id() }},
                    lab_test_type_id: '{{ $labReport->lab_test_type_id }}',
                    test_name: '{{ $labReport->test_name }}',
                    test_type: '{{ $labReport->test_type }}',
                    lab_number: '{{ $labReport->lab_number }}',
                    status: '{{ $labReport->status }}',
                    comments: `{{ $labReport->comments }}`,
                    sample_type: 'Blood',
                    priority: 'normal',
                },

                init() {
                    this.updateTestDetails();
                    this.checkOptimization();
                    // Save original form snapshot for dirty check
                    this.originalForm = JSON.stringify(this.form);
                },

                // --- Validation ---

                isFormValid() {
                    return !!this.form.doctor_id && !!this.form.lab_test_type_id;
                },

                isDirty() {
                    return JSON.stringify(this.form) !== this.originalForm;
                },

                checkOptimization() {
                    const optimizedTests = ['Special Chemistry', 'Urine Routine Examination'];
                    this.isOptimizedTest = optimizedTests.includes(this.form.test_name);
                },

                updateTestDetails() {
                    const select = document.querySelector('select[x-model="form.lab_test_type_id"]');
                    if (select && select.selectedOptions.length > 0) {
                        const option = select.selectedOptions[0];
                        if (option.value) {
                            this.form.test_name = option.dataset.name;
                            this.form.test_type = option.dataset.type;
                            this.checkOptimization();
                        }
                    }
                },

                // --- Submission ---

                async submitForm() {
                    if (this.isSubmitting) return;

                    // Touch all required fields to show any remaining errors
                    this.touched.doctor_id = true;
                    this.touched.lab_test_type_id = true;

                    if (!this.isFormValid()) {
                        showNotification('Please fill in all required fields.', 'error');
                        return;
                    }

                    if (!this.isDirty()) {
                        showNotification('No changes detected.', 'info');
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        const response = await fetch('{{ route("lab.reports.update", $labReport->id) }}', {
                            method: 'PUT',
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

                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        } else {
                            showNotification(data.message || 'Failed to update lab report', 'error');

                            if (data.errors) {
                                Object.entries(data.errors).forEach(([field, errors]) => {
                                    errors.forEach(error => {
                                        showNotification(`${field}: ${error}`, 'error');
                                    });
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error updating form:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                // --- Deletion ---

                async deleteReport() {
                    if (!confirm('Are you sure you want to delete this lab report? This action cannot be undone.')) {
                        return;
                    }

                    try {
                        const response = await fetch('{{ route("lab.reports.destroy", $labReport->id) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            showNotification(data.message, 'success');

                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        } else {
                            showNotification(data.message || 'Failed to delete lab report', 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting report:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                },
            }));
        });
    </script>
@endsection
