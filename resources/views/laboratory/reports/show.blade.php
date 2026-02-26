@extends('layouts.app')

@section('title', 'Lab Report Details')

@section('content')
    @php
        $showUnits = !in_array($labReport->testType->name ?? '', ['Special Chemistry', 'Urine Routine Examination']);
    @endphp
    <div x-data="labReportDetails" class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- Header -->
        <div class="mx-4 mt-6">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative flex items-center shadow-sm"
                    role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative flex items-center shadow-sm"
                    role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Lab Report Details</h1>
                        <div class="flex items-center mt-2 space-x-6">
                            <span class="text-gray-600">Lab Number: <strong>{{ $labReport->lab_number }}</strong></span>
                            @if ($labReport->testType)
                                <span class="text-gray-600">Test: <strong>{{ $labReport->testType->name }}</strong></span>
                                <span class="text-gray-600">Department:
                                    <strong>{{ $labReport->testType->department }}</strong></span>
                            @endif
                            <span class="px-3 py-1 rounded-full text-sm font-medium"
                                :class="{
                                    'bg-yellow-100 text-yellow-800': '{{ $labReport->status }}'
                                    === 'pending',
                                    'bg-blue-100 text-blue-800': '{{ $labReport->status }}'
                                    === 'processing',
                                    'bg-green-100 text-green-800': '{{ $labReport->status }}'
                                    === 'completed',
                                    'bg-red-100 text-red-800': '{{ $labReport->status }}'
                                    === 'cancelled'
                                }">
                                {{ ucfirst($labReport->status) }}
                            </span>
                            @if ($labReport->is_verified)
                                <div
                                    class="flex items-center gap-1 text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded border border-green-100">
                                    <i class="fas fa-check-circle"></i>
                                    Verified by {{ $labReport->verifiedBy->name ?? 'Unknown' }}
                                    <span class="text-gray-400">|</span>
                                    {{ $labReport->verified_at ? $labReport->verified_at->format('M d, H:i') : '' }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('lab.reports.edit', $labReport->id) }}"
                            class="bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <a href="{{ route('lab.reports.print', $labReport->id) }}" target="_blank"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                            <i class="fas fa-print mr-2"></i>Print
                        </a>
                        @if ($labReport->status === 'pending' || $labReport->status === 'processing')
                            <button @click="confirmStatusChange('completed')"
                                class="bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                                <i class="fas fa-check mr-2"></i>Mark Complete
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mx-4 mt-6">
                <!-- Patient & Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Test Results -->
                    <div class="bg-white shadow-xl rounded-2xl p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Test Results</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div>
                                <h3 class="font-semibold text-gray-700 mb-4">Order Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Tests:</span>
                                        <span class="font-medium">{{ $labReport->results->count() }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Created At:</span>
                                        <span class="font-medium">{{ $labReport->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline -->
                            <div>
                                <h3 class="font-semibold text-gray-700 mb-4">Timeline</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Requested:</span>
                                        <span
                                            class="font-medium">{{ $labReport->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    @if ($labReport->collection_date)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Sample Collected:</span>
                                            <span
                                                class="font-medium">{{ $labReport->collection_date->format('M d, Y h:i A') }}</span>
                                        </div>
                                    @endif
                                    @if ($labReport->reporting_date)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Results Ready:</span>
                                            <span
                                                class="font-medium">{{ $labReport->reporting_date->format('M d, Y h:i A') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Results Table -->
                        @php
                            $hasResults = $labReport->results->count() > 0;
                            // If results exist, use them. Otherwise, use expected parameters to show the structure.
                            $displayItems = $hasResults ? $labReport->results : $labReport->expected_parameters;
                        @endphp

                        @if ($displayItems->count() > 0)
                            <div class="mt-8">
                                <h3 class="font-semibold text-gray-700 mb-4">
                                    {{ $hasResults ? 'Test Results' : 'Pending Test Parameters' }}
                                </h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">
                                                    Parameter</th>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Result
                                                </th>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Ref.
                                                    Range</th>
                                                @if ($showUnits)
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Unit
                                                    </th>
                                                @endif
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($displayItems as $item)
                                                @php
                                                    // Determine if we are looping through Result objects or Parameter objects
                                                    $isResult = $hasResults;
                                                    $parameter = $isResult ? $item->parameter : $item;
                                                    $resultValue = $isResult ? $item->result_value : '-';
                                                    $refRange = $parameter->reference_range ?? 'N/A';
                                                    $unit = $parameter->unit ?? 'N/A';
                                                    $isAbnormal = $isResult ? $item->is_abnormal : false;
                                                @endphp
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        {{ $parameter->name ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                        {{ $resultValue }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-600 overflow-wrap: break-word;">
                                                        {{ $refRange }}
                                                    </td>
                                                    @if ($showUnits)
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            {{ $unit }}
                                                        </td>
                                                    @endif
                                                    <td class="px-4 py-3">
                                                        @if ($isResult)
                                                            @if ($isAbnormal)
                                                                <span
                                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    <i class="fas fa-exclamation-circle mr-1"></i> Abnormal
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    <i class="fas fa-check-circle mr-1"></i> Normal
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                <i class="fas fa-clock mr-1"></i> Pending
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Interpretation -->
                        @if ($labReport->interpretation)
                            <div class="mt-8">
                                <h3 class="font-semibold text-gray-700 mb-4">Interpretation</h3>
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <p class="text-gray-700">{{ $labReport->interpretation }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Recommendations -->
                        @if ($labReport->recommendations)
                            <div class="mt-6">
                                <h3 class="font-semibold text-gray-700 mb-4">Recommendations</h3>
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                    <p class="text-gray-700">{{ $labReport->recommendations }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Results Form -->
                    @if (in_array($labReport->status, ['pending', 'processing']))
                        <div class="bg-white shadow-xl rounded-2xl p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Insert Results Values</h2>
                            <form @submit.prevent="submitResults" id="resultsForm">
                                <div class="space-y-6">
                                    <!-- Results Input -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Parameters to be
                                            added</label>
                                        <div id="resultsContainer">
                                            <!-- Dynamic results fields will be added here -->
                                        </div>
                                    </div>

                                    <!-- Interpretation -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Interpretation</label>
                                        <textarea name="interpretation" rows="4"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Enter interpretation of results..."></textarea>
                                    </div>

                                    <!-- Critical Result -->
                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_critical" name="is_critical"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <label for="is_critical" class="ml-2 text-sm text-gray-700">
                                            Mark as Critical Result
                                        </label>
                                    </div>

                                    <div class="flex justify-end space-x-4">
                                        <button type="button" @click="cancelSubmission"
                                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-shadow duration-300">
                                            <i class="fas fa-check mr-2"></i> Submit Results
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Add Sample Information to Sidebar -->
                    @if ($labReport->items->first()?->sampleInfo)
                        @php $sampleInfo = $labReport->items->first()->sampleInfo; @endphp
                        <div class="bg-white shadow-xl rounded-2xl p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Sample Information</h2>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-600">Sample ID</p>
                                    <p class="font-medium text-gray-900">{{ $sampleInfo->sample_id ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Collection Time</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $sampleInfo->sample_collected_at ? \Carbon\Carbon::parse($sampleInfo->sample_collected_at)->format('M d, Y h:i A') : 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Container</p>
                                    <p class="font-medium text-gray-900">
                                        {{ ucfirst($sampleInfo->sample_container ?? 'Not specified') }}</p>
                                </div>
                                @if ($sampleInfo->sample_quantity)
                                    <div>
                                        <p class="text-sm text-gray-600">Quantity</p>
                                        <p class="font-medium text-gray-900">{{ $sampleInfo->sample_quantity }}
                                            {{ $sampleInfo->sample_quantity_unit }}</p>
                                    </div>
                                @endif
                                @if ($sampleInfo->sample_condition)
                                    <div>
                                        <p class="text-sm text-gray-600">Condition</p>
                                        <p class="font-medium text-gray-900">{{ $sampleInfo->sample_condition }}</p>
                                    </div>
                                @endif
                                @if ($sampleInfo->special_instructions)
                                    <div>
                                        <p class="text-sm text-gray-600">Special Instructions</p>
                                        <p class="font-medium text-gray-900">{{ $sampleInfo->special_instructions }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Patient Info -->
                    <div class="bg-white shadow-xl rounded-2xl p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Patient Information</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Patient Name</p>
                                <p class="font-medium text-gray-900">{{ $labReport->patient->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">CNIC</p>
                                <p class="font-medium text-gray-900">{{ $labReport->patient->cnic }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">EMRN</p>
                                <p class="font-medium text-gray-900">{{ $labReport->patient->emrn }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Age</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($labReport->patient->dob)->age }}
                                    years</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Gender</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($labReport->patient->gender) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Blood Group</p>
                                <p class="font-medium text-gray-900">
                                    {{ $labReport->patient->blood_group ?? 'Not Specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Staff -->
                    <div class="bg-white shadow-xl rounded-2xl p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Medical Staff</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Requesting Doctor</p>
                                <p class="font-medium text-gray-900">{{ $labReport->doctor->name }}</p>
                            </div>
                            @if ($labReport->technician)
                                <div>
                                    <p class="text-sm text-gray-600">Lab Technician</p>
                                    <p class="font-medium text-gray-900">{{ $labReport->technician->name }}</p>
                                </div>
                            @endif
                            @if ($labReport->verified_by)
                                <div>
                                    <p class="text-sm text-gray-600">Verified By</p>
                                    <p class="font-medium text-gray-900">{{ $labReport->verifier->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $labReport->verified_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @if ($labReport->status === 'completed')
                        <div class="bg-white shadow-xl rounded-2xl p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h2>
                            <div class="space-y-3">
                                @if (!$labReport->verified_by)
                                    <button @click="showVerifyModal = true"
                                        class="w-full bg-green-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors duration-200">
                                        <i class="fas fa-check-circle mr-2"></i> Verify Report
                                    </button>
                                @endif

                                <button @click="sendToDoctor"
                                    class="w-full bg-indigo-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-colors duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i> Send to Doctor
                                </button>

                                <a href="{{ route('lab.reports.download-pdf', $labReport->id) }}"
                                    class="block w-full bg-purple-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-purple-700 transition-colors duration-200 text-center">
                                    <i class="fas fa-file-download mr-2"></i> Download Report (PDF)
                                </a>

                                @if ($labReport->file_path)
                                    <a href="{{ Storage::url($labReport->file_path) }}" target="_blank"
                                        class="block w-full bg-gray-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors duration-200 text-center">
                                        <i class="fas fa-paperclip mr-2"></i> View Uploaded File
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-white shadow-xl rounded-2xl p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Workflow Actions</h2>
                            <div class="space-y-3">
                                @if ($labReport->status === 'pending')
                                    <button @click="confirmStatusChange('processing')"
                                        class="w-full bg-blue-600 text-white px-4 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-flask mr-2"></i> Mark as Processing
                                    </button>
                                @endif

                                <div
                                    class="p-4 bg-blue-50 rounded-xl border border-blue-100 italic text-sm text-blue-700 text-center">
                                    Complete the report to enable further actions like verification and PDF download.
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modals -->

            <!-- Verification Modal -->
            <div x-show="showVerifyModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showVerifyModal = false">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Verify Report</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Are you sure you want to verify this report? You can add optional notes below.
                                        </p>
                                        <textarea x-model="verificationNotes"
                                            class="mt-3 w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-green-500 focus:border-green-500"
                                            rows="3" placeholder="Verification notes (optional)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="verifyReport"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Verify
                            </button>
                            <button type="button" @click="showVerifyModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showConfirmModal = false">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-info text-blue-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Action</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" x-text="confirmMessage"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="executeStatusChange"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Confirm
                            </button>
                            <button type="button" @click="showConfirmModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Toast (Replaces alert) -->
            <div x-show="showToast" x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed bottom-0 right-0 m-6 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden z-50"
                style="display: none;">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas"
                                :class="toastType === 'success' ? 'fa-check-circle text-green-400' :
                                    'fa-exclamation-circle text-red-400'"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900" x-text="toastMessage"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="showToast = false"
                                class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="sr-only">Close</span>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Parameter Check Modal -->
                <div x-show="showParameterWarning" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity"
                            @click="showParameterWarning = false"></div>
                        <div
                            class="relative bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full p-6 text-center">
                            <div
                                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">No Results Found</h3>
                            <p class="text-sm text-gray-500 mb-6">
                                You have not inserted any test parameter results yet.
                                Please insert the results before marking this report as completed.
                            </p>
                            <button @click="showParameterWarning = false"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-blue-700">
                                Okay, I'll add them
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('labReportDetails', () => ({
                        resultFields: [],
                        showVerifyModal: false,
                        verificationNotes: '',
                        showConfirmModal: false,
                        confirmMessage: '',
                        pendingStatus: null,
                        showToast: false,
                        toastMessage: '',
                        toastType: 'success',
                        showParameterWarning: false,

                        init() {
                            // Initialize with existing results if any
                            @if ($labReport->hasResults())
                                // Load existing results
                                const results = @json($labReport->formatted_results);
                                results.forEach(item => {
                                    this.addResultField(
                                        item.test,
                                        item.result,
                                        item.normal_range,
                                        item.units,
                                        item.parameter_id // Keep track of ID
                                    );
                                });
                            @else
                                // Load expected parameters for new entry
                                const parameters = @json($labReport->expected_parameters);
                                if (parameters.length > 0) {
                                    parameters.forEach(param => {
                                        this.addResultField(
                                            param.name,
                                            '',
                                            param.formatted_range,
                                            param.unit,
                                            param.id // Pass ID
                                        );
                                    });
                                }
                            @endif
                        },

                        addResultField(parameter = '', result = '', normalRange = '', unit = '', parameterId =
                            '') {
                            const id = 'result_' + Date.now() + Math.random();
                            const showUnits = {{ $showUnits ? 'true' : 'false' }};
                            this.resultFields.push({
                                id,
                                parameter,
                                result,
                                normalRange,
                                unit,
                                parameterId
                            });

                            // Update DOM
                            setTimeout(() => {
                                const container = document.getElementById('resultsContainer');
                                if (!container) return;

                                const gridCols = showUnits ? 'md:grid-cols-4' : 'md:grid-cols-3';
                                const template = `
                        <div class="grid grid-cols-1 ${gridCols} gap-4 mb-4" data-id="${id}">
                            <input type="hidden" name="parameters[]" value="${parameter}">
                            <input type="hidden" name="parameter_ids[]" value="${parameterId}">

                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 font-medium">
                                ${parameter}
                            </div>

                            <input type="text" name="results[]" placeholder="Result"
                                   value="${result}" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 text-sm">
                                ${normalRange || 'N/A'}
                            </div>

                            ${showUnits ? `
                                                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 text-sm">
                                                        ${unit || 'N/A'}
                                                    </div>
                                                    ` : ''}
                        </div>
                    `;
                                container.insertAdjacentHTML('beforeend', template);
                            });
                        },

                        async submitResults() {
                            const form = document.getElementById('resultsForm');
                            const formData = new FormData(form);

                            const fileInput = form.querySelector('input[type="file"]');

                            const resultValues = {};

                            const params = formData.getAll('parameters[]');
                            const paramIds = formData.getAll('parameter_ids[]');
                            const res = formData.getAll('results[]');

                            // Prioritize finding IDs
                            // If paramIds are present, use them. Otherwise fallback to names (legacy compatibility)

                            if (res.length > 0) {
                                res.forEach((value, index) => {
                                    if (paramIds[index]) {
                                        resultValues[paramIds[index]] = value;
                                    } else if (params[index]) {
                                        resultValues[params[index]] = value;
                                    }
                                });
                            }

                            const payload = {
                                result_values: resultValues,
                                interpretation: formData.get('interpretation'),
                                recommendations: formData.get('recommendations'),
                                is_critical: formData.get('is_critical') === 'on'
                            };

                            try {
                                const baseUrl = window.location.origin;
                                const url = `${baseUrl}/laboratory/reports/{{ $labReport->id }}/results`;
                                const response = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify(payload)
                                });

                                const text = await response.text();
                                let data;
                                try {
                                    data = JSON.parse(text);
                                } catch (e) {
                                    console.error('Invalid JSON response:', text);
                                    throw new Error(
                                        'Server returned an invalid response. Please check logs.');
                                }

                                if (data.success) {
                                    if (fileInput && fileInput.files.length > 0) {
                                        await this.uploadFile(fileInput.files[0]);
                                    }

                                    showSuccess('Results submitted successfully!', 'success');
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    showError(data.message || 'Failed to submit results', 'error');
                                }
                            } catch (error) {
                                console.error('Error submitting results:', error);
                                showError('Failed to submit results', 'error');
                            }
                        },

                        async uploadFile(file) {
                            const formData = new FormData();
                            formData.append('file', file);

                            try {
                                const baseUrl = window.location.origin;
                                const url = `${baseUrl}/laboratory/reports/{{ $labReport->id }}/upload`;
                                await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: formData
                                });
                            } catch (error) {
                                console.error('Error uploading file:', error);
                            }
                        },

                        confirmStatusChange(status) {
                            // Check if results exist before allowing completion
                            if (status === 'completed') {
                                const resultsCount = {{ $labReport->results->count() }};
                                if (resultsCount === 0) {
                                    this.showParameterWarning = true;
                                    return;
                                }
                            }

                            this.pendingStatus = status;
                            this.confirmMessage = `Are you sure you want to change the status to ${status}?`;
                            this.showConfirmModal = true;
                        },

                        async executeStatusChange() {
                            if (!this.pendingStatus) return;

                            const status = this.pendingStatus;
                            this.showConfirmModal = false;

                            try {
                                const baseUrl = window.location.origin;
                                const url = `${baseUrl}/laboratory/reports/{{ $labReport->id }}/status`;
                                const response = await fetch(url, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        status
                                    })
                                });

                                const data = await response.json();
                                if (data.success) {
                                    showSuccess(`Status updated to ${status}`, 'success');
                                    setTimeout(() => window.location.reload(), 1000);
                                } else {
                                    showError(data.message || 'Failed to update status', 'error');
                                }
                            } catch (error) {
                                console.error('Error updating status:', error);
                                showError('Failed to update status', 'error');
                            }
                        },

                        async verifyReport() {
                            const notes = this.verificationNotes;
                            this.showVerifyModal = false;

                            try {
                                const baseUrl = window.location.origin;
                                const url = `${baseUrl}/laboratory/reports/{{ $labReport->id }}/verify`;
                                const response = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        verification_notes: notes
                                    })
                                });

                                const data = await response.json();
                                if (data.success) {
                                    showSuccess('Report verified successfully!', 'success');
                                    setTimeout(() => window.location.reload(), 1000);
                                } else {
                                    showError(data.message || 'Failed to verify report', 'error');
                                }
                            } catch (error) {
                                console.error('Error verifying report:', error);
                                showError('Failed to verify report', 'error');
                            }
                        },

                        async sendToDoctor() {
                            try {
                                const baseUrl = window.location.origin;
                                const url =
                                    `${baseUrl}/laboratory/reports/{{ $labReport->id }}/notify-doctor`;
                                const response = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    }
                                });

                                const data = await response.json();
                                if (data.success) {
                                    showSuccess('Notification sent to doctor', 'success');
                                }
                            } catch (error) {
                                console.error('Error notifying doctor:', error);
                                showError('Failed to notify doctor', 'error');
                            }
                        },

                        cancelSubmission() {
                            document.getElementById('resultsForm').reset();
                            const container = document.getElementById('resultsContainer');
                            if (container) container.innerHTML = '';
                            this.resultFields = [];
                            // Optionally reload to restore initial state
                            window.location.reload();
                        }
                    }));
                });
            </script>
        @endsection
