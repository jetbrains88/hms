<div id="visit-history-modal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Visit History</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl"
                        onclick="window.closeVisitHistoryModal()">
                    &times;
                </button>
            </div>
        </div>

        <div class="p-6">
            <div id="visit-history-content">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    // Visit History Modal Functions
    window.showVisitHistoryModal = function (visits) {
        const modal = document.getElementById('visit-history-modal');
        const content = document.getElementById('visit-history-content');

        if (!visits || visits.length === 0) {
            content.innerHTML = `
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 text-gray-300">
                    <i class="fas fa-calendar-times text-5xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No Visit History</h4>
                <p class="text-gray-500">This patient has no recorded visits.</p>
            </div>
        `;
        } else {
            content.innerHTML = `
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h4 class="font-bold text-gray-800 mb-2">Total Visits: ${visits.length}</h4>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">${visits.filter(v => v.status === 'completed').length}</div>
                            <div class="text-gray-600">Completed</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">${visits.filter(v => v.status === 'in_progress').length}</div>
                            <div class="text-gray-600">In Progress</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">${visits.filter(v => v.status === 'waiting').length}</div>
                            <div class="text-gray-600">Waiting</div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Token</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vitals</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${visits.map(visit => `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    ${new Date(visit.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-900">
                                    ${visit.queue_token || 'N/A'}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    <span class="capitalize">${visit.visit_type || 'routine'}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        ${visit.status === 'completed' ? 'bg-green-100 text-green-800' :
                visit.status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                    'bg-yellow-100 text-yellow-800'}">
                                        ${visit.status ? visit.status.replace('_', ' ') : 'N/A'}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    ${visit.vitals ? `
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs">${visit.vitals.temperature || '--'}°</span>
                                        <span class="text-xs">${visit.vitals.pulse || '--'}</span>
                                        <span class="text-xs">${visit.vitals.blood_pressure_systolic || '--'}/${visit.vitals.blood_pressure_diastolic || '--'}</span>
                                    </div>
                                    ` : 'No vitals'}
                                </td>
                            </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        }

        modal.classList.remove('hidden');
    };

    window.closeVisitHistoryModal = function () {
        document.getElementById('visit-history-modal').classList.add('hidden');
    };

    // Close modal when clicking outside
    document.getElementById('visit-history-modal').addEventListener('click', function (event) {
        if (event.target === this) {
            window.closeVisitHistoryModal();
        }
    });
</script>
