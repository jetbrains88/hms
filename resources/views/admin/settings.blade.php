{{-- resources/views/admin/settings.blade.php --}}
<x-layout>
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-6">System Settings</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: General Settings -->
            <div class="lg:col-span-2 space-y-6">
                <!-- General Settings Card -->
                <div class="bg-white rounded-lg shadow">
                    <h2 class="p-4 text-xl font-bold border-b">General Settings</h2>
                    <form action="{{ route('admin.settings.update', ['id' => $id]) }}" method="POST" class="p-4">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Hospital Name</label>
                                <input type="text" name="hospital_name" 
                                    value="{{ old('hospital_name', 'NHMP Hospital') }}" 
                                    class="w-full border rounded p-2" 
                                    placeholder="Enter hospital name">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">System Title</label>
                                <input type="text" name="system_title" 
                                    value="{{ old('system_title', 'NHMP HMS') }}" 
                                    class="w-full border rounded p-2" 
                                    placeholder="Enter system title">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Contact Email</label>
                                <input type="email" name="contact_email" 
                                    value="{{ old('contact_email', 'admin@hospital.com') }}" 
                                    class="w-full border rounded p-2" 
                                    placeholder="Enter contact email">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Contact Phone</label>
                                <input type="text" name="contact_phone" 
                                    value="{{ old('contact_phone', '+92 300 1234567') }}" 
                                    class="w-full border rounded p-2" 
                                    placeholder="Enter contact phone">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Address</label>
                                <textarea name="address" rows="3" 
                                    class="w-full border rounded p-2" 
                                    placeholder="Enter hospital address">{{ old('address', 'NHMP Headquarters, Islamabad') }}</textarea>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="maintenance_mode" id="maintenance_mode" 
                                    value="1" class="rounded border-gray-300 mr-2">
                                <label for="maintenance_mode" class="text-sm">Enable Maintenance Mode</label>
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Save General Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Queue Settings Card -->
                <div class="bg-white rounded-lg shadow">
                    <h2 class="p-4 text-xl font-bold border-b">Queue Settings</h2>
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-4">
                        @csrf
                        <input type="hidden" name="settings_type" value="queue">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Token Prefix</label>
                                <input type="text" name="token_prefix" 
                                    value="{{ old('token_prefix', 'T') }}" 
                                    class="w-full border rounded p-2 w-20" 
                                    placeholder="T">
                                <p class="text-xs text-gray-500 mt-1">Prefix for queue tokens (e.g., T001)</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Daily Token Reset Time</label>
                                <select name="token_reset_time" class="w-full border rounded p-2">
                                    <option value="00:00">12:00 AM (Midnight)</option>
                                    <option value="06:00">6:00 AM</option>
                                    <option value="08:00" selected>8:00 AM</option>
                                    <option value="09:00">9:00 AM</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Max Patients Per Day</label>
                                <input type="number" name="max_patients_per_day" 
                                    value="{{ old('max_patients_per_day', 200) }}" 
                                    min="1" max="1000"
                                    class="w-full border rounded p-2">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Average Consultation Time (minutes)</label>
                                <input type="number" name="avg_consultation_time" 
                                    value="{{ old('avg_consultation_time', 15) }}" 
                                    min="5" max="60"
                                    class="w-full border rounded p-2">
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Save Queue Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Quick Actions and System Info -->
            <div class="space-y-6">
                <!-- Quick Actions Card -->
                <div class="bg-white rounded-lg shadow">
                    <h2 class="p-4 text-xl font-bold border-b">Quick Actions</h2>
                    <div class="p-4 space-y-3">
                        <button onclick="clearCache()" 
                            class="w-full text-left px-4 py-3 border rounded hover:bg-gray-50 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Clear Cache
                        </button>
                        
                        <button onclick="backupDatabase()" 
                            class="w-full text-left px-4 py-3 border rounded hover:bg-gray-50 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            Backup Database
                        </button>
                        
                        <button onclick="showLogs()" 
                            class="w-full text-left px-4 py-3 border rounded hover:bg-gray-50 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            View System Logs
                        </button>
                        
                        <button onclick="exportData()" 
                            class="w-full text-left px-4 py-3 border rounded hover:bg-gray-50 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Data
                        </button>
                    </div>
                </div>

                <!-- System Information Card -->
                <div class="bg-white rounded-lg shadow">
                    <h2 class="p-4 text-xl font-bold border-b">System Information</h2>
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Laravel Version:</span>
                            <span class="font-mono">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">PHP Version:</span>
                            <span class="font-mono">{{ phpversion() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Environment:</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                {{ app()->environment() }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Debug Mode:</span>
                            <span class="px-2 py-1 {{ config('app.debug') ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }} text-xs rounded">
                                {{ config('app.debug') ? 'ON' : 'OFF' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Timezone:</span>
                            <span>{{ config('app.timezone') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Database:</span>
                            <span>{{ config('database.default') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Server Time:</span>
                            <span>{{ now()->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone Card -->
                <div class="bg-white rounded-lg shadow border-2 border-red-200">
                    <h2 class="p-4 text-xl font-bold border-b text-red-600">Danger Zone</h2>
                    <div class="p-4 space-y-3">
                        <p class="text-sm text-gray-600 mb-3">These actions are irreversible. Use with caution.</p>
                        
                        <button onclick="resetSystem()" 
                            class="w-full text-left px-4 py-3 border border-red-300 rounded hover:bg-red-50 text-red-600 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Reset System Data
                        </button>
                        
                        <button onclick="purgeLogs()" 
                            class="w-full text-left px-4 py-3 border border-red-300 rounded hover:bg-red-50 text-red-600 flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Purge All Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearCache() {
            if (confirm('Clear all cached data?')) {
                fetch('{{ route("admin.settings.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ action: 'clear_cache' })
                }).then(response => {
                    if (response.ok) {
                        alert('Cache cleared successfully!');
                    }
                });
            }
        }

        function backupDatabase() {
            if (confirm('Create a database backup?')) {
                alert('Backup feature would be implemented here.');
                // Implement backup logic
            }
        }

        function showLogs() {
            alert('Log viewer would open here.');
            // Implement log viewing logic
        }

        function exportData() {
            if (confirm('Export all system data?')) {
                alert('Export feature would be implemented here.');
                // Implement export logic
            }
        }

        function resetSystem() {
            if (confirm('WARNING: This will delete ALL data except admin users. Are you absolutely sure?')) {
                const password = prompt('Enter admin password to confirm:');
                if (password) {
                    alert('System reset would be performed here.');
                    // Implement system reset logic
                }
            }
        }

        function purgeLogs() {
            if (confirm('Delete ALL system logs? This cannot be undone.')) {
                alert('Log purging would be implemented here.');
                // Implement log purging logic
            }
        }
    </script>
</x-layout>