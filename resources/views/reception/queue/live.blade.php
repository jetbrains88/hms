<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Queue - {{ $branchId }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .queue-item {
            animation: slideIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">Live Queue</h1>
            <p class="text-slate-600">Branch: <span class="font-semibold">{{ $branchId }}</span></p>
            <div class="mt-4 flex justify-center gap-8">
                <div class="bg-white rounded-xl px-6 py-3 shadow-lg">
                    <div class="text-sm text-slate-500">Waiting</div>
                    <div class="text-3xl font-bold text-amber-500" id="waiting-count">{{ $waitingQueue->count() }}</div>
                </div>
                <div class="bg-white rounded-xl px-6 py-3 shadow-lg">
                    <div class="text-sm text-slate-500">In Progress</div>
                    <div class="text-3xl font-bold text-green-500" id="in-progress-count">{{ $inProgress->count() }}</div>
                </div>
            </div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Waiting Queue -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-slate-700 mb-4 flex items-center">
                    <span class="w-3 h-3 bg-amber-500 rounded-full mr-2 animate-pulse-slow"></span>
                    Waiting Queue
                </h2>
                <div class="space-y-3" id="waiting-queue">
                    @forelse($waitingQueue as $index => $visit)
                    <div class="queue-item bg-amber-50 rounded-xl p-4 border-l-4 border-amber-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-sm text-amber-600 font-medium">Token</span>
                                <div class="text-2xl font-bold text-amber-700">{{ $visit->queue_token }}</div>
                                <div class="text-lg font-semibold text-slate-700 mt-1">{{ $visit->patient->name }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-500">#{{ $index + 1 }}</span>
                                <div class="text-sm text-slate-500 mt-1">{{ $visit->created_at->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-slate-500 py-8">No patients waiting</p>
                    @endforelse
                </div>
            </div>
            
            <!-- In Progress -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-slate-700 mb-4 flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    In Progress
                </h2>
                <div class="space-y-3" id="in-progress-queue">
                    @forelse($inProgress as $visit)
                    <div class="queue-item bg-green-50 rounded-xl p-4 border-l-4 border-green-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-sm text-green-600 font-medium">Token</span>
                                <div class="text-2xl font-bold text-green-700">{{ $visit->queue_token }}</div>
                                <div class="text-lg font-semibold text-slate-700 mt-1">{{ $visit->patient->name }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-slate-500">Started</div>
                                <div class="text-sm font-medium text-green-600">{{ $visit->updated_at->format('H:i') }}</div>
                            </div>
                        </div>
                        @if($visit->doctor)
                        <div class="mt-2 text-sm text-slate-600">
                            <i class="fas fa-user-md mr-1"></i>Dr. {{ $visit->doctor->name }}
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="text-center text-slate-500 py-8">No active consultations</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Last Updated -->
        <div class="text-center mt-8 text-sm text-slate-500">
            Last updated: <span id="last-updated">{{ now()->format('H:i:s') }}</span>
        </div>
    </div>

    <script>
        // Auto-refresh queue every 30 seconds
        function refreshQueue() {
            fetch(window.location.href + '?json=1')
                .then(response => response.json())
                .then(data => {
                    updateQueue('waiting-queue', data.waiting, 'amber');
                    updateQueue('in-progress-queue', data.in_progress, 'green');
                    document.getElementById('waiting-count').textContent = data.waiting.length;
                    document.getElementById('in-progress-count').textContent = data.in_progress.length;
                    document.getElementById('last-updated').textContent = new Date().toLocaleTimeString();
                })
                .catch(error => console.error('Failed to refresh queue:', error));
        }
        
        function updateQueue(containerId, items, color) {
            const container = document.getElementById(containerId);
            if (!container) return;
            
            if (items.length === 0) {
                container.innerHTML = '<p class="text-center text-slate-500 py-8">No patients in queue</p>';
                return;
            }
            
            let html = '';
            items.forEach((item, index) => {
                html += `
                    <div class="queue-item bg-${color}-50 rounded-xl p-4 border-l-4 border-${color}-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-sm text-${color}-600 font-medium">Token</span>
                                <div class="text-2xl font-bold text-${color}-700">${item.queue_token}</div>
                                <div class="text-lg font-semibold text-slate-700 mt-1">${item.patient.name}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-500">#${index + 1}</span>
                                <div class="text-sm text-slate-500 mt-1">${new Date(item.created_at).toLocaleTimeString()}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }
        
        // Refresh every 30 seconds
        setInterval(refreshQueue, 30000);
    </script>
</body>
</html>