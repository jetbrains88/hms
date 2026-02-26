@extends('layouts.app')

@section('title', 'E-Consultation')
@section('page-title', 'E-Consultation')
@section('page-description', 'Remote consultations and telemedicine')

@section('content')
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Search Patient</p>
                        <p class="text-sm text-blue-700 mt-2">Find patient by EMRN, name, or phone</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="relative">
                        <input type="text" id="patientSearch"
                               placeholder="Enter EMRN, name, or phone..."
                               class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div id="searchResults"
                             class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Office Patients</p>
                        <p class="text-sm text-green-700 mt-2">Select office for batch consultation</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <select id="officeSelect"
                            class="w-full px-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Office</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                        @endforeach
                    </select>
                    <div id="officePatients" class="mt-2 hidden">
                        <!-- Office patients will be loaded here -->
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Recent Patients</p>
                        <p class="text-sm text-purple-700 mt-2">Quick access to recent patients</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-history text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="space-y-2">
                        @foreach($recentPatients as $patient)
                            <button onclick="startTeleconsultation({{ $patient->id }})"
                                    class="w-full text-left px-3 py-2 bg-white rounded-lg border border-purple-200 hover:border-purple-300 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900">{{ $patient->name }}</span>
                                    <i class="fas fa-video text-purple-600"></i>
                                </div>
                                <span class="text-xs text-gray-500">{{ $patient->emrn }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Teleconsultation Form -->
        <div id="consultationForm" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Start Teleconsultation</h3>
                <button onclick="closeConsultationForm()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="teleconsultationForm">
                @csrf
                <input type="hidden" id="selectedPatientId" name="patient_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Info -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-700 border-b pb-2">Patient Information</h4>
                        <div id="patientInfo" class="space-y-3">
                            <!-- Patient info will be loaded here -->
                        </div>
                    </div>

                    <!-- Consultation Details -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-700 border-b pb-2">Consultation Details</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Type</label>
                            <select name="consultation_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="telemedicine">Telemedicine</option>
                                <option value="office_visit">Office Visit</option>
                                <option value="follow_up">Follow-up</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vitals (Optional)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" step="0.1" name="vitals[temperature]"
                                       placeholder="Temperature °C"
                                       class="px-3 py-2 border border-gray-300 rounded-lg">
                                <input type="number" name="vitals[pulse]"
                                       placeholder="Pulse"
                                       class="px-3 py-2 border border-gray-300 rounded-lg">
                                <input type="number" name="vitals[blood_pressure_systolic]"
                                       placeholder="BP Systolic"
                                       class="px-3 py-2 border border-gray-300 rounded-lg">
                                <input type="number" name="vitals[blood_pressure_diastolic]"
                                       placeholder="BP Diastolic"
                                       class="px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="consultation_notes" rows="3"
                                      placeholder="Initial consultation notes..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeConsultationForm()"
                            class="px-4 py-2 text-gray-700 hover:text-gray-900">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-video mr-2"></i>
                        Start Consultation
                    </button>
                </div>
            </form>
        </div>

        <!-- Medical History Modal -->
        <div id="medicalHistoryModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Medical History</h3>
                            <button onclick="closeMedicalHistory()" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div id="medicalHistoryContent">
                            <!-- Medical history will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let debounceTimer;

        // Patient search with debounce
        document.getElementById('patientSearch').addEventListener('input', function (e) {
            clearTimeout(debounceTimer);
            const searchTerm = e.target.value.trim();

            if (searchTerm.length < 2) {
                document.getElementById('searchResults').classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                searchPatients(searchTerm);
            }, 300);
        });

        function searchPatients(searchTerm) {
            fetch(`/doctor/patients/search?search=${encodeURIComponent(searchTerm)}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.getElementById('searchResults');

                    if (data.success && data.patients.length > 0) {
                        let html = '';
                        data.patients.forEach(patient => {
                            html += `
                <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                     onclick="selectPatient(${patient.id})">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">${patient.name}</p>
                            <p class="text-xs text-gray-500">
                                ${patient.emrn} • ${patient.age_formatted} • ${patient.gender}
                                ${patient.is_nhmp ? '<span class="ml-2 px-1.5 py-0.5 bg-green-100 text-green-800 text-xs rounded">NHMP</span>' : ''}
                            </p>
                        </div>
                        <button onclick="event.stopPropagation(); viewMedicalHistory(${patient.id})"
                                class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-history"></i>
                        </button>
                    </div>
                </div>
                `;
                        });

                        resultsContainer.innerHTML = html;
                        resultsContainer.classList.remove('hidden');
                    } else {
                        html = '<div class="p-3 text-gray-500 text-center">No patients found</div>';
                        resultsContainer.innerHTML = html;
                        resultsContainer.classList.remove('hidden');
                    }
                });
        }

        // Office selection
        document.getElementById('officeSelect').addEventListener('change', function (e) {
            const officeId = e.target.value;
            const container = document.getElementById('officePatients');

            if (!officeId) {
                container.classList.add('hidden');
                return;
            }

            fetch(`/doctor/office/${officeId}/patients`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.patients.length > 0) {
                        let html = '<div class="space-y-2 max-h-60 overflow-y-auto">';
                        data.patients.forEach(patient => {
                            html += `
                <button onclick="selectPatient(${patient.id})"
                        class="w-full text-left p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">${patient.name}</p>
                            <p class="text-xs text-gray-500">
                                ${patient.emrn} • ${patient.designation ? patient.designation.name : patient.rank}
                            </p>
                        </div>
                        <i class="fas fa-user-md text-blue-600"></i>
                    </div>
                </button>
                `;
                        });
                        html += '</div>';

                        container.innerHTML = html;
                        container.classList.remove('hidden');
                    } else {
                        container.innerHTML = '<p class="text-gray-500 text-center py-4">No patients in this office</p>';
                        container.classList.remove('hidden');
                    }
                });
        });

        function selectPatient(patientId) {
            // Hide search results
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('patientSearch').value = '';

            // Get patient details
            fetch(`/doctor/patient/${patientId}/history`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const patient = data.data.patient;
                        document.getElementById('selectedPatientId').value = patient.id;

                        // Display patient info
                        let html = `
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h5 class="font-bold text-gray-900">${patient.name}</h5>
                            <p class="text-sm text-gray-600">
                                ${patient.emrn} • ${patient.age_formatted} • ${patient.gender}
                                ${patient.blood_group ? ' • ' + patient.blood_group : ''}
                            </p>
                        </div>
                        ${patient.is_nhmp ?
                            '<span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">NHMP</span>' : ''}
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-500">Phone:</span>
                            <span class="ml-2">${patient.formatted_phone || 'N/A'}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Allergies:</span>
                            <span class="ml-2">${patient.allergies || 'None'}</span>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Chronic Conditions:</span>
                            ${patient.chronic_conditions || 'None'}
                        </p>
                    </div>
                </div>

                <div class="flex space-x-2 mt-4">
                    <button type="button" onclick="viewMedicalHistory(${patient.id})"
                            class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                        <i class="fas fa-history mr-2"></i>
                        View Full History
                    </button>
                </div>
            `;

                        document.getElementById('patientInfo').innerHTML = html;
                        document.getElementById('consultationForm').classList.remove('hidden');

                        // Scroll to form
                        document.getElementById('consultationForm').scrollIntoView({behavior: 'smooth'});
                    }
                });
        }

        function startTeleconsultation(patientId) {
            selectPatient(patientId);
        }

        function closeConsultationForm() {
            document.getElementById('consultationForm').classList.add('hidden');
            document.getElementById('selectedPatientId').value = '';
            document.getElementById('patientInfo').innerHTML = '';
        }

        // Teleconsultation form submission
        document.getElementById('teleconsultationForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Handle nested vitals object
            const vitals = {};
            Object.keys(data).forEach(key => {
                if (key.startsWith('vitals[')) {
                    const vKey = key.match(/\[(.*?)\]/)[1];
                    vitals[vKey] = data[key];
                    delete data[key];
                }
            });

            if (Object.keys(vitals).length > 0) {
                data.vitals = vitals;
            }

            fetch('/doctor/teleconsultation/start', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Teleconsultation started successfully', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to start teleconsultation', 'error');
                });
        });

        function viewMedicalHistory(patientId) {
            fetch(`/doctor/patient/${patientId}/history`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const patient = data.data.patient;
                        const stats = data.data.statistics;

                        let html = `
                <div class="space-y-6">
                    <!-- Patient Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">${patient.name}</h4>
                                <p class="text-sm text-gray-600">
                                    ${patient.emrn} • ${patient.age_formatted} • ${patient.gender} • ${patient.blood_group || 'Blood group not specified'}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-700">Total Visits: ${stats.total_visits}</p>
                                <p class="text-sm text-gray-600">Prescriptions: ${stats.total_prescriptions}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <p class="text-2xl font-bold text-blue-600">${stats.total_visits}</p>
                                <p class="text-xs text-gray-500">Total Visits</p>
                            </div>
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <p class="text-2xl font-bold text-green-600">${stats.total_prescriptions}</p>
                                <p class="text-xs text-gray-500">Prescriptions</p>
                            </div>
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <p class="text-2xl font-bold text-yellow-600">${stats.chronic_conditions}</p>
                                <p class="text-xs text-gray-500">Chronic Conditions</p>
                            </div>
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <p class="text-2xl font-bold text-red-600">${stats.allergies}</p>
                                <p class="text-xs text-gray-500">Allergies</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Visits -->
                    <div>
                        <h5 class="font-medium text-gray-700 mb-3">Recent Visits</h5>
                        <div class="space-y-3">
            `;

                        if (patient.visits && patient.visits.length > 0) {
                            patient.visits.forEach(visit => {
                                html += `
                        <div class="border rounded-lg p-3 hover:bg-gray-50">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">
                                    ${new Date(visit.created_at).toLocaleDateString()}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full
                                    ${visit.status === 'completed' ? 'bg-green-100 text-green-800' :
                                    visit.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-blue-100 text-blue-800'}">
                                    ${visit.status}
                                </span>
                            </div>

                            ${visit.diagnoses && visit.diagnoses.length > 0 ?
                                    `<p class="text-sm text-gray-600 mb-1">
                                    <span class="font-medium">Diagnosis:</span>
                                    ${visit.diagnoses[0].diagnosis}
                                </p>` : ''
                                }

                            ${visit.latest_vital ?
                                    `<div class="text-xs text-gray-500 mt-2">
                                    Vitals: ${visit.latest_vital.temperature ? visit.latest_vital.temperature + '°C' : 'N/A'} |
                                    BP: ${visit.latest_vital.blood_pressure_systolic || 'N/A'}/${visit.latest_vital.blood_pressure_diastolic || 'N/A'} |
                                    Pulse: ${visit.latest_vital.pulse || 'N/A'}
                                </div>` : ''
                                }
                        </div>
                    `;
                            });
                        } else {
                            html += `<p class="text-gray-500 text-center py-4">No visit history</p>`;
                        }

                        html += `
                        </div>
                    </div>

                    <!-- Lab Reports -->
                    <div>
                        <h5 class="font-medium text-gray-700 mb-3">Recent Lab Reports</h5>
                        <div class="space-y-3">
            `;

                        if (patient.lab_reports && patient.lab_reports.length > 0) {
                            patient.lab_reports.forEach(report => {
                                html += `
                        <div class="border rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">
                                    ${report.test_name || 'Lab Test'}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full
                                    ${report.status === 'completed' ? 'bg-green-100 text-green-800' :
                                    'bg-yellow-100 text-yellow-800'}">
                                    ${report.status}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                ${new Date(report.created_at).toLocaleDateString()}
                            </p>
                        </div>
                    `;
                            });
                        } else {
                            html += `<p class="text-gray-500 text-center py-4">No lab reports</p>`;
                        }

                        html += `
                        </div>
                    </div>
                </div>
            `;

                        document.getElementById('medicalHistoryContent').innerHTML = html;
                        document.getElementById('medicalHistoryModal').classList.remove('hidden');
                    }
                });
        }

        function closeMedicalHistory() {
            document.getElementById('medicalHistoryModal').classList.add('hidden');
        }

        // Close search results when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#patientSearch') && !e.target.closest('#searchResults')) {
                document.getElementById('searchResults').classList.add('hidden');
            }
        });
    </script>
@endsection
