@extends('layouts.app')

@section('title', 'Medical Reports')
@section('page-title', 'Medical Reports')
@section('page-description', 'Analytics and reports of your medical practice')

@section('content')
<div class="space-y-6">
    <!-- Report Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form id="reportFilterForm" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                    <select name="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="monthly">Monthly Summary</option>
                        <option value="patient">Patient Report</option>
                        <option value="prescription">Prescription Analysis</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d', strtotime('-1 month')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-50 to-indigo-100 text-indigo-700 font-bold  rounded-lg hover:from-indigo-950 hover:to-indigo-600 hover:text-white transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Generate Report
                </button>
                <button type="button" onclick="downloadReport()" class="px-4 py-2 bg-gradient-to-r from-teal-50 to-green-100 text-green-700 font-bold  rounded-lg hover:from-teal-950 hover:to-green-600 hover:text-white transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Download
                </button>
            </div>
        </form>
    </div>

    <!-- Monthly Statistics -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Monthly Statistics (Last 6 Months)</h3>
        
        <!-- Visits Chart -->
        <div class="mb-8">
            <h4 class="font-medium text-gray-700 mb-4">Patient Visits Trend</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Visits</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg. Time (min)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($monthlyStats['visits'] as $stat)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $stat->month }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $stat->total_visits }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $stat->completed_visits }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($stat->avg_time, 1) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                No data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Diagnoses Chart -->
        <div>
            <h4 class="font-medium text-gray-700 mb-4">Diagnoses Analysis</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Diagnoses</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urgent Cases</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chronic Cases</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($monthlyStats['diagnoses'] as $stat)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $stat->month }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $stat->total_diagnoses }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $stat->urgent_cases }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $stat->chronic_cases }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                No data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm">
            <div class="text-center">
                <div class="text-4xl font-bold text-blue-900 mb-2">150</div>
                <div class="text-sm font-medium text-blue-700">Total Patients This Month</div>
                <div class="text-xs text-blue-600 mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>
                    12% increase from last month
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm">
            <div class="text-center">
                <div class="text-4xl font-bold text-green-900 mb-2">45m</div>
                <div class="text-sm font-medium text-green-700">Average Consultation Time</div>
                <div class="text-xs text-green-600 mt-2">
                    <i class="fas fa-clock mr-1"></i>
                    5m faster than average
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 shadow-sm">
            <div class="text-center">
                <div class="text-4xl font-bold text-purple-900 mb-2">98%</div>
                <div class="text-sm font-medium text-purple-700">Patient Satisfaction Rate</div>
                <div class="text-xs text-purple-600 mt-2">
                    <i class="fas fa-star mr-1"></i>
                    Based on 120 reviews
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('reportFilterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    showNotification('Generating report...', 'info');
    
    // In a real application, this would generate and display the report
    // For now, we'll just show a success message
    setTimeout(() => {
        showNotification('Report generated successfully', 'success');
    }, 1500);
});

function downloadReport() {
    const formData = new FormData(document.getElementById('reportFilterForm'));
    const data = Object.fromEntries(formData);
    
    fetch('/doctor/reports/download', {
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
            showNotification('Report download started', 'success');
            
            // In a real application, you would trigger the download
            // For example, create a download link with the report URL
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to download report', 'error');
    });
}
</script>
@endsection